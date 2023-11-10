<?php

namespace touiteur\render\base\header\action;

class HeaderActionPublier extends HeaderAction
{
	function render(): string
	{
		return <<<HTML
<form class="publier">
	<button onclick="openDialog()" title="Cr&eacute;er un Touit"></button>
</form>
HTML;
	}
}