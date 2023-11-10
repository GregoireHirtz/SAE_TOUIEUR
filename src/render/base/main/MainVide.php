<?php

namespace touiteur\render\base\main;

use touiteur\classe\Touite;

class MainVide extends Main
{
	public function __construct()
	{
		parent::__construct();
	}

	function render(): string
	{
		return "";
	}
}