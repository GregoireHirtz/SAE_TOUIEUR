<?php

namespace touiteur\render\base\header\data;

use touiteur\classe\Touite;
use touiteur\classe\User;
use touiteur\db\ConnectionFactory;

class HeaderDataStats extends HeaderData
{
	private User $user;

	public function __construct(Touite $touit)
	{
		$db = ConnectionFactory::makeConnection();
		$st = $db->prepare("CALL obtenirAuteur(\"{$touit->id}\")");
		$st->execute();
		$auteur = $st->fetch()['username'];

		$this->user = User::loadUserFromUsername($auteur);
	}

	function render(): string
	{
		// TODO implementer l'affichage en stat (abbonement · vue · abonnes)
		$db = ConnectionFactory::makeConnection();
		$st = $db->prepare("CALL compterStat(\"{$this->user->email}\")");
		$st->execute();
		$abonnement = $st->fetch()[1];
		$abonne = $st->fetch()[1];
		$publication = $st->fetch()[1];

		return "<p>{$abonnement} · {$publication} · {$abonne}</p>";
	}
}