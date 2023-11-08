<?php
declare(strict_types=1);
namespace touiteur\render;

use touiteur\classe\Tag;
use touiteur\classe\Touite;

class RenderTouite{

	private Touite $t;

	public function __construct(Touite $touite){
		$this->t = $touite;
	}

	/**
	 * @return String le touit sous forme html pour accueil
	 *
	 * $type =
	 * 1. touit simple (avec boutona abonnement/desabonnement ou si touit perso supprimer)
	 * 2. touit detaille (avec boutona abonnement/desabonnement ou si touit perso supprimer)
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
		$username = "aaa";

		$html = <<<HTML
		<header>
			<a href="#" class="photo_profil"><img src="src/vue/images/user.svg" alt="PP"></a>
			<a href="#" class="pseudo">{$username}</a>
			<p>JJ-MM-AAAA à hh:mm</p>
			<div>
				<button class="sabonner">S'abonner</button>
			</div>
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
		foreach ($lT as $idTag){
			$libelle = Tag::loadTag($idTag)->getLibelle();
			$tags .= "<a href=\"#\">#{$libelle}</a> ";
		}


		$html = <<<HTML
		 <footer>
			<div>
				<img src="src/vue/images/heart_empty.svg" alt="Like">
				<p>84</p>
				<img src="src/vue/images/heart-crack_empty.svg" alt="Dislike">
			</div>
			<div>
				<p>0</p>
				<img src="src/vue/images/view.svg" alt="Vue">
			</div>
			<p>{$tags}</p>
    </footer>
HTML;
		return $html;
	}

}