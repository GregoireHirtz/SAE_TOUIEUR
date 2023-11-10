<?php
declare(strict_types=1);
namespace touiteur\render;

use PDO;
use touiteur\classe\Tag;
use touiteur\classe\Touite;
use touiteur\classe\User;
use touiteur\db\ConnectionFactory;
use touiteur\render\base\Base;
use touiteur\render\base\footer\FooterClassique;
use touiteur\render\base\header\action\HeaderActionAbonner;
use touiteur\render\base\header\action\HeaderActionSupprimer;
use touiteur\render\base\header\data\HeaderDataDate;
use touiteur\render\base\header\data\HeaderDataStats;
use touiteur\render\base\header\Header;
use touiteur\render\base\header\image\HeaderImageDefault;
use touiteur\render\base\header\nom\HeaderNomTitre;
use touiteur\render\base\main\MainComplet;
use touiteur\render\base\main\MainSimple;

class RenderTouite{

	private Touite $t;



	public function __construct(Touite $touite){
		$this->t = $touite;
	}

	/**
	 * @return String le touit sous forme html pour accueil
	 */
	public function genererTouitSimple(): String{

		//ajouter une vue au touite
		$bd = ConnectionFactory::makeConnection();
		$st = $bd->prepare("CALL ajouterVue(?)");
		$id = $this->t->id;
		$st->bindParam(1, $id, PDO::PARAM_INT);
		$st->execute();


		return BaseFactory::baseTouite($this->t)->render();
	}

	/**
	 * @return String le touit sous forme html pour accueil
	 */
	public function genererTouitComplet(): String{
		return BaseFactory::baseTouite($this->t)->render();
	}

}