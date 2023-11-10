<?php

namespace touiteur\action\touit;

use PDO;
use touiteur\action\Action;
use touiteur\db\ConnectionFactory;

class ActionNouveauTouit extends Action{
	static public function execute(?string $username = null){


		// TRAITEMEENT IMAGE
		$upload_dir = __DIR__ . '/img/';
		$filename = uniqid();

		$tmp = $_FILES['image']['tmp_name'] ;
		$image =  false;
		if (($_FILES['image']['error'] === UPLOAD_ERR_OK) &&
			($_FILES['image']['type'] === 'image/png') ) {
			$dest = $upload_dir.$filename.'.png';
			if (move_uploaded_file($tmp, $dest )) {
				$image=true;
			}
		}


		$texte = filter_var($_POST['texte'], FILTER_SANITIZE_STRING);

		if (strlen($texte) > 235 || empty($texte))
			return;

		$email = $_SESSION['email'];
		$db = ConnectionFactory::makeConnection();

		// ajoute le texte dans Touit et fait le lien de PublierPar
		$st = $db->prepare("SELECT  ajoutTouite('{$texte}', '{$email}')");
		$st->execute();
		$idTouite = $st->fetch()[0];

		if ($image) {
			//ajout image en bd
			$db = ConnectionFactory::makeConnection();
			$st = $db->prepare("SELECT ajoutImage(?, ?)");
			$description = "";
			$st->bindParam(1, $description);
			$st->bindParam(2, $dest);
			$st->execute();
			$idImage= $st->fetch()[0];



			// liaison image + touite
			$db = ConnectionFactory::makeConnection();
			$st = $db->prepare("CALL ajoutUtiliserImage(?, ?)");
			$st->bindParam(1, $idTouite, PDO::PARAM_INT);
			$st->bindParam(2, $idImage, PDO::PARAM_INT);
			$st->execute();
		}
	}
}