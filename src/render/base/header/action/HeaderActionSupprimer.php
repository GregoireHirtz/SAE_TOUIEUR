<?php

namespace touiteur\render\base\header\action;

use touiteur\classe\Touite;

class HeaderActionSupprimer extends HeaderAction
{
	private Touite $touit;
	public function __construct(Touite $touit)
	{
		$this->touit = $touit;
	}

	function render(): string
	{
		$redirection = URL;
		return <<<HTML
<form class="delete" action="supprimer?id={$this->touit->id}&redirect={$redirection}" method="post">
	<input type="submit" value="Supprimer">
</form>
HTML;

	}
}