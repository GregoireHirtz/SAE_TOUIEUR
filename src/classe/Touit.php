<?php
declare(strict_types=1);
namespace touiteur\classe;

class Touit{

	private $id;
	private $texte;
	private $date;
	private $notePertinence;
	private $nbLike;
	private $nbDislike;
	private $nbRetouite;
	private $nbVue;
	private $listeTag;


	public function __construct__($i, $t, $d, $nP=0, $nL=0, $nDL=0, $nR=0, $nV=0, $lT=array()){
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
	public static function loadTouite(int $idTouite): Touit{

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


	/**
	 * @return String le touit sous forme html pour accueil
	 *
	 * $type =
	 * 1. touit simple (avec boutona abonnement/desabonnement ou si touit perso supprimer)
	 * 2. touit detaille (avec boutona abonnement/desabonnement ou si touit perso supprimer)
	 */
	public function genererTouitSimple(int $type): String{

	}
}