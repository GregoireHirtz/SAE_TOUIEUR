<?php

namespace touiteur\action\accueil;

use Cassandra\Function_;
use DateTime;
use touiteur\action\Action;
use touiteur\classe\Tag;
use touiteur\classe\Touite;
use touiteur\db\ConnectionFactory;
use touiteur\render\RenderAbonnement;
use touiteur\classe\User;
use touiteur\render\RenderTouite;

class GenererProfil extends Action{

    // Constante avec le nom de la procédure qui liste les abonnements d'un utilisateur
    const PROCEDURE_ABONNEMENTS = "obtenirAbonnementUtilisateur";
    // Constante avec le nom de la procédure qui liste les abonnés d'un utilisateur
    const PROCEDURE_ABONNES = "obtenirUtilisateurAbo";
    static public function execute(?string $username = null): String{
        // On récupère l'email de l'utilisateur connecté si on ne lui en a pas donné un spécifique
        if(!is_null($username)) $emailAssocie = User::loadUserFromUsername($username)->email;
        else $emailAssocie = $_SESSION["email"];

        $html = "";
        if(!isset($_GET["data"])) $_GET["data"] = "publication";
        switch($_GET["data"]){
            case "abonnement":
                    $liste = self::listerAbos(self::PROCEDURE_ABONNEMENTS, $emailAssocie);

                    foreach ($liste as $userAbo){
                        $user = $userAbo[0];
                        $date = $userAbo[1];
                        $rT = new RenderAbonnement($user, $date);
                        $html .= $rT->genererAffichageUser();
                    }
                break;
            case "abonnes":
                $liste = self::listerAbos(self::PROCEDURE_ABONNES, $emailAssocie);

                foreach ($liste as $userAbo){
                    $user = $userAbo[0];
                    $date = $userAbo[1];
                    $rT = new RenderAbonnement($user, $date);
                    $html .= $rT->genererAffichageUser();
                }
                break;
            default:
                // Si pas d'argument dans listerPublications, on liste les publications de l'utilisateur connecté
                $liste = self::listerPublications($emailAssocie);

                foreach ($liste as $touite){
                    $rT = new RenderTouite($touite);
                    $html .= $rT->genererTouitSimple();
                }
                break;
        }
        return $html;
    }


    static private function listerAbos($choixProcedure, string $email){
        $liste = [];

        $db = ConnectionFactory::makeConnection();
        $st = $db->prepare("CALL $choixProcedure(\"$email\")");
        $st->execute();


        $row = $st->fetchAll();
        // Je dois faire des tableaux à deux dimensions pour stocker l'utilisateur mais aussi la date d'abonnement
        foreach ($row as $r){
            $liste[] = [new User($r["emailUt"], $r["nomUt"], $r["prenomUt"], $r["username"], new DateTime($r["dateInscription"]), $r["permissions"]), new DateTime($r["dateAboUt"])];
        }
        return $liste;
    }

    static private function listerPublications(string $email){
        $liste = [];

        $db = ConnectionFactory::makeConnection();
        $st = $db->prepare("CALL obtenirTouitesUtilisateur(\"$email\")");
        $st->execute();


        $row = $st->fetchAll();
        // Je dois faire des tableaux à deux dimensions pour stocker l'utilisateur mais aussi la date d'abonnement
        foreach ($row as $r){
            $liste[] = new Touite($r["idTouite"], $r["texte"], new DateTime($r["date"]), $r["username"], $r["notePertinence"], $r["nbLike"], $r["nbDislike"], $r["nbRetouite"], $r["nbVue"], []);
        }
        return $liste;


    }

}