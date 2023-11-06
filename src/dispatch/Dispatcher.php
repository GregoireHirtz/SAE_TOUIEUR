<?php
declare(strict_types=1);
namespace touiteur\dispatch;

use touiteur\exception\InvalideTypePage;

class Dispatcher{

    public function run(string $page): void{
        echo $page;
        switch ($page){
            case TYPE_PAGE_ACCUEIL:
                break;

            case TYPE_PAGE_PROFILE:
                break;

            case TYPE_PAGE_TOUIT:
                break;

            case TYPE_PAGE_LOGIN:
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