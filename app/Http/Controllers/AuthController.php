<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use App\Mail\VerifyEmail;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Dans Laravel 12, on utilise le middleware directement sur les routes
        // Cette ligne sera commentée et le middleware sera ajouté dans les routes
        // $this->middleware('auth:api', ['except' => ['login', 'register', 'verifyEmail']]);
    }

    /**
     * Register a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $verificationToken = Str::random(60);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        // Store verification token in cache
        Cache::put('email_verification_' . $user->id, $verificationToken, now()->addDays(1));

        // Send verification email
        $verificationUrl = url('/api/verify-email/' . $user->id . '/' . $verificationToken);
        Mail::to($user->email)->send(new VerifyEmail($verificationUrl));

        return response()->json([
            'message' => 'User registered successfully. Please check your email to verify your account.',
            'user' => $user
        ], 201);
    }

    /**
     * Verify user email.
     *
     * @param  int  $id
     * @param  string  $token
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyEmail($id, $token)
    {
        $cachedToken = Cache::get('email_verification_' . $id);

        if (!$cachedToken || $cachedToken !== $token) {
            return response()->json(['message' => 'Invalid verification link'], 400);
        }

        $user = User::findOrFail($id);
        $user->email_verified_at = now();
        $user->save();

        Cache::forget('email_verification_' . $id);

        return response()->json(['message' => 'Email verified successfully'], 200);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $credentials = $request->only('email', 'password');

        if (!$token = Auth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Check if email is verified
        if (Auth::user()->email_verified_at === null) {
            return response()->json(['error' => 'Please verify your email before logging in'], 403);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(Auth::user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(Auth::refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60, // TTL en secondes
            'user' => Auth::user()
        ]);
    }
}
