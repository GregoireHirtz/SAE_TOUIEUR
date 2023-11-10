<?php

namespace touiteur\render\base\header\action;

use touiteur\classe\Touite;
use touiteur\db\ConnectionFactory;

class HeaderActionAbonner extends HeaderAction
{
	private Touite $touit;

	public function __construct(Touite $touit)
	{
		$this->touit = $touit;
	}

	function render(): string
	{
		if (!empty($_SESSION)) {
			$db = ConnectionFactory::makeConnection();
			$st = $db->prepare("CALL verifierUsernameInAbonnement(\"{$_SESSION["username"]}\", \"{$this->touit->user}\")");
			$st->execute();

			$etreAbonne = $st->fetch()['nb_ligne'] != 0;
			$st->closeCursor();
		} else {
			$etreAbonne = false;
		}

		if ($etreAbonne) {
			$bouton = "<input type=\"submit\" value=\"Se désabonner\">";
			$classe = "de sabonner";
		} else {
			$bouton = "<input type=\"submit\" value=\"S'abonner\">";
			$classe = "sabonner";
		}

		$p = PREFIXE;
		$url_actuel = str_replace("/".$p, "", $_SERVER['REQUEST_URI']);

		return <<<HTML
<form class="{$classe}" action="{$p}abonnement?username={$this->touit->user}&redirect={$url_actuel}" method="post">
	{$bouton}
</form>
HTML;
	}
}