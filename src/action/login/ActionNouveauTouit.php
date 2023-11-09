<?php

namespace touiteur\action\login;

use touiteur\action\Action;
use touiteur\auth\Auth;
use touiteur\auth\Session;
use touiteur\db\ConnectionFactory;
use touiteur\dispatch\Dispatcher;

class ActionNouveauTouit extends Action{
	static public function execute(?string $username = null)
	{
		$texte = filter_var($_POST['texte'], FILTER_SANITIZE_STRING);

		if (strlen($texte) > 235)
			return;

		if (empty($texte))
			return;

		$email = $_SESSION['email'];
		$db = ConnectionFactory::makeConnection();

		$db->prepare("CALL ajoutTouite('{$texte}', '{$email}')")->execute();
	}
}