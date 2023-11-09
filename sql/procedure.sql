-- DROP des procédures
DROP PROCEDURE IF EXISTS `obtenirAbonnementUtilisateur`;
DROP PROCEDURE IF EXISTS `obtenirAbonnementTag`;
DROP PROCEDURE IF EXISTS `obtenirTouitesUtilisateur`;
DROP PROCEDURE IF EXISTS `obtenirMeilleursTouites`;
DROP PROCEDURE IF EXISTS ajoutPublier;
DROP FUNCTION IF EXISTS `ajoutTouite`;
DROP FUNCTION IF EXISTS ajoutImage;
DROP FUNCTION IF EXISTS `ajoutTag`;
DROP PROCEDURE IF EXISTS ajoutUtilisateur;
DROP PROCEDURE IF EXISTS `ajoutUtiliserTag`;
DROP PROCEDURE IF EXISTS ajoutUtiliserImage
DROP PROCEDURE IF EXISTS `obtenirUtilisateurAbo`;
DROP PROCEDURE IF EXISTS `obtenirTouitesUtilisateursSuivis`;
DROP PROCEDURE IF EXISTS `obtenirTouitesTagChoisi`;
DROP PROCEDURE IF EXISTS `voter`;
DROP PROCEDURE IF EXISTS `compterLikes`;
DROP PROCEDURE IF EXISTS `compterDislikes`;
DROP FUNCTION IF EXISTS `calculerNote`;
DROP PROCEDURE IF EXISTS afficherTouite;
DROP PROCEDURE IF EXISTS afficherTouiteImages;
DROP PROCEDURE IF EXISTS afficherTouiteTags;
DROP PROCEDURE IF EXISTS retouiter;
DROP PROCEDURE IF EXISTS sabonnerTag;
DROP PROCEDURE IF EXISTS sabonnerUtilisateur;
DROP PROCEDURE IF EXISTS voir;
DROP PROCEDURE IF EXISTS annulerLikeDislike;
DROP PROCEDURE IF EXISTS supprimerTouite;
DROP PROCEDURE IF EXISTS afficherHistoriqueUtilisateur;
DROP FUNCTION IF EXISTS ajoutHistorique;
DROP PROCEDURE IF EXISTS ajoutRecherche;
DROP PROCEDURE IF EXISTS annulerAbonnementUtilisateur;
DROP PROCEDURE IF EXISTS annulerAbonnementTag;


-- Créations des PROCEDURES

-- Liste des abonnements (utilisateurs)
DELIMITER $$
CREATE PROCEDURE `obtenirAbonnementUtilisateur`(IN `emailUtilisateur` VARCHAR(150))
select u.emailUt, u.nomUt, u.prenomUt, u.username, u.dateInscription
from EtreAboUtilisateur aUt inner join Utilisateur u on aUt.emailUtAbo=u.emailUt
where aUt.emailUt = emailUtilisateur
ORDER BY u.username
$$
DELIMITER ;

-- Liste des abonnements (tags)
DELIMITER $$
CREATE PROCEDURE `obtenirAbonnementTag`(IN `emailUtilisateur` VARCHAR(150))
select t.libelle, t.descriptionTag, t.dateCreation, count(t.idTag)
from Tag t inner join EtreAboTag aTa on t.idTag=aTa.idTag
           inner join UtiliserTag ut on aTa.idTag=ut.idTag
where aTa.emailUt = emailUtilisateur
ORDER BY t.libelle
$$
DELIMITER ;

-- Liste des abonnés (utilisateurs)
DELIMITER $$
CREATE PROCEDURE `obtenirUtilisateurAbo`(IN `emailUtilisateur` VARCHAR(150))
select u.nomUt, u.prenomUt, u.username, u.dateInscription
from EtreAboUtilisateur aUt inner join Utilisateur u on aUt.emailUt=u.emailUt
where aUt.emailUtAbo = emailUtilisateur
ORDER BY u.username
$$
DELIMITER ;

