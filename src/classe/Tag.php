<?php

namespace touiteur\classe;

class Tag{
	private $id;
	private $libelle;
	private $description;

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
	public function loadTag($id): Tag{
		// TODO
	}

	public function getLibelle(): String{
		return $this->libelle;
	}

}