<?php

namespace touiteur\action\touit;

use touiteur\action\Action;
use touiteur\db\ConnectionFactory;

class ActionNouveauTouit extends Action{
	static public function execute(?string $username = null)
	{
		$texte = filter_var($_POST['texte'], FILTER_SANITIZE_STRING);

		if (strlen($texte) > 235 || empty($texte))
			return;

		$email = $_SESSION['email'];
		$db = ConnectionFactory::makeConnection();

		// TODO ajouter les tag dans la BD

		// ajoute le texte dans Touit et fait le lien de PublierPar
		$db->prepare("CALL ajoutTouite('{$texte}', '{$email}')")->execute();
	}
}