-- Liste des touites qu’un utilisateur a publié :
DELIMITER $$
CREATE PROCEDURE `obtenirTouitesUtilisateur`(IN `emailUtilisateur` VARCHAR(150))
select t.texte, t.date, t.notePertinence, t.nbLike, t.nbDislike, t.nbRetouite, t.nbVue
from Utilisateur u inner join PublierPar p on u.emailUt=p.emailUt
                   inner join Touite t on p.idTouite=t.idTouite
where u.emailUt like emailUtilisateur
ORDER BY t.date DESC$$
DELIMITER ;

-- Liste des meilleurs touites d'aujourd'hui (HOME PAGE)
DELIMITER $$
CREATE PROCEDURE `obtenirMeilleursTouites`()
select t.texte, t.date, t.notePertinence, t.nbLike, t.nbDislike, t.nbRetouite, t.nbVue
from Touite t
ORDER BY t.notePertinence DESC, t.date DESC$$
DELIMITER ;

-- Ajout d'un touite
DELIMITER //
CREATE FUNCTION ajoutTouite(nouvTexte TEXT) RETURNS INT
BEGIN
    DECLARE v_idTouite INT;
    SELECT COALESCE(MAX(idTouite), 0) + 1 INTO v_idTouite FROM Touite;
    INSERT INTO Touite (idTouite, texte, date) VALUES (v_idTouite, nouvTexte, NOW());
    RETURN v_idTouite;
END;
//
DELIMITER ;

-- Ajout d'un tag
DELIMITER //
CREATE FUNCTION ajoutTag(nouvLibelle TEXT) RETURNS INT
BEGIN
    DECLARE v_idTag INT;
    SELECT COALESCE(MAX(idTag), 0) + 1 INTO v_idTag FROM Tag;
    INSERT INTO Tag (idTag, libelle) VALUES (v_idTag, nouvLibelle);
    RETURN v_idTag;
END;
//
DELIMITER ;

-- Ajout d'une image
DELIMITER //
CREATE FUNCTION ajoutImage(nouvDescription TEXT, src TEXT) RETURNS INT
BEGIN
    DECLARE v_idImage INT;
    SELECT COALESCE(MAX(idTag), 0) + 1 INTO v_idImage FROM Tag;
    INSERT INTO Image (idImage, descriptionImg, cheminSrc) VALUES (v_idImage, nouvDescription, src);
    RETURN v_idImage;
END;
//
DELIMITER ;

-- Ajout d'un lien touite/tag
DELIMITER //
CREATE PROCEDURE ajoutUtiliserTag(v_idTouite INT, v_idTag INT)
BEGIN
    INSERT INTO UtiliserTag (idTouite, idTag) VALUES (v_idTouite, v_idTag);
END;
//
DELIMITER ;

-- Ajout d'un lien touite/image
DELIMITER //
CREATE PROCEDURE ajoutUtiliserImage(v_idTouite INT, v_idImage INT)
BEGIN
    INSERT INTO UtiliserImage (idTouite, idImage) VALUES (v_idTouite, v_idImage);
END;
//
DELIMITER ;

-- Ajout d'un lien touite/utilisateur
DELIMITER //
CREATE PROCEDURE ajoutPublier(v_idTouite INT, v_email TEXT)
BEGIN
    INSERT INTO PublierPar (idTouite, emailUt) VALUES (v_idTouite, v_email);
END;
//
DELIMITER ;


-- Ajout d'un utilisateur

CREATE PROCEDURE ajoutUtilisateur(emailUtilisateur TEXT, pseudo TEXT, motdepasse TEXT)
BEGIN
    INSERT INTO Utilisateur (emailUt, username, mdp, dateInscription) VALUES (emailUtilisateur, pseudo, motdepasse, now());
END;

-- Compter likes
CREATE PROCEDURE compterLikes(v_idTouite INT)
BEGIN
    select IFNULL(SUM(vote=1), 0) as nbLike from AvoirVote where idTouite=v_idTouite;
END;


-- Compter dislikes
CREATE PROCEDURE compterDislikes(v_idTouite INT)
BEGIN
    select IFNULL(SUM(vote=-1), 0) as nbDiike from AvoirVote where idTouite=v_idTouite;
