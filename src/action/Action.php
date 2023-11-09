<?php
declare(strict_types=1);
namespace touiteur\action;

abstract class Action {
    // Paramètre username pour les actions qui en ont besoin (lister les publications d'UN utilisateur par exemple)
	abstract static public function execute(?string $username = null);

}
