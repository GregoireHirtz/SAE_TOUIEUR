<?php

namespace touiteur\render\base\header\image;

class HeaderImageHashtag extends HeaderImage
{
	function render(): string
	{
		return "<a href='#' class='photo_profil'><img src='src/vue/images/hashtag.svg' alt='PP'></a>";
	}
}