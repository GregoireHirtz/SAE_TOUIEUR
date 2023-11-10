<?php

namespace touiteur\render\base\header\action;

use touiteur\classe\Tag;
use touiteur\classe\Touite;
use touiteur\classe\User;
use touiteur\db\ConnectionFactory;

class HeaderActionAbonner extends HeaderAction
{
	private User|Tag $element;

	public function __construct(User|Tag $element)
	{
		$this->element = $element;
	}

	function render(): string
	{
		if (!empty($_SESSION)) {
			$db = ConnectionFactory::makeConnection();
			if ($this->element instanceof User) {
				$st = $db->prepare("SELECT etreAboUtilisateur(\"{$_SESSION["username"]}\", \"{$this->element->username}\")");
				$abonne = $this->element->username;
			} else {
				$st = $db->prepare("SELECT etreAboTag(\"{$_SESSION["username"]}\", \"{$this->element->id}\")");
				$abonne = $this->element->id;
			}
			$st->execute();

			$etreAbonne = $st->fetch() != 0;
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
<form class="{$classe}" action="{$p}abonnement?username={$abonne}&redirect={$url_actuel}" method="post">
	{$bouton}
</form>
HTML;
	}
}