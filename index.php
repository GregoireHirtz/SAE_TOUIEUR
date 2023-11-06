<?php
declare(strict_types=1);

// Récupérez l'URL après la réécriture
$request = $_SERVER['REQUEST_URI'];

$parts = explode('/', $request);
$idMessage = end($parts);

echo "Affichage du message avec l'identifiant : $idMessage";
