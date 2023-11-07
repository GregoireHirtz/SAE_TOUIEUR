<?php
declare(strict_types=1);
namespace touiteur\auth;


use touiteur\db\ConnectionFactory;
use PDO;


class Auth{

    public static function authenticate(string $email, string $password): bool{
        $db = ConnectionFactory::makeConnection();

        $query = 'SELECT passwd FROM User WHERE email LIKE ?';
        $st = $db->prepare($query);
        $st->bindParam(1, $email, PDO::PARAM_STR);
        $st->execute();
        $db=null;

        $hash = $st->fetch(PDO::FETCH_ASSOC)['passwd'];
        return password_verify($password, $hash);
    }

    public static function register(string $email, string $password){
        $db = ConnectionFactory::makeConnection();

        $query = 'SELECT COUNT(id) as NB FROM User WHERE email LIKE ?';
        $st = $db->prepare($query);
        $st->bindParam(1, $email, PDO::PARAM_STR);
        $st->execute();

        $nb = $st->fetch(PDO::FETCH_ASSOC)['NB'];
        if ($nb != 0){
            return "Email déjà utilisé";
        }
        if (strlen($password) < 2){
            return "Mot de passe trop court";
        }

        $hash=password_hash($password, PASSWORD_DEFAULT, ['cost'=> 12] );

        $query = 'INSERT INTO User (email, passwd) VALUES (?, ?)';
        $st = $db->prepare($query);
        $st->bindParam(1, $email, PDO::PARAM_STR);
        $st->bindParam(2, $hash, PDO::PARAM_STR);
        $st->execute();
        $db = null;

        return "Utilisateur ajouté";
    }
}