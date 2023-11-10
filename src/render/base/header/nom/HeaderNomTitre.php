<?php

namespace touiteur\render\base\header\nom;

use touiteur\classe\Tag;
use touiteur\classe\Touite;
use touiteur\classe\User;

class HeaderNomTitre extends HeaderNom
{
	private Touite|Tag|User $element;
	public function __construct(Touite|Tag|User $element, String $p="")
	{
		$this->element = $element;
		$this->prefixe = $p;
	}

	function render(): string
	{
		if ($this->element instanceof Tag)
			$titre = $this->element->libelle;
		else if ($this->element instanceof User)
			$titre = $this->element->username;
		else
			$titre = $this->element->user;
		return "<a href='{$this->prefixe}{$titre}' class='pseudo'>{$titre}</a>";
	}
}