END;


-- Calculer note
CREATE FUNCTION calculerNote(v_idTouite INT) RETURNS INT
BEGIN
    return compterLikes(v_idTouite) - compterDislikes(v_idTouite);
END;


-- Voter
create procedure voter(IN v_email text, IN v_idTouite int, IN v_vote int)
BEGIN
    INSERT INTO AvoirVote VALUES (v_email, v_idTouite, v_vote);
    IF v_vote=1 THEN
        UPDATE Touite
        SET nbLike = (select compterLikes(v_idTouite)), notePertinence = (select calculerNote(v_idTouite))
        WHERE idTouite=v_idTouite;
    ELSE
        UPDATE Touite
        SET nbDislike = (select compterDislikes(v_idTouite)), notePertinence = (select calculerNote(v_idTouite))
        WHERE idTouite=v_idTouite;
    END IF;
END;

-- Annuler Like/Dislike
CREATE PROCEDURE annulerLikeDislike(v_idTouite INT, v_email TEXT)
BEGIN
    DELETE FROM AvoirVote
    WHERE idTouite = v_idTouite and emailUt=v_email;
    UPDATE Touite
    SET nbLike = (select compterLikes(v_idTouite)),
        nbDislike = (select compterDislikes(v_idTouite)),
        notePertinence = (select calculerNote(v_idTouite))
    WHERE idTouite=v_idTouite;
END;

-- Supprimer touite
CREATE PROCEDURE supprimerTouite(v_idTouite INT)
BEGIN
    -- Supprimer les avoirVote
    DELETE FROM AvoirVote
    WHERE idTouite=v_idTouite;
    -- Supprimer le PublierPar
    DELETE FROM PublierPar
    WHERE idTouite=v_idTouite;
    -- Supprimer UtiliserTag
    DELETE FROM UtiliserTag
    WHERE idTouite=v_idTouite;
    -- Supprimer UtiliserImage
    DELETE FROM UtiliserImage
    WHERE idTouite=v_idTouite;
    -- Supprimer Touite
    DELETE FROM Touite
    WHERE idTouite=v_idTouite;
END;


-- Incrémenter vue
CREATE PROCEDURE voir(v_idTouite INT)
BEGIN
    UPDATE Touite
    SET nbVue = nbVue+1
    WHERE idTouite=v_idTouite;
END;

-- Incrémenter retouite
CREATE PROCEDURE retouiter(v_idTouite INT)
BEGIN
    UPDATE Touite
    SET nbRetouite = nbRetouite+1
    WHERE idTouite=v_idTouite;
END;

-- Ajouter abonnement utilisateur
CREATE PROCEDURE sabonnerUtilisateur(mail1 TEXT, mail2 TEXT)
BEGIN
    INSERT INTO EtreAboUtilisateur(emailUt, emailUtAbo, dateAboUt) VALUES (mail1, mail2, now());
END;

-- Ajouter abonnement tag

CREATE PROCEDURE sabonnerTag(mail1 TEXT, v_idTag INT)
BEGIN
    INSERT INTO EtreAboTag(emailUt, idTag, dateAboTag) VALUES (mail1, v_idTag, now());
END;

-- Liste des touites des utilisateurs suivis :
DELIMITER $$
CREATE  PROCEDURE `obtenirTouitesUtilisateursSuivis`(IN `emailUtilisateur` VARCHAR(150))
    NOT DETERMINISTIC CONTAINS SQL SQL SECURITY DEFINER
select t.texte, t.date, t.notePertinence, t.nbLike, t.nbDislike, t.nbRetouite, t.nbVue
from EtreAboUtilisateur aUt inner join PublierPar pp on aUt.emailUtAbo=pp.emailUt
                            inner join Touite t on pp.idTouite=t.idTouite
where aUt.emailUt like emailUtilisateur
ORDER BY t.date DESC, t.notePertinence DESC$$
DELIMITER ;

