<?php

namespace touiteur\action\accueil;

use Cassandra\Function_;
use DateTime;
use touiteur\action\Action;
use touiteur\classe\Tag;
use touiteur\classe\Touite;
use touiteur\db\ConnectionFactory;
use touiteur\render\BaseFactory;
use touiteur\render\RenderAbonnement;
use touiteur\classe\User;
use touiteur\render\RenderTouite;

/**
 * Class GenererProfil qui permet de générer l'affichage du profil d'un utilisateur
 */
class GenererProfil extends Action{

    // Constante avec le nom de la procédure qui liste les abonnements d'un utilisateur
    const PROCEDURE_ABONNEMENTS = "obtenirAbonnementUtilisateur";
    // Constante avec le nom de la procédure qui liste les abonnés d'un utilisateur
    const PROCEDURE_ABONNES = "obtenirUtilisateurAbo";

    // TODO: Gérer mieux les exceptions !
    /**
     * @param string|null $username le nom d'utilisateur de l'utilisateur dont on veut afficher le profil
     * @return String la variable finale contenant le html de la page profil
     * @throws \Exception si l'utilisateur n'existe pas
     * Méthode qui génère le html de la page profil
     */
    static public function execute(?string $username = null): String{
        // On récupère l'email de l'utilisateur connecté si on ne lui en a pas donné un spécifique
        if(!is_null($username)) $emailAssocie = User::loadUserFromUsername($username)->email;
        else $emailAssocie = $_SESSION["email"];

        $html = "";

        // Si l'utilisateur n'a pas mis de ?data= dans l'url pour indiquer son choix (publications, abonnements ou abonnés),
        // on affiche ses publications par défaut
        if(!isset($_GET["data"])) $_GET["data"] = "publication";

        // On affiche les publications, les abonnements ou les abonnés en fonction de l'argument data dans l'url
        switch($_GET["data"]){
            // Si l'utilisateur veut afficher ses abonnements
            case "abonnement":
                // On récupère la liste des abonnements de l'utilisateur
                $liste = self::listerAbos(self::PROCEDURE_ABONNEMENTS, $emailAssocie);

                // On génère le html pour chaque abonnement
                foreach ($liste as $userAbo){
                    // On récupère l'utilisateur et la date d'abonnement
                    $user = $userAbo[0];
                    $date = $userAbo[1];
                    // On utilise la classe RenderAbonnement pour générer le html de chaque user
                    $rT = new RenderAbonnement($user, $date);
                    // On ajoute le html généré à la variable finale pour faire un affichage de tous les abonnements
                    $html .= BaseFactory::baseProfil($user)->render();
                }
                break;
            case "abonnes":
                // On récupère la liste des abonnés de l'utilisateur
                $liste = self::listerAbos(self::PROCEDURE_ABONNES, $emailAssocie);

                foreach ($liste as $userAbo){
                    $user = $userAbo[0];
                    $date = $userAbo[1];
                    // On utilise la classe RenderAbonnement pour générer le html de chaque user
                    $rT = new RenderAbonnement($user, $date);
                    // On ajoute le html généré à la variable finale pour faire un affichage de tous les abonnés
                    $html .= BaseFactory::baseProfil($user)->render();
                }
                break;
            default:
                // On récupère la liste des publications de l'utilisateur à partir de son email
                $liste = self::listerPublications($emailAssocie);

                // On génère le html pour chaque publication
                foreach ($liste as $touite){
                    // On utilise la classe RenderTouite pour générer le html de chaque touite
                    $rT = new RenderTouite($touite);
                    // On ajoute le html généré à la variable finale pour faire un affichage de toutes les publications
                    $html .= $rT->genererTouitSimple();
                }
                break;
        }
        return $html;
    }


    /**
     * @param $choixProcedure string le nom de la procédure à appeler (utiliser les constantes de la classe GenererProfil)
     * @param string $email
     * @return array
     * @throws \Exception
     * Méthode qui liste les abonnements ou les abonnés d'un utilisateur à partir de son email
     */
    static private function listerAbos($choixProcedure, string $email){
        $liste = [];

        // On récupère les abonnements de l'utilisateur à partir de son email
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

    /**
     * @param string $email
     * @return array
     * @throws \Exception
     * Méthode qui liste les publications d'un utilisateur à partir de son email
     */
    static private function listerPublications(string $email){
        $liste = [];

        // On récupère les touites de l'utilisateur à partir de son email
        $db = ConnectionFactory::makeConnection();
        $st = $db->prepare("CALL obtenirTouitesUtilisateur(\"$email\")");
        $st->execute();


        $row = $st->fetchAll();
        // Je récupère les touites de l'utilisateur dans un tableau de Touite
        foreach ($row as $r){
            $liste[] = new Touite($r["idTouite"], $r["texte"], new DateTime($r["date"]), $r["username"], $r["notePertinence"], $r["nbLike"], $r["nbDislike"], $r["nbRetouite"], $r["nbVue"], []);
        }
        return $liste;


    }

}