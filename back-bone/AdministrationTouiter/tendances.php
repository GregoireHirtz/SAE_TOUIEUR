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
        <a href="influenceurs.php">Influenceurs</a>
        <a href="tendances.php" style="text-decoration: underline">Tendances</a>
    </header>
HTML;

    $db = ConnectionFactory::makeConnection();
    // on récupère les tags par ordre décroissant du nombre d'utilisations (popularité)
    $st = $db->prepare("CALL obtenirTendancesDec()");
    $st->execute();

    // on affiche les tags par ordre décroissant de popularité
    echo "<h3>Liste des tags les plus populaires :</h3>";
    echo "<ol>";
    foreach ($st->fetchAll() as $tag) {
        // on récupère les infos de chaque tag
        $libelle = $tag["libelle"];
        $id = $tag["idTag"];
        $dateCreation = $tag["dateCreation"];
        $descriptionTag = $tag["descriptionTag"];
        $nbUtilisations = $tag["nbUtilisationsTag"];
        // on affiche les infos de chaque tag par ordre décroissant de popularité sous forme ordonnée
        echo <<<HTML
            <li>
                <ul>
                    <li>id: $id</li>
                    <li>Libelle : $libelle</li>
                    <li>date de création : $dateCreation</li>
                    <li>description : $descriptionTag</li>
                    <li>Nombre de fois où le tag a été utilisé : $nbUtilisations</li>
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


