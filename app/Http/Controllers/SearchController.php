<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Search for products based on query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchProducts(Request $request)
    {
        $query = $request->input('query');
        $categoryId = $request->input('category_id');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');

        $products = Product::query()
            ->when($query, function ($q) use ($query) {
                return $q->where('name', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            })
            ->when($categoryId, function ($q) use ($categoryId) {
                return $q->whereHas('categories', function ($query) use ($categoryId) {
                    $query->where('categories.id', $categoryId);
                });
            })
            ->when($minPrice, function ($q) use ($minPrice) {
                return $q->where('price', '>=', $minPrice);
            })
            ->when($maxPrice, function ($q) use ($maxPrice) {
                return $q->where('price', '<=', $maxPrice);
            })
            ->with('categories')
            ->get();

        return response()->json($products);
    }
}
