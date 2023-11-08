<?php

namespace touiteur\action\login;

use touiteur\action\Action;
use touiteur\auth\Auth;
use touiteur\auth\Session;

class ActionSignin extends Action{

	static public function execute(): string{
		$message = '';

		// filtrage saisie user
		$username = htmlspecialchars($_POST['username']);
		$email = htmlspecialchars($_POST['email']);
		$password = htmlspecialchars($_POST['password']);
		$password2 = htmlspecialchars($_POST['password2']);

		$message = '<ul>';
		$valide = true;
		$ETAT_VALIDE = 'green';
		$ETAT_INVALIDE = 'red';

		// si username déjà utilisé
		if (Auth::usernameExists($username)){
			$message .= "<li class={$ETAT_INVALIDE}>Le nom d'utilisateur est déjà utilisé</li>";
			$valide = false;
		}
		// si email déjà utilisé
		if (Auth::emailExists($email)){
			$message .= "<li class={$ETAT_INVALIDE}>L'adresse email est déjà utilisée</li>";
			$valide = false;
		}
		// si password1 != password2
		if ($password != $password2){
			$message .= "<li class={$ETAT_INVALIDE}>Les mots de passe ne correspondent pas</li>";
			$valide = false;
		}else {
			$liste = Auth::checkPassword($password);
			if (in_array(false, $liste)) {
				$message .= "<li class={$ETAT_INVALIDE}>Le mot de passe ne respecte au moins 1 champs suivant : <ul>";
				$a = $liste["longueur"] == true ? $ETAT_VALIDE : $ETAT_INVALIDE;
				$message .= "<li class={$a}> minimum 8 caractère</li>";
				$a = $liste["majuscule"] == true ? $ETAT_VALIDE : $ETAT_INVALIDE;
				$message .= "<li class={$a}> au moins 1 majuscule</li>";
				$a = $liste["caractereSpecial"] == true ? $ETAT_VALIDE : $ETAT_INVALIDE;
				$message .= "<li class={$a}> au moins 1 chiffre</li>";
				$a = $liste["chiffre"] == true ? $ETAT_VALIDE : $ETAT_INVALIDE;
				$message .= "<li class={$a}> au moins 1 caractère spéciale</li></ul></li>";
				$valide = false;
			}
		}
		$message = '</ul>';

		if ($valide){
			Auth::register($email, $username, $password);
			Session::loadSession($username);
			header("Location: /");
		}

		return $message;
	}
}