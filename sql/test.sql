DROP PROCEDURE IF EXISTS obtenirTouiteGénérale;

/**
    * @param page : la page de touites à retourner
    * @param nbTouiteParPage : le nombre de touites par page
    * @return les touites de la page demandée
    * retourne uniquement le toutie avec un auteur specifier dans la table PublierPar
 */
CREATE PROCEDURE obtenirTouiteGénérale(IN page INT, IN nbTouiteParPage INT)
    BEGIN
        DECLARE inf INT;

        SET inf = nbTouiteParPage*(page-1);

        SELECT t.*, u.emailUt, u.username FROM Touite t
            INNER JOIN PublierPar pp ON t.idTouite=pp.idTouite
            INNER JOIN Utilisateur u ON pp.emailUt=u.emailUt
        ORDER BY t.notePertinence DESC
        LIMIT nbTouiteParPage OFFSET inf;
    end;

CALL obtenirTouiteGénérale(2, 10);


DROP PROCEDURE IF EXISTS obtenirNbPagesTouiteGénérale;
CREATE PROCEDURE obtenirNbPagesTouiteGénérale(IN nbTouiteParPage INT, OUT result INT)
    BEGIN
        DECLARE nbPages INT;
        SELECT CEIL(COUNT(*)/nbTouiteParPage) INTO nbPages FROM Touite;
        SET result = nbPages;
    end;

CALL obtenirNbPagesTouiteGénérale(10, @result);
SELECT @result;


DROP PROCEDURE IF EXISTS verifierUsernameInAbonnement;
CREATE PROCEDURE verifierUsernameInAbonnement(IN username VARCHAR(150), IN usernameCible VARCHAR(150))
    BEGIN
        SELECT COUNT(*) AS nb_ligne FROM EtreAboUtilisateur ea
            INNER JOIN Utilisateur u ON u.emailUt=ea.emailUt
            INNER JOIN Utilisateur uC ON uC.emailUt=ea.emailUtAbo
        WHERE u.username=username AND uC.username=usernameCible;
    end;

CALL verifierUsernameInAbonnement('a', 'JohnDoeee');