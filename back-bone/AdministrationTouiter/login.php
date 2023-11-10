<?php
declare(strict_types=1);

require_once "src/db/ConnectionFactory.php";

ConnectionFactory::setConfig('db.config.ini');
session_start();

include "constantes.php";
$path = path;

$message = '';

// filtrage saisie user
$username = htmlspecialchars($_POST['username']);
$password = htmlspecialchars($_POST['password']);

$db = ConnectionFactory::makeConnection();

// récupération du mdp de l'username donné pour faire la comparaison avec celui donné
$query = 'SELECT COUNT(mdp) as NB_LIGNE, mdp, permissions FROM Utilisateur WHERE username LIKE ?';
$st = $db->prepare($query);
$st->bindParam(1, $username, PDO::PARAM_STR);
$st->execute();
$db=null;

$row = $st->fetch(PDO::FETCH_ASSOC);
$nb_ligne = $row['NB_LIGNE'];
// si l'username n'existe pas, on ne fait pas la comparaison
if ($nb_ligne != 1) {
    // on indique que la connexion n'a pas réussi si l'username n'est pas bon
    $connexionReussie = false;
}
// sinon, on compare les mdp et on vérifie que le compte a les permissions nécessaires et si ce n'est pas le cas, on refuse la connexion
else {
    if($row['permissions'] >= 1) {
        $connexionReussie = false;
        $hash = $row['mdp'];
        // on vérifie que le mdp donné correspond à celui de l'username donné
        $valide = password_verify($password, $hash);
    }
    else{
        $valide = false;
        $permissionsInsuffisantes = true;
    }
// si connexion valide
    if ($valide) {
        $db = ConnectionFactory::makeConnection();
        // récupération des infos de l'utilisateur
        $query = 'SELECT * FROM Utilisateur WHERE username LIKE ?';
        $st = $db->prepare($query);
        $st->bindParam(1, $username, PDO::PARAM_STR);
        $st->execute();
        $db = null;

        // on stocke les infos dont on a besoin de l'utilisateur dans la session
        $_SESSION['username'] = $username;
        $tRes = $st->fetch(PDO::FETCH_ASSOC);
        $_SESSION['permissions'] = $tRes['permissions'];
        $_SESSION['email'] = $tRes['emailUt'];
        // redirection vers accueil
        header("Location: $path");
    } else {
        // Si le mdp n'est pas bon, on indique que la connexion n'a pas réussi
        $connexionReussie = false;
    }
}

// si la connexion n'a pas été réussie, on affiche un message d'erreur
if(!$connexionReussie){
    if (isset($permissionsInsuffisantes) && $permissionsInsuffisantes) {
        $messageErreur = "Vous n'avez pas les permissions nécessaires pour vous connecter à ce compte";
    }
    else{
        $messageErreur = "Login ou mot de passe incorrect";
    }

    echo <<<HTML
        <p>
            $messageErreur<br>
            <a href="{$path}index.php">Retour à l'accueil</a>
        </p>
    HTML;
}


echo '</body>
</html>';


