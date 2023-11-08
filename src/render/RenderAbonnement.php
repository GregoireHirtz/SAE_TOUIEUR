<?php
declare(strict_types=1);
namespace touiteur\render;

use touiteur\classe\Tag;
use touiteur\classe\User;
use DateTime;

class RenderAbonnement{

    private User $u;
    private DateTime $d;

    public function __construct(User $user, DateTime $date){
        $this->u = $user;
        $this->d = $date;
    }

    /**
     * @return String l'user sous forme html pour la page profil
     *
     */
    public function genererAffichageUser(): String{
        $header = $this->genererUserHeader();
        $main = $this->genererUserMain();
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
     */
    private function genererUserHeader(): String{
        $dateAbonnement = date_format($this->d, "d/m/y");
        $heureAbonnement = date_format($this->d, "H:i");
        $html = <<<HTML
		<header>
			<a href="#" class="photo_profil"><img src="src/vue/images/user.svg" alt="Photo Profil"></a>
			<a href="#" class="pseudo">{$this->u->username}</a>
			<p>Abonné le $dateAbonnement à $heureAbonnement </p>
			<div>
				<button class="sabonner">S'abonner</button>
			</div>
		</header>
HTML;
        return $html;
    }



    private function genererUserMain(): String{
        return "";
    }

    private function genererUserFooter(): String{
        return "";
    }

}