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
	private String $user;


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

	public function getUsername(): String{
		return $this->user;
	}

	public function getDate(): DateTime{
		return $this->date;
	}

	public function getPertinence(): int{
		return $this->notePertinence;
	}

	public function getNbVue(): int{
		return $this->nbVue;
	}

	public function getId(): int{
		return $this->id;
	}
}