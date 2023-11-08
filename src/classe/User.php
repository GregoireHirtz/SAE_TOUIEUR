<?php
declare(strict_types=1);
namespace touiteur\classe;

class User{

	private $email;
	private $username;
	private $permissions;

	public function __construct__($e, $u, $p){
		$this->email = $e;
		$this->username = $u;
		$this->permissions = $p;
	}

	/**
	 * @param $email
	 * @return User
	 *
	 * avec un email, il generer un user selon info bd
	 */
	public static function loadUser($email): User{

	}


	/**
	 * @return String code html pour afficher case user
	 */
	public function genererUser(): String{
		$html = <<<HTML
	 <header>
        <a href="#" class="photo_profil"><img src="images/user.svg" alt="PP"></a>
        <a href="#" class="pseudo">!________!</a>
        <p>JJ-MM-AAAA à hh:mm</p>
        <div>
            <button class="sabonner">S'abonner</button>
        </div>
    </header>
HTML;


	}
	
	public function getUsername(): String{
		return $this->username;
	}
}