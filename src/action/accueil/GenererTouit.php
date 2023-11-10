<?php

namespace touiteur\action\accueil;

use Cassandra\Function_;
use DateTime;
use PDO;
use touiteur\action\Action;
use touiteur\classe\Tag;
use touiteur\classe\Touite;
use touiteur\db\ConnectionFactory;
use touiteur\render\RenderTouite;

class GenererTouit extends Action{

	static public function execute(?string $username = null): String{
		$html = "";
		global $parts;
		$idTouite = $parts[2];

		// generer Touit a afficher
		$db = ConnectionFactory::makeConnection();
		$st = $db->prepare("CALL obtenirTouit(?)");
		$st->bindParam(1, $idTouite, PDO::PARAM_INT);
		$st->execute();
		$touite = $st->fetch();
		$touite = new Touite($touite['idTouite'], $touite['texte'], new DateTime($touite['date']), $touite['username'], $touite['notePertinence'], $touite['nbLike'], $touite['nbDislike'], $touite['nbRetouite'], $touite['nbVue'], array());

		$rT = new RenderTouite($touite, "../");
		$html .= $rT->genererTouitComplet();

		return $html;
	}
}