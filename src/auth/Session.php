<?php

namespace touiteur\auth;

use PDO;
use touiteur\db\ConnectionFactory;

class Session{

    public static function loadSession(string $username): void{

		$db = ConnectionFactory::makeConnection();
		$query = 'SELECT * FROM Utilisateur WHERE username LIKE ?';
		$st = $db->prepare($query);
		$st->bindParam(1, $username, PDO::PARAM_STR);
		$st->execute();
		$db = null;

		$_SESSION['username'] = $username;
		$_SESSION['permissions'] = $st->fetch()['permissions'];
	}

	public static function unloadSession(): void{
		session_destroy();
		header("Location: /");
	}
}