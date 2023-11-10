<?php

namespace touiteur\render\base\header\data;

use touiteur\classe\Touite;
use touiteur\classe\User;
use touiteur\db\ConnectionFactory;

class HeaderDataStats extends HeaderData
{
	private User $user;

	public function __construct(Touite|User $element, String $p="")
	{
		$this->prefixe = $p;
		if ($element instanceof User) {
			$this->user = $element;
			return;
		}

		$db = ConnectionFactory::makeConnection();
		$st = $db->prepare("CALL obtenirAuteur(\"{$element->id}\")");
		$st->execute();
		$auteur = $st->fetch()['username'];

		$this->user = User::loadUserFromUsername($auteur);
	}

	function render(): string
	{
		$db = ConnectionFactory::makeConnection();
		$st = $db->prepare("CALL compterStat(\"{$this->user->email}\")");
		$st->execute();
		$abonnement = $st->fetch()[1];
		$abonne = $st->fetch()[1];
		$publication = $st->fetch()[1];

		return "<p>{$abonnement} · {$publication} · {$abonne}</p>";
	}
}