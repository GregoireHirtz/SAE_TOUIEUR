<?php
declare(strict_types=1);
namespace touiteur\classe;

use DateTime;
use PDO;
use touiteur\db\ConnectionFactory;

class Touite{

	private int $id;
	private String $texte;
	private DateTime $date;
	private int $notePertinence;
	private int $nbLike;
	private int $nbDislike;
	private int $nbRetouite;
	private int $nbVue;
	private array $listeTag;


	public function __construct($i, $t, $d, $nP=0, $nL=0, $nDL=0, $nR=0, $nV=0, $lT=array()){
		$this->id = $i;
		$this->texte = $t;
		$this->date = $d;
		$this->notePertinence = $nP;
		$this->nbLike = $nL;
		$this->nbDislike = $nDL;
		$this->nbRetouite = $nR;
		$this->nbVue = $nV;
		$this->listeTag = $lT;
	}

	/*
	 * recuperer le touit en bd selon son id
	 */
	public static function loadTouite(int $idTouite): Touite{
		$db = ConnectionFactory::makeConnection();


		$query = "SELECT * from Touite WHERE idTouite = ?";
		$st = $db->prepare($query);
		$st->bindParam(1, $idTouite, PDO::PARAM_STR);
		$st->execute();

		$row = $st->fetch();
		$i =  $row['idTouite'];
		$t = $row['texte'];
		$d = new DateTime($row['date']);
		$nP = $row['notePertinence'];
		$nL = $row['nbLike'];
		$nDL = $row['nbDislike'];
		$nR = $row['nbRetouite'];
		$nV = $row['nbVue'];

		$query = "SELECT * from UtiliserTag WHERE idTouite = ?";
		$st = $db->prepare($query);
		$st->bindParam(1, $idTouite, PDO::PARAM_STR);
		$st->execute();
		$db = null;

		$lT = [];
		while ($row = $st->fetch()){
			$tag = $row['idTag'];
			$lT[] = $tag;
		}
		$touite = new Touite($i, $t, $d, $nP, $nL, $nDL, $nR, $nV, $lT);
		return $touite;
	}


	/**
	 * @return bool true si le touite a été ajouté, false sinon
	 * ajoute un touite dans la base de donnée + mise a jour PublierPar + UtiliserTag
	 */
	public function publierTouite(User $u): bool{

	}

	/**
	 * @return bool
	 */
	public function supprimerTouite(): bool{

	}




	public function getTexte(): String{
		return $this->texte;
	}

	public function getListeTag(): array{
		return $this->listeTag;
	}
}