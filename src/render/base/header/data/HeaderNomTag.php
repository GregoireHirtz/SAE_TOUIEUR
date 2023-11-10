<?php

namespace touiteur\render\base\header\data;

use touiteur\classe\Tag;
use touiteur\render\base\header\nom\HeaderNom;

class HeaderNomTag extends HeaderNom
{
	private Tag $tag;

	function render(): string
	{
		$tag = $this->tag->getLibelle();
		return "<a href='{$tag}' class='pseudo'>{$tag}</a>";

	}
}