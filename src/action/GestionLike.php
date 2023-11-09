<?php
declare(strict_types=1);
namespace touiteur\action;

use PDO;
use touiteur\db\ConnectionFactory;

class GestionLike extends Action{


	static public function execute(?string $username = null){
		$bd = ConnectionFactory::makeConnection();
		$st = $bd->prepare("CALL etreVote(?, ?)");
		$st->bindParam(1, $_GET["id"], PDO::PARAM_INT);
		$st->bindParam(2, $_SESSION["email"]);
		$st->execute();

		$type = $st->fetch();
		// si pas de vote
		if ($type == false){
			var_dump("LIKE");
			$bd = ConnectionFactory::makeConnection();
			$st = $bd->prepare("CALL voter(?, ?, 1)");
			$st->bindParam(1, $_SESSION["email"]);
			$st->bindParam(2, $_GET["id"], PDO::PARAM_INT);
			$st->execute();
		}else{
			// si deja like
			if ($type["vote"]==1){
				var_dump("DEJA LIKE");
			}

			// si deja dislike
			else{
				var_dump("DEJA DISLIKE");
			}
		}
	}
}