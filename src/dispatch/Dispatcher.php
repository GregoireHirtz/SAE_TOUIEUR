<?php
declare(strict_types=1);
namespace touiteur\dispatch;

use PDO;
use touiteur\action\accueil\GenererTouit;
use touiteur\action\touit\ActionNouveauTouit;
use touiteur\action\touit\ActionSupprimerTouit;
use touiteur\action\accueil\GenererAccueil;
use touiteur\action\accueil\GenererAccueilAbonne;
use touiteur\action\accueil\GenererAccueilTag;
use touiteur\action\accueil\GenererFooter;
use touiteur\action\accueil\GenererHeader;
use touiteur\action\accueil\GenererInfluenceurs;
use touiteur\action\accueil\GenererProfil;
use touiteur\action\GestionLike;
use touiteur\action\login\ActionLogin;
use touiteur\action\login\ActionSignin;
use touiteur\auth\Auth;
use touiteur\auth\Session;
use touiteur\classe\Touite;
use touiteur\classe\User;
use touiteur\db\ConnectionFactory;
use touiteur\exception\InvalideTypePage;
use touiteur\render\RenderTouite;


/**
 * Classe qui permet de dispatcher les requêtes
 */
class Dispatcher{

    /**
     * @param string $page le type de page à afficher
     * @return void affiche la page demandée
     * @throws InvalideTypePage si le type de page n'est pas valide
     * Méthode qui permet de dispatcher les requêtes
     */
	public function run(string $page): void{
		switch ($page){

			// afficher les touite decroissant
			case TYPE_PAGE_ACCUEIL:

				if (!in_array("data", array_keys($_GET))) {
					$_GET["data"] = "accueil";
				}

				$htmlHeader = GenererHeader::execute();
				$htmlFooter = GenererFooter::execute();
				switch ($_GET["data"]){
					case "accueil":
						$htmlHeader = GenererHeader::execute();
						$htmlMain = GenererAccueil::execute();
						$htmlFooter = GenererFooter::execute();
						break;

					case "tag":

						if (empty($_SESSION)){
							Dispatcher::redirection("login");
							break;
						}
						$htmlHeader = GenererHeader::execute();
						$htmlMain = GenererAccueilTag::execute($_SESSION["username"]);
						$htmlFooter = GenererFooter::execute();
						break;


					case "abonne":
						if (empty($_SESSION)){
							Dispatcher::redirection("login");
							break;
						}
						$htmlHeader = GenererHeader::execute();
						$htmlMain = GenererAccueilAbonne::execute($_SESSION["username"]);
						$htmlFooter = GenererFooter::execute();
						break;


					default:
						Dispatcher::redirection("accueil");
						break;
				}
				include 'src/vue/accueil.html';
				break;

			case TYPE_PAGE_PROFIL:

				global $parts;
				$username = $parts[1];
                // On vérifie bien que l'utilisateur existe dans la bd
				if (!Auth::usernameExists($username)){
					//header("Location: /notfound");
				}
                else {
                    $htmlHeader = GenererHeader::execute();

                    // On a bien vérifié que l'username est bon donc on peut afficher le profil de l'utilisateur demandé
                    $htmlMain = GenererProfil::execute($username);

                    $htmlFooter = GenererFooter::execute();

                    include 'src/vue/profil.html';
                }
				break;

			case TYPE_PAGE_TOUIT:
				$htmlHeader = GenererHeader::execute();
				$htmlFooter = "";
				$htmlMain = GenererTouit::execute();

				include 'src/vue/touit.html';
				break;

			case TYPE_PAGE_LOGIN:
				$htmlHeader = GenererHeader::execute();


				// si déjà connecté ==> accueil
				if (!empty($_SESSION)){
					// redirection vers accueil
					//header("Location: /");
					Dispatcher::redirection("");
				}
				$htmlLoginMessage = '';
				$htmlSigninMessage = '';
				// si validation formulaire
				if ($_SERVER['REQUEST_METHOD'] == 'POST') {
					// -- FORMULAIRE LOGIN -- //
					if ($_GET["action"]=="login"){
						$htmlLoginMessage = ActionLogin::execute();
					}

					// -- FORMULAIRE SIGNIN -- //
					elseif ($_GET["action"]=="signin"){
						$htmlSigninMessage = ActionSignin::execute();
					}
				}
				include 'src/vue/login.html';
				break;


			case TYPE_PAGE_UNLOGIN:
				Session::unloadSession();
				//header("Location: /");
				Dispatcher::redirection("");
				break;

			case TYPE_PAGE_NOTFOUND:
				var_dump("404");
				break;

			case TYPE_PAGE_LIKE:
				// SI SESSION VIDE, RENVOYER VERS LOGIN
				if (empty($_SESSION)){
					Dispatcher::redirection("login");
					break;
				}
				GestionLike::execute();
				$redirection = $_GET["redirect"];
				Dispatcher::redirection($redirection);
				break;

			case TYPE_PAGE_ABONNEMENT:

				// verification URL valide
				if (!in_array("username", array_keys($_GET))) {
					Dispatcher::redirection("");
					//header("Location: /");
				}

				// SI SESSION VIDE, RENVOYER VERS LOGIN
				if (empty($_SESSION)){
					Dispatcher::redirection("login");
					//header("Location: /login");
				}

				$cible = $_GET["username"];
				$username = $_SESSION["username"];

				//verification username donne valide
				$db = ConnectionFactory::makeConnection();
				$st = $db->prepare("CALL EtreUserValide(\"{$cible}\")");
				$st->execute();
				$row = $st->fetch();

				$cibleValide = $row["nb_ligne"];
				if ($cibleValide==0){
					Dispatcher::redirection("");
					//header("Location: /");
					break;
				}
				$db = ConnectionFactory::makeConnection();
				$st = $db->prepare("CALL verifierUsernameInAbonnement(\"{$username}\", \"{$cible}\")");
				$st->execute();
				$nb_ligne = $st->fetch()["nb_ligne"];
				// SI DEJA ABONNER
				if ($nb_ligne != 0){
					$db = ConnectionFactory::makeConnection();
					$db->prepare("CALL desabonnerUser(\"{$username}\", \"{$cible}\")")->execute();
				}
				// SI PAS ABONNER
				else{
					$email = User::loadUserFromUsername($username)->email;
					$emailCible = User::loadUserFromUsername($cible)->email;

					$db = ConnectionFactory::makeConnection();
					$db->prepare("CALL sabonnerUtilisateur(\"{$email}\", \"{$emailCible}\")")->execute();
				}

				Dispatcher::redirection("");
				//header("Location: ./");
				break;

            case TYPE_PAGE_PUBLIER:
				if (empty($_SESSION))
					Dispatcher::redirection("login");

				ActionNouveauTouit::execute();

				$redirection = $_POST["redirect"];
				Dispatcher::redirection($redirection);
                break;

            case TYPE_PAGE_INFLUENCEURS:
                if (!empty($_SESSION)){
                    // redirection vers accueil
                    Dispatcher::redirection("login");
                }
                else {
                    $htmlHeader = GenererHeader::execute();
                    $htmlMain = GenererInfluenceurs::execute();
                    $htmlFooter = GenererFooter::execute();
                    include("src/vue/influenceurs.html");
                }
                break;

			case TYPE_PAGE_SUPPRIMER:
				if (empty($_SESSION))
					Dispatcher::redirection("login");

				ActionSupprimerTouit::execute();

				$redirection = $_GET["redirect"];
				Dispatcher::redirection($redirection);
				break;

			case TYPE_PAGE_TAG:

				break;

			default:
				throw new InvalideTypePage($page);
		}
	}

	public static function redirection(String $url){
		$p = PREFIXE;
		header("Location: /{$p}{$url}");
	}
}