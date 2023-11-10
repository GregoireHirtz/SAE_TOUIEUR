<?php

namespace touiteur\render\base\main;

class MainComplet extends Main
{

	function render(): string
	{
		return <<<HTML
		<main>
			<p>{$this->texteConverter()}</p>
			<a href="touit/{$this->touit->id}">Afficher plus...</a>
		</main>	
HTML;
	}
}