<?php

namespace touiteur\action\accueil;

use Cassandra\Function_;
use DateTime;
use touiteur\action\Action;
use touiteur\classe\Tag;
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
		$st = $db->prepare('CALL obtenirMeilleursTouites()');
		$st->execute();

		$row = $st->fetchAll();
		foreach ($row as $r){
			$i = $r['idTouite'];
			$t = $r['texte'];
			$d = new DateTime($r['date']);
			$nP = $r['notePertinence'];
			$nL = $r['nbLike'];
			$nDL = $r['nbDislike'];
			$nR = $r['nbRetouite'];
			$nV = $r['nbVue'];

			$db = ConnectionFactory::makeConnection();
			$st = $db->prepare("CALL afficherTouiteTags({$i})");
			$st->execute();

			$lT = [];
			$row2 = $st->fetchAll();
			foreach ($row2 as $r2) {
				$lT[] = $r2['libelle'];
			}

			$db = ConnectionFactory::makeConnection();
			$st = $db->prepare("CALL obtenirUsername({$i})");
			$st->execute();

			$row = $st->fetch();
			$u = $row['username'];

			$liste[] = new Touite($i, $t, $d, $u, $nP, $nL, $nDL, $nR, $nV, $lT);
		}


		return $liste;
	}
}