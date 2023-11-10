<?php

namespace touiteur\render\base\header\nom;

use touiteur\classe\Touite;

class HeaderNomPseudo extends HeaderNom
{
	private Touite $touit;
	public function __construct(Touite $touit)
	{
		$this->touit = $touit;
	}

	function render(): string
	{
		$username = $this->touit->user;
		return "<a href='{$username}' class='pseudo'>{$username}</a>";
	}
}