<?php
declare(strict_types=1);
namespace touiteur\auth;


use touiteur\db\ConnectionFactory;
use PDO;
use touiteur\exception\SQLError;


class Auth{

	/**
	 * @param string $email
	 * @param string $password
	 * @return bool
	 * true = email et password valide selon la donne bd    false = sinon
	 */
    public static function authenticate(string $username, string $password): bool{
        $db = ConnectionFactory::makeConnection();

        $query = 'SELECT COUNT(mdp) as NB_LIGNE, mdp FROM Utilisateur WHERE username LIKE ?';
        $st = $db->prepare($query);
        $st->bindParam(1, $username, PDO::PARAM_STR);
        $st->execute();
        $db=null;

        $row = $st->fetch(PDO::FETCH_ASSOC);
        $nb_ligne = $row['NB_LIGNE'];
        if ($nb_ligne != 1) {
			return false;
		}
		$hash = $row['mdp'];
		$a = password_verify($password, $hash);
		return $a;
    }

	/**
	 * @param string $email
	 * @param string $username
	 * @param string $password
	 * methode d'ajout d'un utilsiateur par defaut dans la bd
	 * !!! VERIFICATION DEJA PRESENCE DANS BD A FAIRE AVANT !!!
	 */
    public static function register(string $email, string $username, string $password){
        $db = ConnectionFactory::makeConnection();

		$query = "";
        $st = $db->prepare($query);
        $st->bindParam(1, $email, PDO::PARAM_STR);
        $st->execute();

        $hash=password_hash($password, PASSWORD_DEFAULT, ['cost'=> 12] );

        $query = 'INSERT INTO User (email, passwd) VALUES (?, ?)';
        $st = $db->prepare($query);
        $st->bindParam(1, $email, PDO::PARAM_STR);
        $st->bindParam(2, $hash, PDO::PARAM_STR);
        $st->execute();
        $db = null;
    }

	/**
	 * @param $username
	 * @return bool
	 * true = username deja dans table false = sinon et si username = login car c'est une page défini
	 */
	public static function usernameExists($username): bool{
        $db = ConnectionFactory::makeConnection();

        // Utilisation de LIMIT dans la requête pour éviter de parcourir complètement la table si on a déjà trouvé le pseudo de l'utilisateur.
        $query = "SELECT 1 as present from Utilisateur where username = ? LIMIT 1;";
        $st = $db->prepare($query);
        $st->bindParam(1, $username, PDO::PARAM_STR);
        $st->execute();

        $present = $st->fetch() == true; // Vérifie s'il y a au moins une ligne dans le résultat de la requête.
		return $present;
	}

	/**
	 * @param $email
	 * @return bool
	 * true = email deja dans table    false = sinon
	 */
	public static function emailExists($email): bool{
        $db = ConnectionFactory::makeConnection();

        // Utilisation de LIMIT dans la requête pour éviter de parcourir complètement la table si on a déjà trouvé le mail de l'utilisateur.
        $query = "SELECT 1 as present from Utilisateur where emailUt = ? LIMIT 1;";
        $st = $db->prepare($query);
        $st->bindParam(1, $email, PDO::PARAM_STR);
        $st->execute();

        $present = $st->fetch() == true; // Vérifie s'il y a au moins une ligne dans le résultat de la requête.
        return $present;
	}

	/**
	 * @param $password
	 * @return array(bool) de 4 états (>8 caractères,>1 Maj, >1 caractère spécial, >1 chiffre)
	 * true = password valide    false = sinon
	 * password valide :
	 * - au moins 8 caractères
	 * - au moins 1 lettre majuscule
	 * - au moins 1 caractère spécial
	 * - au moins 1 chiffre
	 */
	public static function checkPassword($password): array{
        $validationConditions = array(
            "longueur" => false,
            "majuscule" => false,
            "caractereSpecial" => false,
            "chiffre" => false
        );

        if(strlen($password)>=8) $validationConditions["longueur"] = true;

        if(preg_match('/[A-Z]/', $password)) $validationConditions["majuscule"] = true;

        if(preg_match('/[\W]/', $password)) $validationConditions["caractereSpecial"] = true;

        if(preg_match('/[0-9]/', $password)) $validationConditions["chiffre"] = true;

		return $validationConditions;
	}
}