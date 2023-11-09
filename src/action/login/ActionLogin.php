<?php
declare(strict_types=1);
namespace touiteur\action\login;

use touiteur\action\Action;
use touiteur\auth\Auth;
use touiteur\auth\Session;
use touiteur\dispatch\Dispatcher;

class ActionLogin extends Action{

	static public function execute(?string $username = null): string{
		$message = '';

		// filtrage saisie user
		$username = htmlspecialchars($_POST['username']);
		$password = htmlspecialchars($_POST['password']);

		$valide = Auth::authenticate($username, $password);
		// si connexion valide
		if ($valide){
			Session::loadSession($username);
			// redirection vers accueil
			//header("Location: /");
			Dispatcher::redirection("");
		}
		// sinon message erreur dans $message
		else{
			$message = '<p>Login ou mot de passe incorrect</p>';
		}

		return $message;
	}
}