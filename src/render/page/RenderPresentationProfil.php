<?php

namespace touiteur\render\page;

use touiteur\classe\User;
use touiteur\render\BaseFactory;
use touiteur\render\Renderable;

class RenderPresentationProfil implements Renderable
{

	function render(): string
	{
		global $parts;
		$username = $parts[1];

		$user = User::loadUserFromUsername($username);

		return BaseFactory::baseProfil($user, "data")->render();
	}
}