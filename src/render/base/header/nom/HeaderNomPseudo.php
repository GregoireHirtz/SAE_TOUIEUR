<?php

namespace touiteur\render\base\header\nom;

use touiteur\classe\Touite;

class HeaderNomPseudo extends HeaderNom
{
	private Touite $touit;
	public function __construct(Touite $touit, String $p="")
	{
		$this->touit = $touit;
		$this->prefixe = $p;
	}

	function render(): string
	{
		$username = $this->touit->user;
		return "<a href='{$this->prefixe}{$username}' class='pseudo'>{$username}</a>";
	}
}