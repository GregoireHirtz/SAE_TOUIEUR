<?php

namespace touiteur\render;

use touiteur\classe\Tag;
use touiteur\classe\Touite;
use touiteur\render\base\Base;
use touiteur\render\base\footer\FooterClassique;
use touiteur\render\base\footer\FooterVide;
use touiteur\render\base\header\action\HeaderActionAbonner;
use touiteur\render\base\header\action\HeaderActionSupprimer;
use touiteur\render\base\header\data\HeaderDataStats;
use touiteur\render\base\header\Header;
use touiteur\render\base\header\image\HeaderImageDefault;
use touiteur\render\base\header\image\HeaderImageHashtag;
use touiteur\render\base\header\nom\HeaderNomPseudo;
use touiteur\render\base\header\nom\HeaderNomTag;
use touiteur\render\base\main\MainSimple;
use touiteur\render\base\main\MainVide;

class BaseFactory
{
	public static function baseHashtag(Touite $touit): Base
	{
		$header = new Header(new HeaderImageDefault(), new HeaderNomPseudo($touit), new HeaderDataStats($touit), new HeaderActionAbonner($touit));
		return new Base($header, new MainVide(), new FooterVide());
	}

	public static function baseTouite(Touite $touit): Base
	{
		$user = $_SESSION['username'] ?? null;
		if ($user == $touit->user)
			$header = new Header(new HeaderImageDefault(), new HeaderNomPseudo($touit), new HeaderDataStats($touit), new HeaderActionSupprimer($touit));
		else
			$header = new Header(new HeaderImageDefault(), new HeaderNomPseudo($touit), new HeaderDataStats($touit), new HeaderActionAbonner($touit));

		global $parts;
		if ($parts[1] == "touit")
			$main = new MainComplet($touit);
		else
			$main = new MainSimple($touit);

		$footer = new FooterClassique($touit);

		return new Base($header, $main, $footer);
	}

	public static function baseProfil(Touite|Tag $element): ?Base
	{
		$main = new MainVide();
		$footer = new FooterVide();

		if ($element instanceof Tag) {
			$headerImage = new HeaderImageHashtag();
			$headerNom = new HeaderNomTag($element);

		} else {
			$headerImage = new HeaderImageDefault();
			$headerNom = new HeaderNomPseudo($element);
		}
		// dateAboUt -> obtenirAbonnementUtilisateur
	}
}