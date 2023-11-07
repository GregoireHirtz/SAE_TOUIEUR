<?php

namespace touiteur\action;

use touiteur\auth\Auth;
use touiteur\auth\Session;

class ActionLogin extends Action{

	static public function execute(): string{
		$message = '';

		// filtrage saisie user
		$username = htmlspecialchars($_POST['username']);
		$password = htmlspecialchars($_POST['password']);

		$valide = Auth::authenticate($username, $password);
		// si connexion valide
		if ($valide){
			Session::loadSession($username);
			// redirection vers accueil
			header("Location: /");
		}
		// sinon message erreur dans $message
		else{
			$message = 'Login ou mot de passe incorrect';
		}

		return $message;
	}
}