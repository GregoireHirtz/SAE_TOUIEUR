<?php
declare(strict_types=1);
namespace touiteur\dispatch;

use touiteur\action\accueil\GenererHeader;
use touiteur\action\accueil\GenererAccueil;
use touiteur\action\accueil\GenererFooter;

use touiteur\action\accueil\GenererProfil;
use touiteur\action\login\ActionLogin;
use touiteur\action\login\ActionSignin;

use touiteur\auth\Auth;
use touiteur\auth\Session;


use touiteur\db\ConnectionFactory;
use touiteur\classe\User;
use touiteur\exception\InvalideTypePage;

class Dispatcher{

		
	public function run(string $page): void{
		switch ($page){

			// afficher les touite decroissant
			case TYPE_PAGE_ACCUEIL:

				if ($_SERVER['REQUEST_METHOD'] == 'POST'){
					var_dump($_GET);
				}

				$htmlHeader = GenererHeader::execute();
				$htmlMain = GenererAccueil::execute();
				$htmlFooter = GenererFooter::execute();

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
				break;

			case TYPE_PAGE_LOGIN:
				$htmlHeader = GenererHeader::execute();


				// si déjà connecté ==> accueil
				if (!empty($_SESSION)){
					// redirection vers accueil
					header("Location: /");
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
				header("Location: /");
				break;

			case TYPE_PAGE_NOTFOUND:
				var_dump("404");
				break;

			case TYPE_PAGE_ABONNEMENT:

				if (!in_array("username", array_keys($_GET))) {
					header("Location: /");
				}

				// SI SESSION VIDE, RENVOYER VERS LOGIN
				if (empty($_SESSION)){
					header("Location: /login");
				}

				$cible = $_GET["username"];
				$username = $_SESSION["username"];

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

				header("Location: /");
				break;

			default:
				throw new InvalideTypePage($page);
		}
	}
}