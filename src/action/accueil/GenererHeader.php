<?php

namespace touiteur\action\accueil;

use touiteur\action\Action;

class GenererHeader extends Action{

	static public function execute(){
		$username = "Se connecter";
		$url = "login";
		if (!empty($_SESSION)){
			$username = $_SESSION['username'];
			$url = $username;
		}

		$html = <<<HTML
	<nav class="barreNav">
		<a href="/" class="logo">Touiter</a>
		<a href="/{$url}" class="compte">
			{$username}
			<img src="src/vue/images/user.svg" alt="PP">
		</a>
</nav>
HTML;


		return $html;
	}
}