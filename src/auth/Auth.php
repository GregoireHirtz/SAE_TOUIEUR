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
	 * true = username deja dans table    false = sinon
	 */
	public static function usernameExists($username): bool{
		//TODO a faire
		return false;
	}

	/**
	 * @param $email
	 * @return bool
	 * true = email deja dans table    false = sinon
	 */
	public static function emailExists($email): bool{
		//TODO a faire
		return false;
	}

	/**
	 * @param $password
	 * @return bool
	 * true = password valide    false = sinon
	 * password valide :
	 * - au moins 8 caractères
	 * - au moins 1 lettre majuscule
	 * - au moins 1 caractère spécial
	 * - au moins 1 chiffre
	 */
	public static function checkPassword($password): bool{
		//TODO a faire
		return false;
	}
}