<?php

namespace touiteur\render\base\header;

use touiteur\render\Renderable;
use touiteur\classe\Touite;
use touiteur\render\base\header\action\HeaderAction;
use touiteur\render\base\header\data\HeaderData;
use touiteur\render\base\header\image\HeaderImage;
use touiteur\render\base\header\nom\HeaderNom;

class Header implements Renderable
{
	protected Touite $touit;
	private HeaderImage $image;
	private HeaderNom $nom;
	private HeaderData $data;
	private HeaderAction $action;

	public function __construct(HeaderImage $image, HeaderNom $nom, HeaderData $data, HeaderAction $action)
	{
		$this->image = $image;
		$this->nom = $nom;
		$this->data = $data;
		$this->action = $action;
	}

	function render(): string
	{
		return <<<HTML
<header>
	{$this->image->render()}
	{$this->nom->render()}
	{$this->data->render()}
	{$this->action->render()}
</header>
HTML;

	}
}