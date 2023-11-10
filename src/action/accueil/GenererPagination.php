<?php

namespace touiteur\action\accueil;

use PDO;
use touiteur\action\Action;
use touiteur\db\ConnectionFactory;

class GenererPagination extends Action{

	static public function execute(?string $username = null): String{
		$p = PREFIXE;
		$url_actuel = str_replace("/".$p, "", $_SERVER['REQUEST_URI']);

		$db = ConnectionFactory::makeConnection();
		$st = $db->prepare("SELECT obtenirNbPagesTouiteGénérale(?)");
		$nb = NB_TOUITE_PAGE;
		$st->bindParam(1, $nb, PDO::PARAM_INT);
		$st->execute();
		$row = $st->fetch()[0];

		$html = "<ul id='pagination'>";
		for ($i=1; $i<=$row; $i++){
			$html .= "<li><a href=\"accueil?page={$i}\">{$i}</a></li>";
		}
		$html .= "</ul>";
		return $html;
	}
}