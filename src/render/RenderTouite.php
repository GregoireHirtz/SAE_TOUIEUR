<?php
declare(strict_types=1);
namespace touiteur\render;

use PDO;
use touiteur\classe\Tag;
use touiteur\classe\Touite;
use touiteur\classe\User;
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

		$idTouite = $this->t->getId();

		$db = ConnectionFactory::makeConnection();
		//$st=$db->prepare("CALL ajouterVue($idTouite)")->execute();

		$header = $this->genererTouitSimpleHeader();
		$main = $this->genererTouitSimpleMain();
		$footer = $this->genererTouitSimpleFooter();

		$html = <<<HTML
	<article id="{$idTouite}">
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
			$bouton = "<input type=\"submit\" value=\"Se désabonner\">";
			$classe = "de sabonner";
		}else{
			$bouton = "<input type=\"submit\" value=\"S'abonner\">";
			$classe = "sabonner";
		}

		$p = PREFIXE;
		$html = <<<HTML
		<header>
			<a href="#" class="photo_profil"><img src="src/vue/images/user.svg" alt="PP"></a>
			<a href="{$username}" class="pseudo">{$username}</a>
			<p>{$dateJ} à {$dateH}</p>
			<form class="{$classe}" action="{$p}abonnement?username={$username}" method="post">
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

		$pertinence = $this->t->getPertinence();
		$vue = $this->t->getNbVue();

		$type = 0;
		if (!empty($_SESSION)){
			$db = ConnectionFactory::makeConnection();
			$i = $this->t->getId();
			$em = User::loadUserFromUsername($_SESSION["username"])->email;
			$st = $db->prepare("CALL etreVote($i, \"$em\")");
			$st->execute();
			$result = $st->fetch(PDO::FETCH_ASSOC);

			if ($result !== false){$type = $result["vote"];}
		}

		$srcL = "src/vue/images/heart_empty.svg";
		$srcDL = "src/vue/images/heart-crack_empty.svg";
		if ($type===1){
			$srcL = "src/vue/images/heart_full.svg";
		}elseif ($type===-1) {
			$srcDL = "src/vue/images/heart-crack_full.svg";
		}


		$like = <<<HTML
				<input name="like" type="image" src={$srcL} alt="GestionLike">
HTML;

		$dislike = <<<HTML
				<input name="dislike" type="image" src={$srcDL} alt="Dislike">
HTML;



		$p = PREFIXE;
		$html = <<<HTML
		 <footer>
			<div>
				<form action="like?data=l&id={$this->t->getId()}" method="post">
					{$like}
				</form>
				<p>{$pertinence}</p>
				<form action="like?data=dl&id={$this->t->getId()}" method="post">
					{$dislike}
				</form>
			</div>
			<div>
				<p>{$vue}</p>
				<img src="src/vue/images/view.svg" alt="Vue">
			</div>
    </footer>
HTML;
		return $html;
	}

}