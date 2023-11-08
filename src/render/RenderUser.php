<?php
declare(strict_types=1);
namespace touiteur\render;

use touiteur\classe\Tag;
use touiteur\classe\User;

class RenderUser{

    private User $u;

    public function __construct(User $user){
        $this->u = $user;
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

    private function genererUserHeader(): String{;
        $username = $this->u->username;

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



    private function genererUserMain(): String{
        return "";
    }

    private function genererUserFooter(): String{
        return "";
    }

}