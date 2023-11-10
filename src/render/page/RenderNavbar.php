<?php

namespace render\page;

use render\Renderable;

class RenderNavbar implements Renderable
{
	function render(): string
	{
		if (empty($_SESSION)) {
			$username = "Se connecter";
			$url = "login";
		} else {
			$username = $_SESSION['username'];
			$url = $username;
		}

		global $parts;
		$p = PREFIXE;

		// change la zone si dans un sous dossier
		$level = "";
		if ($parts[1] == "touit" || $parts[1] == "tag")
			$level = "../";

		$html = <<<HTML
<nav class="barreNav">
	<a href="/{$p}" class="logo">Touiteur</a>
	<a href="/{$p}{$url}" class="compte">
		{$username}
		<img src="{$level}src/vue/images/user.svg" alt="PP">
	</a>
</nav>
HTML;


		return $html;
	}
}