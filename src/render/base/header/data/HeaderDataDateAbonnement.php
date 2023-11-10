<?php

namespace touiteur\render\base\header\data;

use touiteur\classe\Tag;
use touiteur\classe\Touite;
use touiteur\classe\User;
use touiteur\db\ConnectionFactory;

class HeaderDataDateAbonnement extends HeaderData
{
	private User|Tag $element;
	public function __construct(User|Tag $element)
	{
		$this->element = $element;
	}

	function render(): string
	{
		$date = "Vous n'&ecirc;tes pas abonn&eacute;(e)";
		if (isset($_SESSION['username'])) {
			$db = ConnectionFactory::makeConnection();
			$email = $_SESSION['email'];
			if ($this->element instanceof User) {
				$ps = $db->prepare("CALL obtenirDateAbonnementUtilisateur('{$email}', '{$this->element->email}')");
				$ps->execute();
				$row = $ps->fetchAll();
				foreach ($row as $date)
					$date = "Abonn&eacute; depuis le " . $date[0];
			} else {
				$ps = $db->prepare("CALL obtenirDateAbonnementTag('{$email}', '{$this->element->libelle}')");
				$ps->execute();
			}
		} else {
			$date = "Connectez-vous pour voir vos abonnements";
		}
		return "<p>{$date}</p>";
	}
}