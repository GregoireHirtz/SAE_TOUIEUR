<?php

namespace touiteur\render\base\main;

use touiteur\render\base\Renderable;
use touiteur\classe\Touite;

abstract class Main implements Renderable
{
	protected Touite $touit;

	public function __construct(?Touite $touit = null)
	{
		$this->touit = $touit;
	}

	protected function texteConverter(): String {
		$texte = $this->touit->texte;
		return preg_replace("/#([a-zA-Z0-9]+)/", "<a href=\"tag/$1\">#$1</a>", $texte);
	}
}