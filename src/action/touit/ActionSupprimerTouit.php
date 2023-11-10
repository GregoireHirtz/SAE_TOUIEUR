<?php

namespace touiteur\action\touit;

use touiteur\action\Action;
use touiteur\db\ConnectionFactory;

class ActionSupprimerTouit extends Action
{
	static public function execute(?string $username = null)
	{
		$idTouit = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
		$db = ConnectionFactory::makeConnection();

		$ps = $db->prepare("CALL obtenirUsername({$idTouit})");
		$ps->execute();
		if ($ps->fetch()['username'] != $_SESSION['username'])
			return;
		$ps->closeCursor();

		$db->prepare("CALL supprimerTouite({$idTouit})")->execute();
	}
}