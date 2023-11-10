<?php

namespace touiteur\action\accueil;

use touiteur\action\Action;

class GenererFooter extends Action{

	static public function execute(?string $username = null){
		$p = PREFIXE;
		$url_actuel = str_replace("/".$p, "", $_SERVER['REQUEST_URI']);
		return <<<HTML
<dialog>
   <form action="publier" method="post" enctype="multipart/form-data">
        <header>
            <a href="#" class="photo_profil"><img src="src/vue/images/hashtag.svg" alt="Photo Profil"></a>
            <a href="#" class="pseudo">Votre touit</a>
            <p>Publication en cours de cr&eacute;ation</p>

        </header>
        <div class="texte_touit">
            <fieldset>
                <legend>Entrer votre texte</legend>
                <textarea name="texte" oninput="textAreaAdjust(this)" maxlength="235" id="publier_touit" required></textarea>
            </fieldset>
            <label class="compteur" for="publier_touit">xxx/235</label>
        </div>
        <input name="image" id="input_file" hidden type="file" placeholder="Ajouter une image">
        <label for="input_file">Ajouter une image</label>
        <input name="redirect" hidden value="{$url_actuel}">
        <div class="inline">
            <input type="submit" class="bouton" value="Publier">
            <input type="reset" class="bouton secondaire" value="Annuler" onclick="this.closest('dialog').close()">
        </div>
    </form>
</dialog>
<button id="openDialog" onclick="openDialog()" title="Cr&eacute;er un Touit"></button>
HTML;
	}
}