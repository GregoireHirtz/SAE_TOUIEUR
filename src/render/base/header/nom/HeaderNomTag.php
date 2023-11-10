<?php

namespace touiteur\render\base\header\nom;

use touiteur\classe\Tag;

class HeaderNomTag extends HeaderNom
{
	private Tag $tag;

	public function __construct(Tag $tag)
	{
		$this->tag = $tag;
	}

	function render(): string
	{
		$tag = $this->tag->getLibelle();
		return "<a href='{$tag}' class='pseudo'>{$tag}</a>";

	}
}