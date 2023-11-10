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
            <link rel="stylesheet" href="styleInfos.css">
            <title>Touiter Back-bone</title>
        </head>
        <body>
HTML;

// on vérifie que l'utilisateur est bien connecté et qu'il a les permissions nécessaires pour accéder à cette page
if(!empty($_SESSION) && $_SESSION["permissions"] >= 1) {
    echo <<<HTML
    <header>
        <h1>Back-bone Touiter.app</h1>
        <a href="index.php">Accueil</a>
        <a href="influenceurs.php" style="text-decoration: underline">Influenceurs</a>
        <a href="tendances.php">Tendances</a>
    </header>
HTML;

    $db = ConnectionFactory::makeConnection();
    // on récupère les influenceurs par ordre décroissant du nombre d'abonnés
    $st = $db->prepare("CALL obtenirInfluenceursDec()");
    $st->execute();


    // on affiche les influenceurs par ordre décroissant d'influence
    echo "<h3>Liste des influenceurs les plus populaires :</h3>";
    echo "<ol>";
    foreach ($st->fetchAll() as $influenceur) {
        // on récupère les infos de chaque influenceur
        $emailUt = $influenceur["emailUtAbo"];
        $username = $influenceur["username"];
        $nomUt = $influenceur["nomUt"];
        $prenomUt = $influenceur["prenomUt"];
        $dateInscription = $influenceur["dateInscription"];
        $nbAbonnes = $influenceur["nbAbonnes"];
        // on affiche les infos de chaque influenceur par ordre décroissant d'influence sous forme ordonnée
        echo <<<HTML
            <li>
                <ul>
                    <li>Username: $username</li>
                    <li>Email : $emailUt</li>
                    <li>Prenom : $prenomUt</li>
                    <li>Nom : $nomUt</li>
                    <li>Date d'inscription : $dateInscription</li>
                    <li>Nombre d'abonnés : $nbAbonnes</li>
                </ul>
            </li>
        HTML;
    }
    echo "</ol>";
}
else{
    // si l'utilisateur n'est pas connecté ou n'a pas les permissions nécessaires,
    // on l'informe et on le redirige vers l'accueil pour qu'il se connecte avec un compte administrateur
    echo <<<HTML
        Veuillez vous connecter avec un compte administrateur pour accéder à cette page"<br>
        <a href="{$path}index.php">Retour à l'accueil</a>
    HTML;
}

echo '</body>
</html>';


