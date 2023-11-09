<?php
declare(strict_types=1);
namespace touiteur\auth;

class Like{


	public static function like(){
		$bd = ConnectionFactory::makeConnection();
		$st = $bd->prepare("CALL etreVote(?, ?)");
		$st->bindParam(1, $_GET["id"], PDO::PARAM_INT);
		$st->bindParam(2, $_SESSION["email"]);
		$st->execute();

		$type = $st->fetch();
		// si pas de vote
		if ($type == false){
			var_dump("LIKE");
		}else{
			// si deja like
			if ($type["vote"]==1){

			}

			// si deja dislike
			else{

			}
		}
	}

}