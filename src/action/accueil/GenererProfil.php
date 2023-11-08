<?php

namespace touiteur\action\accueil;

use Cassandra\Function_;
use DateTime;
use touiteur\action\Action;
use touiteur\classe\Tag;
use touiteur\classe\Touite;
use touiteur\db\ConnectionFactory;
use touiteur\render\RenderUser;
use touiteur\classe\User;

class GenererProfil extends Action{

    static public function execute(): String{
        $html = "";
        $liste = self::listeUserAll();

        foreach ($liste as $userAbo){
            $rT = new RenderUser($userAbo);
            $html .= $rT->genererAffichageUser();
        }
        return $html;
    }



    static private function listeUserAll(): array{
        $liste = [];

        var_dump($_SESSION["email"]);
        $email = $_SESSION["email"];

        $db = ConnectionFactory::makeConnection();
        $st = $db->prepare("CALL obtenirAbonnementUtilisateur(\"$email\")");
        $st->execute();


        $row = $st->fetchAll();
        var_dump($row);
        foreach ($row as $r){
            $liste[] = new User($r["emailUt"], $r["nomUt"], $r["prenomUt"], $r["username"], new DateTime($r["dateInscription"]), $r["permissions"]);
        }
        return $liste;
    }
}