-- Liste des touites par tag donné :
DELIMITER $$
CREATE  PROCEDURE `obtenirTouitesTagChoisi`(IN `tagChoisi` INT)
select t.texte, t.date, t.notePertinence, t.nbLike, t.nbDislike, t.nbRetouite, t.nbVue
from Touite t inner join UtiliserTag ut on t.idTouite=ut.idTouite
              inner join Tag ta on ut.idTag=ta.idTag
where ta.idTag = tagChoisi
ORDER BY t.notePertinence DESC, t.date DESC$$
DELIMITER ;

-- Affichage pour touite
CREATE PROCEDURE afficherTouite(v_idTouite INT)
BEGIN
    SELECT tou.texte, DATE_FORMAT(tou.date, '%d-%m-%Y à %H:%i') as formatted_date, tou.notePertinence,
           tou.nbLike, tou.nbDislike, tou.nbVue, u.username
    FROM Utilisateur u  INNER JOIN PublierPar p ON u.emailUt = p.emailUt
                        INNER JOIN Touite tou ON p.idTouite = tou.idTouite
    WHERE tou.idTouite = v_idTouite;
END;

-- Affichage des tags pour un touite
CREATE PROCEDURE afficherTouiteTags(v_idTouite INT)
BEGIN
    SELECT ta.libelle
    FROM Touite tou INNER JOIN UtiliserTag ut ON tou.idTouite = ut.idTouite
                    INNER JOIN Tag ta ON ut.idTag=ta.idTag
    WHERE tou.idTouite = v_idTouite;
END;

-- Affichage des images pour un touite
CREATE PROCEDURE afficherTouiteImages(v_idTouite INT)
BEGIN
    SELECT im.cheminSrc
    FROM Touite tou INNER JOIN UtiliserImage ui ON tou.idTouite = ui.idTouite
                    INNER JOIN Image im ON ui.idImage=im.idImage
    WHERE tou.idTouite = v_idTouite;
END;

-- afficherHistoriqueUtilisateur
CREATE PROCEDURE afficherHistoriqueUtilisateur (v_email TEXT)
BEGIN
    SELECT r.recherche, r.dateRecherche
    FROM Historique h inner join Recherche r on h.idRecherche=r.idRecherche
    where emailUt=v_email;
end;


-- ajoutRecherche;
create
     function ajoutRecherche(r text) returns int
BEGIN
    DECLARE v_idRecherche INT;
    select COALESCE(max(idRecherche), 0) + 1 into v_idRecherche from Recherche;
    INSERT INTO Recherche VALUES (v_idRecherche, r, now());
    return v_idRecherche;
end;

-- ajoutHistorique
create
     procedure ajoutHistorique(v_idRecherche INT, v_email TEXT)
BEGIN
    INSERT INTO Historique VALUES (v_email, v_idRecherche);
end;

-- annulerAbonnementUtilisateur
CREATE PROCEDURE annulerAbonnementUtilisateur (v_mail TEXT, v_mailAbo TEXT)
BEGIN
    DELETE FROM EtreAboUtilisateur
    WHERE emailUt = v_mail and emailUtAbo=v_mailAbo;
end;

-- annulerAbonnementTag
CREATE PROCEDURE annulerAbonnementTag (v_mail TEXT, v_idTag INT)
BEGIN
    DELETE FROM EtreAboTag
    WHERE emailUt = v_mail and idTag=v_idTag;
end;

-- afficher liste des touites abonnements utilisateurs (mail)
CREATE PROCEDURE afficherTouitesAboUtilisateur(v_email TEXT)
BEGIN
    select T.texte, T.date, T.notePertinence, T.nbLike, T.nbDislike, T.nbRetouite, T.nbVue, u.username
    from EtreAboUtilisateur aUt inner join Utilisateur u on aUt.emailUtAbo = u.emailUt
                                inner join PublierPar PP on u.emailUt = PP.emailUt
                                inner join Touite T on PP.idTouite = T.idTouite
    where aUt.emailUt=v_email;
END;

