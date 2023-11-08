<?php
declare(strict_types=1);
namespace touiteur\classe;

use touiteur\db\ConnectionFactory;
use PDO;
use DateTime;

// Classe User qui represente un utilisateur du site

/**
 *
 */
class User{

    // Attributs
    /**
     * @var string
     */
    private string $email;
    /**
     * @var string
     */
    private string $nom;
    /**
     * @var string
     */
    private string $prenom;
    /**
     * @var string
     */
    private string $username;
    /**
     * @var DateTime|string
     */
    private DateTime $dateInscription;
    /**
     * @var int
     */
    private int $permissions;

    /**
     * @param string $email email
     * @param string $nom nom
     * @param string $prenom prenom
     * @param string $username nom d'utilisateur
     * @param DateTime $dateInscription date d'inscription
     * @param int $permissions 0 = utilisateur normal, 1 = admin
     */
    public function __construct(string $email, string $nom, string $prenom,  string $username, DateTime $dateInscription, int $permissions)
    {
        $this->email = $email;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->dateInscription = $dateInscription;
        $this->username = $username;
        $this->permissions = $permissions;
    }


    /**
     * @param string $email
     * @return User
     * @throws SQLError
     * méthode de chargement d'un utilisateur depuis la bd
     */
	public static function loadUser(string $email): User{
        $db = ConnectionFactory::makeConnection();

        $query = "SELECT * from Utilisateur where emailUt = ?";
        $st = $db->prepare($query);
        $st->bindParam(1, $email, PDO::PARAM_STR);
        $st->execute();

        $tRes = $st->fetch(PDO::FETCH_ASSOC);

        // Utilisation de datetime pour remettre un string en date
        $user = new User($tRes['emailUt'], $tRes['nomUt'], $tRes["prenomUt"], $tRes["username"], new DateTime($tRes["dateInscription"]), $tRes["permissions"]);

        $db = null;
        return $user;
	}


	/**
     * @param string $email
     * @param string $nom
     * @param string $prenom
     * @param string $username
     * @param DateTime $dateInscription
     * @param int $permissions
     * @throws SQLError
     * méthode d'ajout d'un utilisateur dans la bd
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
		return $html;
	}

    /**
     * @param string $attr
     * @return mixed
     * @throws \Exception
     * méthode magique pour récupérer les attributs de la classe
     */
    public function __get(string $attr){
        return $this->$attr;
    }

    /**
     * @param string $attr
     * @param string $val
     * @return string
     * méthode magique pour modifier les attributs de la classe
     */
    public function __set(string $attr, string $val){
        return $this->$attr = $val;
    }
}