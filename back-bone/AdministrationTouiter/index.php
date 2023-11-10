<?php
declare(strict_types=1);

require_once "src/db/ConnectionFactory.php";


ConnectionFactory::setConfig('db.config.ini');
session_start();

include "constantes.php";
$path = path;
echo <<<HTML
<!doctype html><html lang="fr">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="styleIndex.css">
    <title>Touiter Back-bone</title>
</head>
<body>

<header>
    <h1>Back-bone Touiter.app</h1>
    <a href="index.php" style="text-decoration: underline">Accueil</a>
HTML;

// si l'utilisateur est connecté et qu'il a les permissions nécessaires, on affiche les liens vers les pages influenceurs et tendances
if(!empty($_SESSION) && $_SESSION["permissions"] >= 1){
echo <<<HTML
    <a href="influenceurs.php"> Influenceurs</a>
    <a href="tendances.php">Tendances</a>
    <h3>Administrateur : {$_SESSION["username"]}</h3>
    HTML;
}
echo "</header>";

// si l'utilisateur n'est pas connecté, on affiche le formulaire de connexion
if(empty($_SESSION)){
    echo <<<HTML
        <main>
            <p>
                Bienvenue dans le système d\'administration back-bone de Touiter.app.<br>
                Avant toute chose vous devez vous connecter !
            </p>
            
            
            <form class="login" action="{$path}login.php" method="post">
                <h3>Se connecter &agrave; votre compte</h3>
                <div class="input">
                    <input autocomplete="off" value="" id="login_username" type="text" name="username" required>
                    <label for="login_username">Identifiant</label>
                </div>
        
                <div class="input password">
                    <input autocomplete="off" value="" id="login_password" type="password" name="password" required>
                    <label for="login_password">Mot de passe</label>
                </div>
        
                <input class="bouton" type="submit" value="Se connecter">
            </form>
        </main>
        HTML;
}
else{
    // sinon on affiche un message de bienvenue et un bouton pour se déconnecter
    echo <<<HTML
        <form class="unlogin" action="{$path}unlogin.php" method="post">
            <input class="bouton" type="submit" value="Se déconnecter">
        </form>
    HTML;
}





echo '</body>
</html>';


