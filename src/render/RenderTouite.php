<?php
declare(strict_types=1);
namespace touiteur\render;

use touiteur\classe\Tag;
use touiteur\classe\Touite;
use touiteur\db\ConnectionFactory;

class RenderTouite{

	private Touite $t;

	public function __construct(Touite $touite){
		$this->t = $touite;
	}

	/**
	 * @return String le touit sous forme html pour accueil
	 */
	public function genererTouitSimple(): String{
		$header = $this->genererTouitSimpleHeader();
		$main = $this->genererTouitSimpleMain();
		$footer = $this->genererTouitSimpleFooter();

		$html = <<<HTML
	<article>
		{$header}
		{$main}
		{$footer}
	</article>
HTML;
		return $html;
	}

	private function genererTouitSimpleHeader(): String{
		$username = $this->t->getUsername();

		$date = $this->t->getDate();
		$dateJ = $date->format('d-m-Y');
		$dateH = $date->format('H:i');

		$etreAbonne = false;
		// SI UTILISATEUR LOGGER
		if (!empty($_SESSION)){
			// VERIFICATION SI ABONNE A L'AUTEUR DU TOUITE
			$db = ConnectionFactory::makeConnection();
			$nb_ligne = 0;
			$st = $db->prepare("CALL verifierUsernameInAbonnement(\"{$_SESSION["username"]}\", \"{$username}\")");
			$st->execute();
			if ($st->fetch()['nb_ligne'] != 0){
				$etreAbonne = true;
			}
		}

		if ($etreAbonne){
			$bouton = "<input class=\"bouton\" type=\"submit\" value=\"Se désabonner\">";
		}else{
			$bouton = "<input class=\"bouton\" type=\"submit\" value=\"S\'abonner\">";
		}

		$html = <<<HTML
		<header>
			<a href="#" class="photo_profil"><img src="src/vue/images/user.svg" alt="PP"></a>
			<a href="{$username}" class="pseudo">{$username}</a>
			<p>{$dateJ} à {$dateH}</p>
			<form action="abonnement?username={$username}" method="post">
				{$bouton}
			</form>
		</header>
HTML;
		return $html;
	}



	private function genererTouitSimpleMain(): String{
		$m = $this->t->getTexte();
		$html = <<<HTML
		 <main>
			<p>{$m}</p>
		</main>	
HTML;
		return $html;
	}

	private function genererTouitSimpleFooter(): String{
		$m = $this->t->getTexte();

		$lT = $this->t->getListeTag();
		$tags = "";
		foreach ($lT as $tag){
			$tags .= "<a href=\"#\">#{$tag} </a>";
		}

		$pertinence = $this->t->getPertinence();

		$vue = $this->t->getNbVue();

		$html = <<<HTML
		 <footer>
			<div>
				<img src="src/vue/images/heart_empty.svg" alt="Like">
				<p>{$pertinence}</p>
				<img src="src/vue/images/heart-crack_empty.svg" alt="Dislike">
			</div>
			<div>
				<p>{$vue}</p>
				<img src="src/vue/images/view.svg" alt="Vue">
			</div>
			<p>{$tags}</p>
    </footer>
HTML;
		return $html;
	}

}