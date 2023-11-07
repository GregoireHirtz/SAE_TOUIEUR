<?php

namespace touiteur\auth;

class Session{

    private $liste = array();

    public static function start(): void{
        session_start();
    }
}