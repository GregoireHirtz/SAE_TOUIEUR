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
		global $parts;
		if ($parts[1] == "tag" || $parts[1] == "touit")
			return "";


		if ($this->element instanceof User)
			$abonne = "username=" . $this->element->username;
		else
			$abonne = "tag=" . $this->element->id;


		if (!empty($_SESSION)) {
			$db = ConnectionFactory::makeConnection();
			if ($this->element instanceof User)
				$st = $db->prepare("SELECT etreAboUtilisateur(\"{$_SESSION["email"]}\", \"{$this->element->email}\")");
			else
				$st = $db->prepare("SELECT etreAboTag(\"{$_SESSION["email"]}\", \"{$this->element->id}\")");

			$st->execute();

			$rows = $st->fetchAll();
			foreach ($rows as $row)
				$etreAbonne = $row[0] == true;

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
<form class="{$classe}" action="abonnement?{$abonne}&redirect={$url_actuel}" method="post">
	{$bouton}
</form>
HTML;
	}
}