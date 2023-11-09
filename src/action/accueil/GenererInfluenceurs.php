<?php

namespace touiteur\action\accueil;

class GenererInfluenceurs extends Action{

    public static function execute(): String{
        $html = "";
        $html .= self::genererInfluenceurs();
        return $html;
    }
}