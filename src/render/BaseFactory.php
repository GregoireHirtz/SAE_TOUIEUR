<?php

namespace touiteur\render;

use touiteur\classe\User;
use touiteur\render\base\header\data\HeaderDataDateAbonnement;
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
use touiteur\render\base\header\nom\HeaderNomTitre;
use touiteur\render\base\main\MainComplet;
use touiteur\render\base\main\MainSimple;
use touiteur\render\base\main\MainVide;

class BaseFactory
{
	public static function baseHashtag(Touite $touit): Base
	{
		$header = new Header(new HeaderImageDefault(), new HeaderNomTitre($touit), new HeaderDataStats($touit), new HeaderActionAbonner($touit));
		return new Base($header, new MainVide(), new FooterVide());
	}

	public static function baseTouite(Touite $touit): Base
	{

		global $parts;
		if ($parts[1] == "touit") {
			$main = new MainComplet($touit);
			$footer = new FooterClassique($touit, "../");
			$headerImage = new HeaderImageDefault("../");
		}
		else{
			$main = new MainSimple($touit);
			$footer = new FooterClassique($touit);
			$headerImage = new HeaderImageDefault();
		}

		$user = $_SESSION['username'] ?? null;
		$username = User::loadUserFromUsername($touit->user);
		if ($user == $touit->user)
			$header = new Header($headerImage, new HeaderNomTitre($touit), new HeaderDataStats($touit), new HeaderActionSupprimer($touit));
		else
			$header = new Header($headerImage, new HeaderNomTitre($touit), new HeaderDataStats($touit), new HeaderActionAbonner($username));

		return new Base($header, $main, $footer);
	}

	public static function baseProfil(User|Tag $element): ?Base
	{
		$main = new MainVide();
		$footer = new FooterVide();

		$headerAction = new HeaderActionAbonner($element);
		if ($element instanceof Tag) {
            global $parts;
            if ($parts[1] == "tag")
			    $headerImage = new HeaderImageHashtag("../");
			else
				$headerImage = new HeaderImageHashtag();
		} else {
			$headerImage = new HeaderImageDefault();
		}

		$headerData = new HeaderDataDateAbonnement($element);
		$headerNom = new HeaderNomTitre($element);

		$header = new Header($headerImage, $headerNom, $headerData, $headerAction);

		return new Base($header, $main, $footer);
	}
}