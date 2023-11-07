<?php
declare(strict_types=1);


// ---- IMPORTS ---- //

use touiteur\db\ConnectionFactory;
use \touiteur\dispatch\Dispatcher;
use \touiteur\auth\Session;

// ---- CONSTANTES ---- //

define("TYPE_PAGE_ACCUEIL", "accueil");
define("TYPE_PAGE_PROFILE", "profile");
define("TYPE_PAGE_TOUIT", "touit");
define("TYPE_PAGE_LOGIN", "login");
define("TYPE_PAGE_NOTFOUND", "notfound");

// ---- AUTO-LOADER ---- //

require_once 'vendor/autoload.php';


// ---- BD ---- //

ConnectionFactory::setConfig('db.config.ini');


// ---- ROUTAGE URL ---- //

$url = $_SERVER['REQUEST_URI'];
$url = str_replace("/www/hirtz44u/SAE_TOUITEUR", "", $url);
// Supprimer le "/" à la fin de la chaîne si elle existe
$url = rtrim($url, '/');
$parts = explode('?', $url)[0];
$parts = explode('/', $parts);

$type = TYPE_PAGE_NOTFOUND;

if (count($parts)==1) {
    $type = TYPE_PAGE_ACCUEIL;
}

if (count($parts)==2) {
    if ($parts[1] == 'login'){
        $type = TYPE_PAGE_LOGIN;
    } else{
        $type = TYPE_PAGE_PROFILE;
    }
}

if (count($parts)==3) {
    $type = TYPE_PAGE_TOUIT;
}


// ---- DISPATCHER ---- //

$d = new Dispatcher();
try{
    $d->run($type);
} catch (InvalideTypePage $e){
    $d->run(TYPE_PAGE_NOTFOUND);
}

