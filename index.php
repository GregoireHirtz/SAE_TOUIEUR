<?php
declare(strict_types=1);


// ---- IMPORTS ---- //

use \touiteur\dispatch\Dispatcher;

// ---- CONSTANTES ---- //
define("TYPE_PAGE_ACCUEIL", "accueil");
define("TYPE_PAGE_PROFILE", "profile");
define("TYPE_PAGE_TOUIT", "touit");
define("TYPE_PAGE_LOGIN", "login");
define("TYPE_PAGE_SIGNIN", "signin");
define("TYPE_PAGE_NOTFOUND", "signin");

// ---- AUTO-LOADER ---- //
require_once 'vendor/autoload.php';



// ---- ROUTAGE URL ---- //

$url = $_SERVER['REQUEST_URI'];
$parts = explode('/', $url);

$type = TYPE_PAGE_NOTFOUND;

if (count($parts)==2) {
    if ($parts[1] == ''){  // si localhost/ ==> accueil
        $type = TYPE_PAGE_ACCUEIL;
    } else{ // sinon localhost/xxx => profile xxx
        $type = TYPE_PAGE_PROFILE;
    }
}

if (count($parts)==3) {

    if ($parts[2] == ''){  // si localhost/xxx/ ==> profile xxx
        $type = TYPE_PAGE_PROFILE;
    } else{  // sinon localhost/xxx/yyy => touit yyy de xxx
        $type = TYPE_PAGE_TOUIT;

    }
}

if (count($parts)==4){
    if($parts[3] == ''){ // si localhost/xxx/yyy/ ==> touit yyy de xxx
        $type = TYPE_PAGE_TOUIT;
    }
}

// ---- DISPATCHER ---- //
$d = new Dispatcher();
$d->run($type);