-- afficher liste des touites abonnements tags (mail)
CREATE PROCEDURE afficherTouitesAboTag(v_email TEXT)
BEGIN
    select DISTINCT t.texte, t.date, t.notePertinence, t.nbLike, t.nbDislike, t.nbRetouite, t.nbVue, u2.username
    from Utilisateur u inner join EtreAboTag aTa on u.emailUt = aTa.emailUt
                       inner join Tag ta on aTa.idTag = ta.idTag
                       inner join UtiliserTag ut on ta.idTag = ut.idTag
                       inner join Touite t on ut.idTouite = t.idTouite
                       inner join PublierPar p on t.idTouite = p.idTouite
                       inner join Utilisateur u2 on p.emailUt=u2.emailUt
    where p.emailUt != v_email and aTa.idTag IN (select DISTINCT ta.idTag
                                                 from EtreAboTag aTa inner join Tag ta on aTa.idTag = ta.idTag
                                                                     inner join UtiliserTag ut on ta.idTag = ut.idTag
                                                                     inner join Touite t on ut.idTouite = t.idTouite
                                                 where aTa.emailUt =v_email);
END;

-- etreAbonnéUtilisateur (mail mail)
CREATE FUNCTION etreAboUtilisateur(v_email TEXT, v_emailAbo TEXT) RETURNS bool
BEGIN
    DECLARE v_etreAbo date;
    select dateAboUt into v_etreAbo from EtreAboUtilisateur where emailUt=v_email and emailUtAbo=v_emailAbo;
    IF (v_etreAbo) IS NULL THEN
        return false;
    ELSE
        return true;
    end if;
end;

-- etreAbonnéTag (mail idtag)
CREATE FUNCTION etreAboTag(v_email TEXT, v_idTag INT) RETURNS bool
BEGIN
    DECLARE v_etreAbo date;
    select dateAboTag into v_etreAbo from EtreAboTag where emailUt=v_email and idTag=v_idTag;
    IF (v_etreAbo) IS NULL THEN
        return false;
    ELSE
        return true;
    end if;
end;

-- etreVote (idtouite, user)
CREATE FUNCTION etreVote(v_idTouite INT, v_mail TEXT) RETURNS bool
BEGIN
    DECLARE v_etreVote date;
    select vote into v_etreVote from AvoirVote where emailUt=v_mail and idTouite=v_idTouite;
    IF (v_etreVote) IS NULL THEN
        return false;
    ELSE
        return true;
    end if;
end;


-- obtenir utilisateur avec touite
CREATE PROCEDURE obtenirUsername(v_idtouite INT)
BEGIN
    select u.username
    from Touite t inner join PublierPar p on t.idTouite = p.idTouite
                  inner join Utilisateur u on p.emailUt = u.emailUt
    where t.idTouite=v_idtouite;
end;

-- APPELS DES PROCEDURES

CALL `obtenirAbonnementUtilisateur`('john.doe@example.com');
CALL `obtenirAbonnementTag`('john.doe@example.com');
CALL `obtenirUtilisateurAbo`('john.doe@example.com');
CALL `obtenirTouitesUtilisateur`('john.doe@example.com');
CALL `obtenirTouitesUtilisateursSuivis`('john.doe@example.com');
CALL `obtenirTouitesTagChoisi`(1);








-------------------------
PROCEDURE GREGOIRE
---------------------------
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


DROP PROCEDURE IF EXISTS desabonnerUser;
CREATE PROCEDURE desabonnerUser(IN username VARCHAR(150), IN usernameCible VARCHAR(150))
BEGIN
    DELETE ea FROM EtreAboUtilisateur ea
                       INNER JOIN Utilisateur u ON u.emailUt=ea.emailUt
                       INNER JOIN Utilisateur uC ON uC.emailUt=ea.emailUtAbo
    WHERE u.username=username AND uC.username=usernameCible;
end;

DROP PROCEDURE IF EXISTS EtreUserValide;
CREATE PROCEDURE EtreUserValide(IN username VARCHAR(50))
BEGIN
    SELECT COUNT(*) AS nb_ligne FROM Utilisateur u WHERE u.username LIKE username;
end;