<?php

namespace touiteur\classe;

use DateTime;
use PDO;
use touiteur\db\ConnectionFactory;

class Tag{
	private int $id;
	private String $libelle;
	private String $description;

	public function __construct($i, $l, $d){
		$this->id = $i;
		$this->libelle = $l;
		$this->description = $d;
	}

	/**
	 * @param $id
	 * @return Tag
	 * load un tag depusi la bd selon id
	 */
	public static function loadTag($id): Tag{
		$db = ConnectionFactory::makeConnection();


		$query = "SELECT * from Tag WHERE idTag = ?";
		$st = $db->prepare($query);
		$st->bindParam(1, $id, PDO::PARAM_STR);
		$st->execute();

		$row = $st->fetch();
		$i =  $row['idTag'];
		$l = $row['libelle'];
		$d = $row['descriptionTag'];
		$dC = new DateTime($row['dateCreation']);

		$tag = new Tag($i, $l, $d, $dC);
		return $tag;
	}

	public function __get(string $name)
	{
		return $this->$name;
	}

}