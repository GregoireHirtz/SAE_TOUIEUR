<?php

session_start();
// on détruit la session et on redirige vers l'accueil quand l'utilisateur veut se déconnecter
session_destroy();
include "constantes.php";
$path = path;
header("Location: $path");