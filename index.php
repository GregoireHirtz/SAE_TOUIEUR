<?php
declare(strict_types=1);


// ---- IMPORTS ---- //

use touiteur\db\ConnectionFactory;
use \touiteur\dispatch\Dispatcher;
use \touiteur\auth\Session;

// ---- CONSTANTES ---- //

define("TYPE_PAGE_ACCUEIL", "accueil");
define("TYPE_PAGE_PROFIL", "profil");
define("TYPE_PAGE_TOUIT", "touit");
define("TYPE_PAGE_LOGIN", "login");
define("TYPE_PAGE_UNLOGIN", "unlogin");
define("TYPE_PAGE_NOTFOUND", "notfound");
define("TYPE_PAGE_ABONNEMENT", "abonnement");
define("TYPE_PAGE_LIKE", "like");
define("TYPE_PAGE_TAG", "tag");


// ---- AUTO-LOADER ---- //

require_once 'vendor/autoload.php';


// ---- BD ---- //

ConnectionFactory::setConfig('db.config.ini');
session_start();

// ---- ROUTAGE URL ---- //


define("PREFIXE", "SAE_TOUITEUR/");

$url = $_SERVER['REQUEST_URI'];
$url = str_replace(PREFIXE, "", $url);
// Supprimer le "/" à la fin de la chaîne si elle existe
$url = rtrim($url, '/');
$parts = explode('?', $url)[0];
$parts = explode('/', $parts);

$type = TYPE_PAGE_NOTFOUND;

if (count($parts)==1) {
    $type = TYPE_PAGE_ACCUEIL;
}

if (count($parts)==2) {
    if ($parts[1] == TYPE_PAGE_LOGIN){
        $type = TYPE_PAGE_LOGIN;
    }
	elseif ($parts[1] == TYPE_PAGE_UNLOGIN){
		$type = TYPE_PAGE_UNLOGIN;
	}
	elseif ($parts[1] == TYPE_PAGE_NOTFOUND){
		$type = TYPE_PAGE_NOTFOUND;
	}
	elseif ($parts[1] == TYPE_PAGE_ABONNEMENT){
		$type = TYPE_PAGE_ABONNEMENT;
	}
	elseif ($parts[1] == TYPE_PAGE_LIKE){
		$type = TYPE_PAGE_LIKE;
	}
	elseif ($parts[1] == TYPE_PAGE_TAG){
		$type = TYPE_PAGE_TAG;
	}
	else{
        $type = TYPE_PAGE_PROFIL;
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

