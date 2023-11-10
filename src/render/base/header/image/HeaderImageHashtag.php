<?php

namespace touiteur\render\base\header\image;

class HeaderImageHashtag extends HeaderImage
{
	public function __construct(String $p=""){
		$this->prefixe = $p;
	}

	function render(): string
	{
		return "<a href='#' class='photo_profil'><img src='{$this->prefixe}src/vue/images/hashtag.svg' alt='PP'></a>";
	}
}