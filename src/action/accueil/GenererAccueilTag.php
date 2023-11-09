<?php

namespace touiteur\action\accueil;

use Cassandra\Function_;
use DateTime;
use touiteur\action\Action;
use touiteur\classe\Tag;
use touiteur\classe\Touite;
use touiteur\db\ConnectionFactory;
use touiteur\render\RenderTouite;

class GenererAccueilTag extends Action{

	static public function execute(?string $username = null): String{
		$html = "";

		$db = ConnectionFactory::makeConnection();
		$st = $db->prepare("CALL obtenirTouiteTag(\"$username\", 1, 20)");
		$st->execute();

		foreach ($st->fetchAll() as $touite){
			$touite = new Touite($touite['idTouite'], $touite['texte'], new DateTime($touite['date']), $touite['username'], $touite['notePertinence'], $touite['nbLike'], $touite['nbDislike'], $touite['nbRetouite'], $touite['nbVue'], array());
			$rT = new RenderTouite($touite);
			$html .= $rT->genererTouitSimple();
		}

		return $html;
	}
}