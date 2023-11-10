<?php

namespace touiteur\render\base\footer;

use touiteur\classe\Touite;

class FooterClassique extends Footer
{
	private Touite $touit;

	public function __construct(Touite $touite, String $p="")
	{
		$this->touit = $touite;
		$this->prefixe = $p;
	}

	public function render(): string
	{
		$vote = 0;
		if (isset($_SESSION['username']))
			$vote = $this->touit->getVote($this->touit, $_SESSION['username']);

		$like = $vote == 1 ? "{$this->prefixe}src/vue/images/heart_full.svg" : "{$this->prefixe}src/vue/images/heart_empty.svg";
		$dislike = $vote == -1 ? "{$this->prefixe}src/vue/images/heart-crack_full.svg" : "{$this->prefixe}src/vue/images/heart-crack_empty.svg";

		$url = URL;

		return <<<HTML
<footer>
	<div>
		<form action="{$this->prefixe}like?data=l&id={$this->touit->id}&redirect={$url}" method="post">
			<input name="like" type="image" src={$like} alt="GestionLike">
		</form>
		<p>{$this->touit->notePertinence}</p>
		<form action="{$this->prefixe}like?data=dl&id={$this->touit->id}&redirect={$url}" method="post">
			<input name="dislike" type="image" src={$dislike} alt="GestionLike">
		</form>
	</div>
	<div>
		<p>{$this->touit->nbVue}</p>
		<img src="{$this->prefixe}src/vue/images/view.svg" alt="Vue">
	</div>
</footer>
HTML;

	}
}