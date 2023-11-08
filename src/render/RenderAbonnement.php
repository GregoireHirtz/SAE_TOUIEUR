<?php
declare(strict_types=1);
namespace touiteur\render;

use touiteur\classe\Tag;
use touiteur\classe\User;
use DateTime;

class RenderAbonnement{

    /**
     * @var User l'utilisateur à afficher
     */
    private User $u;
    /**
     * @var DateTime la date d'abonnement
     */
    private DateTime $d;

    /**
     * @param User $user l'utilisateur à afficher
     * @param DateTime $date la date d'abonnement
     */
    public function __construct(User $user, DateTime $date){
        $this->u = $user;
        $this->d = $date;
    }

    /**
     * @return String l'user sous forme html pour la page profil
     * Méthode qui génère l'affichage d'un utilisateur sous forme html pour la page profil dans un article
     */
    public function genererAffichageUser(): String{
        // On génère le header html pour chaque abonnement
        $header = $this->genererUserHeader();
        // On génère le main html pour chaque abonnement
        $main = $this->genererUserMain();
        // On génère le footer html pour chaque abonnement
        $footer = $this->genererUserFooter();

        $html = <<<HTML
	<article>
		{$header}
		{$main}
		{$footer}
	</article>
HTML;
        return $html;
    }
    /**
     * @return String le header de la box user
     * Méthode qui génère le header html de la box user
     */
    private function genererUserHeader(): String{
        $dateAbonnement = date_format($this->d, "d/m/y");
        $heureAbonnement = date_format($this->d, "H:i");
        $html = <<<HTML
		<header>
			<a href="#" class="photo_profil"><img src="src/vue/images/user.svg" alt="Photo Profil"></a>
			<a href="{$this->u->username}" class="pseudo">{$this->u->username}</a>
			<p>Abonné le $dateAbonnement à $heureAbonnement </p>
			<div>
				<button class="sabonner">S'abonner</button>
			</div>
		</header>
HTML;
        return $html;
    }


    /**
     * @return String le main de la box user
     * Méthode qui génère le main html de la box user
     */
    private function genererUserMain(): String{
        return "";
    }

    /**
     * @return String le footer de la box user
     *  Méthode qui génère le footer html de la box user
     */
    private function genererUserFooter(): String{
        return "";
    }

}