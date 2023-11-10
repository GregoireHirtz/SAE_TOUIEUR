<?php

namespace touiteur\render\base\header\data;


use touiteur\classe\Touite;

class HeaderDataDate extends HeaderData
{
	private Touite $touit;

	public function __construct(Touite $touit, String $p="")
	{
		$this->touit = $touit;
		$this->prefixe = $p;
	}

	function render(): string
	{
		$date = $this->touit->date;
		$dateJ = $date->format('d-m-Y');
		$dateH = $date->format('H:i');
		return "<p>{$dateJ} &agrave; {$dateH}</p>";
	}
}