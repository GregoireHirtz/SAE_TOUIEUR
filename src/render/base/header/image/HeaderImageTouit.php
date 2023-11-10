<?php

namespace touiteur\render\base\header\image;

class HeaderImageTouit extends HeaderImage{

	public function __construct(String $p=""){
		$this->prefixe = $p;
	}

	function render(): string{
		return "<a href='#' class='picture'><img src='{$this->prefixe}src/vue/images/user.svg' alt='PP'></a>";
	}
}