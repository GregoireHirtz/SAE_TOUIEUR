<?php

namespace touiteur\action\accueil;

use touiteur\action\Action;

class GenererHeader extends Action{

	static public function execute(?string $username = null){

		// si pas de session alors afficher "Se connecter" et action du bouton => login
		$username = "Se connecter";
		$url = "login";
		if (!empty($_SESSION)){
			$username = $_SESSION['username'];
			$url = $username;
		}

		$p = PREFIXE;
		$html = <<<HTML
	<nav class="barreNav">
		<a href="/{$p}" class="logo">Touiter</a>
		<a href="/{$p}{$url}" class="compte">
			{$username}
			<img src="src/vue/images/user.svg" alt="PP">
		</a>
</nav>
HTML;


		return $html;
	}
}