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
				$htmlHeader = '';
				$htmlMain = '';
				$htmlFooter = '';
				break;

			case TYPE_PAGE_PROFILE:
				break;

			case TYPE_PAGE_TOUIT:
				break;

			case TYPE_PAGE_LOGIN:
				$htmlLoginMessage = '';
				$htmlSigninMessage = '';

				// si validation formulaire
				if ($_SERVER['REQUEST_METHOD'] == 'POST') {

					// -- FORMULAIRE LOGIN -- //
					if ($_GET["action"]=="login"){
						// filtrage saisie user
						$username = htmlspecialchars($_POST['username']);
						$password = htmlspecialchars($_POST['password']);

						$valide = Auth::authenticate($username, $password);
						// si connexion valide
						if ($valide){
							// redirection vers accueil
							header("Location: /");
							break;
						}
						// sinon message erreur dans $htmlLoginMessage
						else{
							$htmlLoginMessage = 'Login ou mot de passe incorrect';
						}
					}

					// -- FORMULAIRE SIGNIN -- //
					elseif ($_GET["action"]=="signin"){
						// filtrage saisie user
						$username = htmlspecialchars($_POST['username']);
						$email = htmlspecialchars($_POST['email']);
						$password = htmlspecialchars($_POST['password']);
						$password2 = htmlspecialchars($_POST['password2']);

						$htmlSigninMessage = '<ul>';
						$valide = true;
						$ETAT_VALIDE = 'green';
						$ETAT_INVALIDE = 'red';

						// si username déjà utilisé
						if (Auth::usernameExists($username)){
							$htmlSigninMessage .= "<li class={$ETAT_INVALIDE}>Le nom d'utilisateur est déjà utilisé</li>";
							$valide = false;
						}
						// si email déjà utilisé
						if (Auth::emailExists($email)){
							$htmlSigninMessage .= "<li class={$ETAT_INVALIDE}>L'adresse email est déjà utilisée</li>";
							$valide = false;
						}
						// si password1 != password2
						if ($password != $password2){
							$htmlSigninMessage = "<li class={$ETAT_INVALIDE}>Les mots de passe ne correspondent pas</li>";
							$valide = false;
						}else{
							#$liste = Auth::checkPassword($password);
							$liste = array(false, true, true, true);
							if (in_array(false, $liste)){
								$htmlSigninMessage .= "<li class={$ETAT_INVALIDE}>Le mot de passe ne respecte au moins 1 champs suivant : </li><ul>";
								$a = $liste[0]==true?$ETAT_VALIDE:$ETAT_INVALIDE;
								$htmlSigninMessage .= "<li class={$a}> minimum 8 caractère</li>";
								$a = $liste[1]==true?$ETAT_VALIDE:$ETAT_INVALIDE;
								$htmlSigninMessage .= "<li class={$a}> au moins 1 majuscule</li>";
								$a = $liste[2]==true?$ETAT_VALIDE:$ETAT_INVALIDE;
								$htmlSigninMessage .= "<li class={$a}> au moins 1 chiffre</li>";
								$a = $liste[3]==true?$ETAT_VALIDE:$ETAT_INVALIDE;
								$htmlSigninMessage .= "<li class={$a}> au moins 1 caractère spéciale</li></ul>";
								$valide = false;
							}
						}

						echo "INSCRIPTION";

					}
				}
				include 'src/vue/login.html';
				break;

			case TYPE_PAGE_NOTFOUND:
				break;

			default:
				throw new InvalideTypePage($page);
		}
	}
}