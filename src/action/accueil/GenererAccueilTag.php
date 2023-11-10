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

class GenererAccueilTag extends Action{

	static public function execute(?string $username = null): String{
		$html = "";

		$db = ConnectionFactory::makeConnection();
		$st = $db->prepare("CALL obtenirTouiteTag(\"$username\", ?, ?)");
		$st->bindParam(1, $_GET["page"], PDO::PARAM_INT);
		$ngp = NB_TOUITE_PAGE;
		$st->bindParam(2, $ngp, PDO::PARAM_INT);
		$st->execute();

		foreach ($st->fetchAll() as $touite){
			$touite = new Touite($touite['idTouite'], $touite['texte'], new DateTime($touite['date']), $touite['username'], $touite['notePertinence'], $touite['nbLike'], $touite['nbDislike'], $touite['nbRetouite'], $touite['nbVue'], array());
			$rT = new RenderTouite($touite);
			$html .= $rT->genererTouitSimple();
		}

		return $html;
	}
}