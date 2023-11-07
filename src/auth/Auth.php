<?php
declare(strict_types=1);
namespace touiteur\auth;


use touiteur\db\ConnectionFactory;
use PDO;
use touiteur\exception\SQLError;


class Auth{

    public static function authenticate(string $email, string $password): bool{
        $db = ConnectionFactory::makeConnection();

        $query = 'SELECT COUNT(emailUt) as NB_LIGNE, emailUt FROM Utilisateur WHERE emailUt LIKE ?';
        $st = $db->prepare($query);
        $st->bindParam(1, $email, PDO::PARAM_STR);
        $st->execute();
        $db=null;

        #$nb_ligne = $st->fetch(PDO::FETCH_ASSOC)['emailUt'];


        $row = $st->fetch(PDO::FETCH_ASSOC);
        $nb_ligne = $row['NB_LIGNE'];
        if ($nb_ligne != 1) {
			return false;
		}
		$hash = $row['emailUt'];
        #return password_verify($password, $hash);
		return true;
    }

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

        return "Utilisateur ajouté";
    }

	public static function usernameExists($username): bool{

	}

	public static function emailExists($email): bool{

	}

	public static function checkPassword($password): bool{

	}
}