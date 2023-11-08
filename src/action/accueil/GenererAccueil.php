<?php

namespace touiteur\action\accueil;

use Cassandra\Function_;
use touiteur\action\Action;
use touiteur\classe\Touite;
use touiteur\db\ConnectionFactory;
use touiteur\render\RenderTouite;

class GenererAccueil extends Action{

	static public function execute(): String{
		$html = "";
		$liste = self::listeTouitAll();

		foreach ($liste as $touite){
			$rT = new RenderTouite($touite);
			$html .= $rT->genererTouitSimple();
		}
		return $html;
	}



	static private function listeTouitAll(): array{
		$liste = [];

		$db = ConnectionFactory::makeConnection();

		// Utilisation de LIMIT dans la requête pour éviter de parcourir complètement la table si on a déjà trouvé le mail de l'utilisateur.
		$query = "SELECT idTouite from Touite ORDER BY notePertinence DESC LIMIT 20";
		$st = $db->prepare($query);
		$st->execute();
		$db = null;

		while ($row = $st->fetch()){
			$id =  $row['idTouite'];
			$t = Touite::loadTouite($id);
			$liste[] = $t;
		}
		return $liste;
	}
}