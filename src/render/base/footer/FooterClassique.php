<?php

namespace touiteur\render\base\footer;

use touiteur\render\base\Renderable;
use touiteur\classe\Touite;

class FooterClassique implements Renderable
{
	private Touite $touit;

	public function __construct(Touite $touite)
	{
		$this->touit = $touite;
	}

	public function render(): string
	{
		$vote = 0;
		if (isset($_SESSION['username']))
			$vote = $this->touit->getVote($_SESSION['username']);

		$like = $vote == 1 ? "src/vue/images/heart_full.svg" : "src/vue/images/heart_empty.svg";
		$dislike = $vote == -1 ? "src/vue/images/heart-crack_full.svg" : "src/vue/images/heart-crack_empty.svg";

		$url = URL;

		return <<<HTML
<footer>
	<div>
		<form action="like?data=l&id={$this->touit->id}&redirect={$url}" method="post">
			<input name="like" type="image" src={$like} alt="GestionLike">
		</form>
		<p>{$this->touit->notePertinence}</p>
		<form action="like?data=dl&id={$this->touit->id}&redirect={$url}" method="post">
			<input name="dislike" type="image" src={$dislike} alt="GestionLike">
		</form>
	</div>
	<div>
		<p>{$this->touit->nbVue}</p>
		<img src="src/vue/images/view.svg" alt="Vue">
	</div>
</footer>
HTML;

	}
}