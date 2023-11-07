<?php
declare(strict_types=1);
namespace touiteur\dispatch;

use Exception;
use touiteur\auth\Auth;
use touiteur\auth\Session;
use touiteur\exception\InvalideTypePage;

class Dispatcher{

    public function run(string $page): void{
        #echo $page;
        switch ($page){
            case TYPE_PAGE_ACCUEIL:
                break;

            case TYPE_PAGE_PROFILE:
                break;

            case TYPE_PAGE_TOUIT:
                break;

            case TYPE_PAGE_LOGIN:
                $htmlLoginMessage = '';

                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    // filtrage entré user
                    $login = htmlspecialchars($_POST['email']);
                    $password = htmlspecialchars($_POST['password']);

                    $valide = Auth::authenticate($login, $password);
                }


                include 'src/vue/login.html';
                break;

            case TYPE_PAGE_SIGNIN:
                break;

            case TYPE_PAGE_NOTFOUND:
                break;

            default:
                throw new InvalideTypePage($page);
        }
    }
}