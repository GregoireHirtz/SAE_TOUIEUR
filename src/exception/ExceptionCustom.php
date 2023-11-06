<?php
declare(strict_types=1);
namespace touiteur\exception;

use Exception;

class InvalideTypePage extends Exception{
    public function __construct(string $typePage){
        parent::__construct("Type de page invalide : $typePage");
    }
}
