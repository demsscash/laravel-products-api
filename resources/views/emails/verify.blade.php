<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Vérification de votre adresse e-mail</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .button {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <h1>Bienvenue sur notre API de produits</h1>
    <p>Merci de vous être inscrit(e). Veuillez cliquer sur le bouton ci-dessous pour vérifier votre adresse e-mail :</p>
    
    <a href="{{ $verificationUrl }}" class="button">Vérifier mon adresse e-mail</a>
    
    <p>Si vous n'avez pas créé de compte, vous pouvez ignorer cet e-mail.</p>
    
    <p>Cordialement,<br>L'équipe de l'API de produits</p>
</body>
</html>