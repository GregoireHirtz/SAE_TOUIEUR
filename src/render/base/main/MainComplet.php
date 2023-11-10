<?php

namespace touiteur\render\base\main;

use PDO;
use touiteur\db\ConnectionFactory;

class MainComplet extends Main
{

	function render(): string{
		global $parts;
		$img = "";
		$bd = ConnectionFactory::makeConnection();
		$st = $bd->prepare("CALL obtenirImage(?)");
		$st->bindParam(1, $parts[2], PDO::PARAM_INT);
		$st->execute();

		$row = $st->fetch();
		if (!empty($row)){
			$nom = $row[1];
			$nom = explode('/', $nom);
			$img = "<img src='../src/action/touit/img/{$nom[2]}' alt='image'>";
		}

		return <<<HTML
		<main>
			<p>{$this->texteConverter()}</p>
			{$img}
		</main>	
HTML;
	}
}