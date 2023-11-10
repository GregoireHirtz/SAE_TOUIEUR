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
	private string $user;

	public function __get(string $nom): mixed
	{
		return $this->$nom;
	}

	public function __construct(int $i, String $t, DateTime $d, String $u, int $nP=0, int $nL=0, int $nDL=0, int $nR=0, int $nV=0, array $lT=array()){
		$this->id = $i;
		$this->texte = $t;
		$this->date = $d;
		$this->user = $u;
		$this->notePertinence = $nP;
		$this->nbLike = $nL;
		$this->nbDislike = $nDL;
		$this->nbRetouite = $nR;
		$this->nbVue = $nV;
		$this->listeTag = $lT;
	}

	public function getVote(Touite $t, ?string $username = null): int {
		if (!isset($username))
			return 0;
		$user = User::loadUserFromUsername($username)->email;

		$db = ConnectionFactory::makeConnection();
		$st = $db->prepare("SELECT etreVote(?, ?)");
		$st->bindParam(1, $t->id, PDO::PARAM_INT);
		$st->bindParam(2, $user);
		$st->execute();
		$row = $st->fetch();
		return $row[0];
	}
}