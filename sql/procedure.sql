-- DROP des procédures
DROP PROCEDURE IF EXISTS afficherTouite;
DROP PROCEDURE IF EXISTS afficherTouiteImages;
DROP PROCEDURE IF EXISTS afficherTouitesAboTag;
DROP PROCEDURE IF EXISTS afficherTouitesAboUtilisateur;
DROP PROCEDURE IF EXISTS afficherTouiteTags;
DROP FUNCTION IF EXISTS ajoutImage;
DROP PROCEDURE IF EXISTS ajoutPublier;
DROP PROCEDURE IF EXISTS ajoutRecherche;
DROP FUNCTION IF EXISTS `ajoutTag`;
DROP PROCEDURE IF EXISTS `ajoutTouite`;
DROP PROCEDURE IF EXISTS ajoutUtilisateur;
DROP PROCEDURE IF EXISTS ajoutUtiliserImage;
DROP PROCEDURE IF EXISTS `ajoutUtiliserTag`;
DROP PROCEDURE IF EXISTS annulerAbonnementTag;
DROP PROCEDURE IF EXISTS annulerAbonnementUtilisateur;
DROP PROCEDURE IF EXISTS annulerLikeDislike;
DROP FUNCTION IF EXISTS `calculerNote`;
DROP PROCEDURE IF EXISTS `compterDislikes`;
DROP PROCEDURE IF EXISTS `compterLikes`;
DROP PROCEDURE IF EXISTS compterStat;
DROP PROCEDURE IF EXISTS desabonnerUser;
DROP FUNCTION IF EXISTS etreAboTag;
DROP FUNCTION IF EXISTS etreAboUtilisateur;
DROP PROCEDURE IF EXISTS EtreUserValide;
DROP FUNCTION IF EXISTS etreVote;
DROP PROCEDURE IF EXISTS etreVoter;
DROP PROCEDURE IF EXISTS `obtenirAbonnementTag`;
DROP PROCEDURE IF EXISTS `obtenirAbonnementUtilisateur`;
DROP PROCEDURE IF EXISTS obtenirAuteur;
DROP PROCEDURE IF EXISTS `obtenirDateAbonnementTag`;
DROP PROCEDURE IF EXISTS `obtenirDateAbonnementUtilisateur`;
DROP PROCEDURE IF EXISTS obtenirIdTouitesUtilisateur;
DROP PROCEDURE IF EXISTS obtenirImage;
DROP PROCEDURE IF EXISTS obtenirInfluenceursDec;
DROP PROCEDURE IF EXISTS `obtenirMeilleursTouites`;
DROP PROCEDURE IF EXISTS obtenirNbPagesTouiteGénérale;
DROP PROCEDURE IF EXISTS obtenirTagTouite;
DROP PROCEDURE IF EXISTS obtenirTendancesDec;
DROP PROCEDURE IF EXISTS obtenirTouit;
DROP PROCEDURE IF EXISTS obtenirTouiteAbonne;
DROP PROCEDURE IF EXISTS obtenirTouiteGénérale;
DROP PROCEDURE IF EXISTS `obtenirTouitesTagChoisi`;
DROP PROCEDURE IF EXISTS `obtenirTouitesUtilisateur`;
DROP PROCEDURE IF EXISTS `obtenirTouitesUtilisateursSuivis`;
DROP PROCEDURE IF EXISTS obtenirUsername;
DROP PROCEDURE IF EXISTS obtenirUtilisateurAbo;
DROP PROCEDURE IF EXISTS sabonnerTag;
DROP PROCEDURE IF EXISTS sabonnerUtilisateur;
DROP PROCEDURE IF EXISTS supprimerTouite;
DROP PROCEDURE IF EXISTS verifierUsernameInAbonnement;
DROP PROCEDURE IF EXISTS voir;
DROP PROCEDURE IF EXISTS `voter`;


-- Créations des PROCEDURES


-- afficherTouite fournit toutes les données afin d'afficher le touite de l'id passé en paramètre
create procedure afficherTouite(IN v_idTouite int)
BEGIN
    SELECT tou.texte, DATE_FORMAT(tou.date, '%d-%m-%Y à %H:%i') as formatted_date, tou.notePertinence,
           tou.nbLike, tou.nbDislike, tou.nbVue, u.username
    FROM Utilisateur u  INNER JOIN PublierPar p ON u.emailUt = p.emailUt
                        INNER JOIN Touite tou ON p.idTouite = tou.idTouite
    WHERE tou.idTouite = v_idTouite;
END;


-- afficherTouiteImages affiche le chemin de l'image du touite si le touite en possède une
create procedure afficherTouiteImages(IN v_idTouite int)
BEGIN
    SELECT im.cheminSrc
    FROM Touite tou INNER JOIN UtiliserImage ui ON tou.idTouite = ui.idTouite
                    INNER JOIN Image im ON ui.idImage=im.idImage
    WHERE tou.idTouite = v_idTouite;
END;


-- afficherTouitesAboTag affiche les données de tous les touites correspondant aux tags que suit l'utilisateur passé en paramètre
create procedure afficherTouitesAboTag(IN v_email text)
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

-- Pareil pour les utilisateurs
create procedure afficherTouitesAboUtilisateur(IN v_email text)
BEGIN
    select T.texte, T.date, T.notePertinence, T.nbLike, T.nbDislike, T.nbRetouite, T.nbVue, u.username
    from EtreAboUtilisateur aUt inner join Utilisateur u on aUt.emailUtAbo = u.emailUt
                                inner join PublierPar PP on u.emailUt = PP.emailUt
                                inner join Touite T on PP.idTouite = T.idTouite
    where aUt.emailUt=v_email;

END;

-- afficherTouiteTags affiche le nom des tags présents sur un touite renseigné
create procedure afficherTouiteTags(IN v_idTouite int)
BEGIN
    SELECT ta.libelle
    FROM Touite tou INNER JOIN UtiliserTag ut ON tou.idTouite = ut.idTouite
                    INNER JOIN Tag ta ON ut.idTag=ta.idTag
    WHERE tou.idTouite = v_idTouite;
END;

-- ajouterVue permet d'ajouter une vue quand un touite est vu
create procedure ajouterVue(IN v_idTouite int)
BEGIN
    UPDATE Touite t SET t.nbVue=t.nbVue+1 WHERE t.idTouite=v_idTouite;
end;

-- ajoutImage ajoute une image avec une description et un chemin et retourne l'id de cette nouvelle image
create function ajoutImage(nouvDescription text, src text) returns int
BEGIN
    DECLARE v_idImage INT;
    SELECT COALESCE(MAX(idImage), 0) + 1 INTO v_idImage FROM Image;
    INSERT INTO Image (idImage, descriptionImg, cheminSrc) VALUES (v_idImage, nouvDescription, src);
    RETURN v_idImage;
END;

-- ajoutPublier ajoute un lien entre un touite et l'email
create procedure ajoutPublier(IN v_idTouite int, IN v_email text)
BEGIN
    INSERT INTO PublierPar (idTouite, emailUt) VALUES (v_idTouite, v_email);
END;

-- ajoutTag ajoute un tag avec un texte et retourne son id
create function ajoutTag(nouvLibelle text) returns int
BEGIN
    DECLARE v_idTag INT;
    SELECT COALESCE(MAX(idTag), 0) + 1 INTO v_idTag FROM Tag;
    INSERT INTO Tag (idTag, libelle, dateCreation) VALUES (v_idTag, nouvLibelle, now());
    RETURN v_idTag;
END;

-- ajoutTouite permet l'ajout d'un touite avec un texte et un email rentrés en paramètre
create function ajoutTouite(nouvTexte varchar(235), emailUtilisateur varchar(150)) returns int
BEGIN
    DECLARE v_idTouite INT;
    DECLARE startPos INT;
    DECLARE endPos INT;
    DECLARE tag VARCHAR(50);
    DECLARE v_idTag INT;
    DECLARE v_nouvIdTag INT;
    IF(nouvTexte IS NULL) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Le texte du touite ne peut pas être NULL';
    ELSEIF(LENGTH(nouvTexte) > 235) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Le texte du touite ne peut pas dépasser 235 caractères';
    ELSEIF(emailUtilisateur IS NULL) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'L\'email de l''utilisateur ne peut pas être NULL';
    ELSEIF(NOT EXISTS(SELECT * FROM Utilisateur WHERE emailUt = emailUtilisateur)) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'L\'email de l''utilisateur n\'existe pas';
    ELSE
        SELECT COALESCE(MAX(idTouite), 0) + 1 INTO v_idTouite FROM Touite;
        INSERT INTO Touite (idTouite, texte, date) VALUES (v_idTouite, nouvTexte, NOW());
        INSERT INTO PublierPar (idTouite, emailUt) VALUES (v_idTouite, emailUtilisateur);

        -- Initialisation de la position de départ à 1
        SET startPos = 1;

        -- Boucle principale parcourant la chaîne de caractères
        WHILE startPos <= LENGTH(nouvTexte) DO
                -- Recherche de la position du prochain espace à partir de la position de départ
                SET endPos = LOCATE(' ', nouvTexte, startPos);

                -- Si aucun espace n'est trouvé, on fixe la position de fin à la longueur totale du texte + 1
                IF endPos = 0 THEN
                    SET endPos = LENGTH(nouvTexte) + 1;
                END IF;

                -- Extraction du tag entre la position de départ et la position de fin
                SET tag = SUBSTRING(nouvTexte, startPos, endPos - startPos);

                -- Si le tag commence par '#', on l'ajoute à la table temporaire en retirant le '#' du début
                IF LEFT(tag, 1) = '#' THEN
                    SET tag = SUBSTRING(tag, 2);
                    IF(NOT EXISTS(SELECT * from Tag where libelle = tag)) THEN
                        SELECT COALESCE(MAX(idTag), 0) + 1 INTO v_nouvIdTag FROM Tag;
                        INSERT INTO Tag (idTag, libelle) VALUES (v_nouvIdTag, tag);
                    END IF;
                    SELECT idTag INTO v_idTag FROM Tag WHERE libelle = tag;
                    INSERT INTO UtiliserTag (idTouite, idTag) VALUES (v_idTouite, v_idTag);
                END IF;

                -- Mise à jour de la position de départ pour la prochaine itération
                SET startPos = endPos + 1;
            END WHILE;
    END IF;

    RETURN v_idTouite;
END;

-- ajoutUtilisateur permet l'ajout d'un Utilisateur avec un mail, un pseudo et un mot de passe
create procedure ajoutUtilisateur(IN emailUtilisateur text, IN pseudo text, IN motdepasse text)
BEGIN
    INSERT INTO Utilisateur (emailUt, username, mdp, dateInscription) VALUES (emailUtilisateur, pseudo, motdepasse, now());
END;

-- ajoutUtiliserImage créer le lien entre un touite et une image
create procedure ajoutUtiliserImage(IN v_idTouite int, IN v_idImage int)
BEGIN
    INSERT INTO UtiliserImage (idTouite, idImage) VALUES (v_idTouite, v_idImage);
END;

-- ajoutUtiliserTag créer le lien entre un touite et un tag
create procedure ajoutUtiliserTag(IN v_idTouite int, IN v_idTag int)
BEGIN
    INSERT INTO UtiliserTag (idTouite, idTag) VALUES (v_idTouite, v_idTag);
END;

-- annulerAbonnementTag permet de se désabonner d'un tag
create procedure annulerAbonnementTag(IN v_mail text, IN v_idTag int)
BEGIN
    DELETE FROM EtreAboTag
    WHERE emailUt = v_mail and idTag=v_idTag;
end;

-- annulerAbonnementUtilisateur permet de se désabonner d'un utilisateur
create procedure annulerAbonnementUtilisateur(IN v_mail text, IN v_mailAbo text)
BEGIN
    DELETE FROM EtreAboUtilisateur
    WHERE emailUt = v_mail and emailUtAbo=v_mailAbo;
end;

-- annulerLikeDislike permet de supprimer le vote d'un utilisateur sur un touite
create procedure annulerLikeDislike(IN v_idTouite int, IN v_email text)
BEGIN
    DELETE FROM AvoirVote WHERE idTouite=v_idTouite and emailUt=v_email;

    UPDATE Touite
    SET nbLike = (select compterLikes(v_idTouite)),
        nbDislike = (select compterDislikes(v_idTouite)),
        notePertinence = (select calculerNote(v_idTouite))
    WHERE idTouite=v_idTouite;
END;

-- calculerNote fait la différence entre le nombre de like et le nombre de dislike et la renvoie
create function calculerNote(v_idTouite int) returns int
BEGIN
    return compterLikes(v_idTouite) - compterDislikes(v_idTouite);
END;

-- compterDislikes compte le nombre de dislike du touite renseigné en paramètre
create function compterDislikes(v_idTouite int) returns int
BEGIN
    DECLARE nbDislikeCount INT;
    SELECT IFNULL(SUM(vote=-1), 0) INTO nbDislikeCount FROM AvoirVote WHERE idTouite = v_idTouite;
    RETURN nbDislikeCount;
END;

-- compterLikes compte le nombre de like du touite renseigné en paramètre
create function compterLikes(v_idTouite int) returns int
BEGIN
    DECLARE nbLikeCount INT;
    SELECT IFNULL(SUM(vote=1), 0) INTO nbLikeCount FROM AvoirVote WHERE idTouite = v_idTouite;
    RETURN nbLikeCount;
END;

-- compterStat renvoie le nombre d'abonnement, le nombre d'abonné et la note moyenne de tous les touites de l'email passer en paramètre
create procedure compterStat(IN v_email text)
BEGIN
    SELECT 'NbAbonnement' AS Type, COUNT(*) AS Nombre
    FROM EtreAboUtilisateur aUt
    WHERE aUt.emailUt = v_email

    UNION

    SELECT 'nbAbonne' AS Type, COUNT(*) AS Nombre
    FROM EtreAboUtilisateur aUt
    WHERE aUt.emailUtAbo = v_email

    UNION

    SELECT 'noteTotal' AS Type, ROUND(SUM(t.notePertinence) / COUNT(t.notePertinence))
    FROM PublierPar pp inner join Touite t on pp.idTouite=t.idTouite
    WHERE pp.emailUt = v_email;
END;

-- desabonnerUser desabonne le premier username au deuxième
create procedure desabonnerUser(IN username varchar(150), IN usernameCible varchar(150))
BEGIN
    DELETE ea FROM EtreAboUtilisateur ea
                       INNER JOIN Utilisateur u ON u.emailUt=ea.emailUt
                       INNER JOIN Utilisateur uC ON uC.emailUt=ea.emailUtAbo
    WHERE u.username=username AND uC.username=usernameCible;
end;

-- etreAboTag renvoie un booléen qui confirme ou non si le mail est abonné à se tag
create function etreAboTag(v_email text, v_idTag int) returns tinyint(1)
BEGIN
    DECLARE v_etreAbo date;
    select dateAboTag into v_etreAbo from EtreAboTag where emailUt=v_email and idTag=v_idTag;
    IF (v_etreAbo) IS NULL THEN
        return false;
    ELSE
        return true;
    end if;
end;

-- etreAboUtilisateur renvoie un booléen qui confirme ou non si le mail est abonné à cet utilisateur
create function etreAboUtilisateur(v_email text, v_emailAbo text) returns tinyint(1)
BEGIN
    DECLARE v_etreAbo date;
    select dateAboUt into v_etreAbo from EtreAboUtilisateur where emailUt=v_email and emailUtAbo=v_emailAbo;
    IF (v_etreAbo) IS NULL THEN
        return false;
    ELSE
        return true;
    end if;
end;

-- EtreUserValide
create procedure EtreUserValide(IN username varchar(50))
BEGIN
    SELECT COUNT(*) AS nb_ligne FROM Utilisateur u WHERE u.username LIKE username;
end;

-- etreVote renvoie un booléen pour savoir si l'utilisateur a déja voté ce touite
create function etreVote(v_idTouite int, v_mail text) returns tinyint(1)
BEGIN
    DECLARE v_etreVote date;
    select vote into v_etreVote from AvoirVote where emailUt=v_mail and idTouite=v_idTouite;
    IF (v_etreVote) IS NULL THEN
        return false;
    ELSE
        return true;
    end if;
end;

-- etreVoter renvoie le vote --A ETE DROP--
create procedure etreVoter(in v_idTouite int, in v_mail text)
BEGIN
    select vote
    from AvoirVote
    where idTouite=v_idTouite and emailUt=v_mail;
END;

-- obtenirAbonnementTag renvoie la liste des tags auxquelles l'utilisateur est abonné et depuis quand
create procedure obtenirAbonnementTag(IN emailUtilisateur varchar(150))
select t.libelle, t.descriptionTag, t.dateCreation, t.idTag, aTa.dateAboTag
from Tag t inner join EtreAboTag aTa on t.idTag=aTa.idTag
where aTa.emailUt = emailUtilisateur
ORDER BY t.libelle;

-- obtenirAbonnementUtilisateur renvoie la liste des utilisateurs auxquelles l'utilisateur est abonné et depuis quand
create procedure obtenirAbonnementUtilisateur(IN emailUtilisateur varchar(150))
select u.emailUt, u.nomUt, u.prenomUt, u.username, u.dateInscription, u.permissions, aUt.dateAboUt
from EtreAboUtilisateur aUt inner join Utilisateur u on aUt.emailUtAbo=u.emailUt
where aUt.emailUt = emailUtilisateur
ORDER BY u.username;

-- obtenirAuteur renvoie l'auteur d'un touite
create procedure obtenirAuteur(IN v_idTouite int)
BEGIN
    SELECT u.username
    FROM Utilisateur u inner join PublierPar pp on u.emailUt = pp.emailUt
    WHERE pp.idTouite = v_idTouite;
END;

-- obtenirDateAbonnementTag renvoie depuis quand la personne est abonné au tag
create procedure obtenirDateAbonnementTag(IN emailUtilisateur varchar(150), v_idTag INT)
select aTa.dateAboTag
from Tag t inner join EtreAboTag aTa on t.idTag=aTa.idTag
where aTa.emailUt = emailUtilisateur and aTa.idTag=v_idTag
ORDER BY t.libelle;

-- obtenirDateAbonnementUtilisateur renvoie depuis quand la personne est abonné à la personne
create procedure obtenirDateAbonnementUtilisateur(IN emailUtilisateur varchar(150), v_emailUtAbo TEXT)
select  aUt.dateAboUt
from EtreAboUtilisateur aUt inner join Utilisateur u on aUt.emailUtAbo=u.emailUt
where aUt.emailUt = emailUtilisateur and aUt.emailUtAbo=v_emailUtAbo
ORDER BY u.username;

-- obtenirIdTouitesUtilisateur envoie l'id des touites d'un utilisateur
create procedure obtenirIdTouitesUtilisateur(IN emailUtilisateur varchar(150))
select t.idTouite
from Utilisateur u inner join PublierPar p on u.emailUt=p.emailUt
                   inner join Touite t on p.idTouite=t.idTouite
where u.emailUt like emailUtilisateur
ORDER BY t.date DESC;

-- obtenirImage obtient le chemin de l'image du touite associé
create procedure obtenirImage(IN v_idTouit int)
BEGIN
    SELECT ui.idTouite, i.cheminSrc FROM UtiliserImage ui
                                             INNER JOIN Image i ON i.idImage=ui.idImage
    WHERE ui.idTouite LIKE v_idTouit;
end;

-- obtenirInfluenceursDec
create procedure obtenirInfluenceursDec()
SELECT count(emailUtAbo) as nbAbonnes, emailUtAbo, u.username, u.nomUt, u.prenomUt, u.dateInscription
from EtreAboUtilisateur e inner join Utilisateur u on e.emailUtAbo = u.emailUt
group by emailUtAbo
ORDER BY count(emailUtAbo) DESC;

-- obtenirMeilleursTouites renvoie tous les touites dans l'ordre de leur pertinence et de leur date
create procedure obtenirMeilleursTouites()
select t.texte, t.date, t.notePertinence, t.nbLike, t.nbDislike, t.nbRetouite, t.nbVue
from Touite t
ORDER BY t.notePertinence DESC, t.date DESC;

-- obtenirNbPagesTouiteGénérale renvoie le nombre page qu'occupe tous les touites
create function obtenirNbPagesTouiteGénérale(nbTouiteParPage int) returns int
BEGIN
    DECLARE nbPages INT;
    SELECT CEIL(COUNT(*)/nbTouiteParPage) INTO nbPages FROM Touite;
    RETURN nbPages;
end;

-- obtenirTagTouite renvoie les tags d'un touite
create procedure obtenirTagTouite(IN idTouite int)
BEGIN
    SELECT t.idTag, t.libelle FROM UtiliserTag ut
                                       INNER JOIN Tag t ON t.idTag=ut.idTag
    WHERE ut.idTouite LIKE idTouite;
end;

-- obtenirTendancesDec
create procedure obtenirTendancesDec()
SELECT count(uT.idTag) as nbUtilisationsTag, uT.idTag, t.libelle, t.descriptionTag, t.dateCreation
from UtiliserTag uT inner join Tag t on uT.idTag = t.idTag
group by idTag
ORDER BY nbUtilisationsTag DESC;

-- obtenirTouit renvoie le touite lié à une id
create procedure obtenirTouit(IN v_idTouite int)
BEGIN
    SELECT t.*, u.emailUt, u.username FROM Touite t
                                               INNER JOIN PublierPar pp ON t.idTouite=pp.idTouite
                                               INNER JOIN Utilisateur u ON pp.emailUt=u.emailUt
    WHERE t.idTouite=v_idTouite;
end;

-- obtenirTouiteAbonne renvoie les touites des abonnés de l'utilisateur
create procedure obtenirTouiteAbonne(IN v_username varchar(50), IN page int, IN nbTouiteParPage int)
BEGIN
    DECLARE inf INT;
    SET inf = nbTouiteParPage*(page-1);

    SELECT DISTINCT ut.idTag, t.*, u.emailUt, u.username FROM Touite t
                                                                  INNER JOIN PublierPar pp ON t.idTouite=pp.idTouite
                                                                  INNER JOIN Utilisateur u ON pp.emailUt=u.emailUt
                                                                  INNER JOIN UtiliserTag ut ON ut.idTouite=t.idTouite
    WHERE u.username IN
          (SELECT u2.username FROM EtreAboUtilisateur eau
                                       INNER JOIN Utilisateur u1 ON u1.emailUt=eau.emailUt
                                       INNER JOIN Utilisateur u2 ON u2.emailUt=eau.emailUtAbo
           WHERE u1.username LIKE v_username)
    ORDER BY t.notePertinence DESC
    LIMIT nbTouiteParPage OFFSET inf;
end;

-- obtenirTouiteGénérale renvoie les touites qu'il va voir pour un utilisateur qui n'a pas d'abonnement ou un anonyme. Ceux ci sont dans l'ordre chronologique inversé
create procedure obtenirTouiteGénérale(IN page int, IN nbTouiteParPage int)
BEGIN
    DECLARE inf INT;

    SET inf = nbTouiteParPage*(page-1);

    SELECT t.*, u.emailUt, u.username FROM Touite t
                                               INNER JOIN PublierPar pp ON t.idTouite=pp.idTouite
                                               INNER JOIN Utilisateur u ON pp.emailUt=u.emailUt
    ORDER BY t.notePertinence DESC
    LIMIT nbTouiteParPage OFFSET inf;
end;

-- obtenirTouitesTagChoisi renvoie tous les touites d'un tag choisi
create procedure obtenirTouitesTagChoisi(IN tagChoisi int)
select t.texte, t.date, t.notePertinence, t.nbLike, t.nbDislike, t.nbRetouite, t.nbVue
from Touite t inner join UtiliserTag ut on t.idTouite=ut.idTouite
              inner join Tag ta on ut.idTag=ta.idTag
where ta.idTag = tagChoisi
ORDER BY t.notePertinence DESC, t.date DESC;

-- obtenirTouitesUtilisateur renvoie tous les touites d'un utilisateur choisi
create procedure obtenirTouitesUtilisateur(IN emailUtilisateur varchar(150))
select t.idTouite, t.texte, t.date, u.username, t.notePertinence, t.nbLike, t.nbDislike, t.nbRetouite, t.nbVue
from Utilisateur u inner join PublierPar p on u.emailUt=p.emailUt
                   inner join Touite t on p.idTouite=t.idTouite
where u.emailUt like emailUtilisateur
ORDER BY t.date DESC;

-- obtenirTouitesUtilisateursSuivis renvoie tous les touites de tous les utilisateurs suivis par l'utilisateur choisi
create procedure obtenirTouitesUtilisateursSuivis(IN emailUtilisateur varchar(150))
select t.texte, t.date, t.notePertinence, t.nbLike, t.nbDislike, t.nbRetouite, t.nbVue
from EtreAboUtilisateur aUt inner join PublierPar pp on aUt.emailUtAbo=pp.emailUt
                            inner join Touite t on pp.idTouite=t.idTouite
where aUt.emailUt like emailUtilisateur
ORDER BY t.date DESC, t.notePertinence DESC;

-- obtenirTouiteTag comme obtenirTouitesTagChoisi mais sur la page voulue
create procedure obtenirTouiteTag(IN username varchar(50), IN page int, IN nbTouiteParPage int)
BEGIN
    DECLARE inf INT;
    SET inf = nbTouiteParPage*(page-1);

    SELECT DISTINCT ut.idTag, t.*, u.emailUt, u.username FROM Touite t
                                                                  INNER JOIN PublierPar pp ON t.idTouite=pp.idTouite
                                                                  INNER JOIN Utilisateur u ON pp.emailUt=u.emailUt
                                                                  INNER JOIN UtiliserTag ut ON ut.idTouite=t.idTouite
    WHERE ut.idTag IN
          (SELECT idTag FROM EtreAboTag eat INNER JOIN Utilisateur u ON u.emailUt= eat.emailUt WHERE u.username LIKE username)
    ORDER BY t.notePertinence DESC
    LIMIT nbTouiteParPage OFFSET inf;
end;

-- obtenirUsername renvoie l'username de l'utilisateur ayant envoyé le touite avec l'id en paramètre
create
    definer = root@`%` procedure obtenirUsername(IN v_idtouite int)
BEGIN
    select u.username
    from Touite t inner join PublierPar p on t.idTouite = p.idTouite
                  inner join Utilisateur u on p.emailUt = u.emailUt
    where t.idTouite=v_idtouite;
end;

-- obtenirUtilisateurAbo renvoie les infos des abonnés de l'utilisateur en paramètre
create procedure obtenirUtilisateurAbo(IN emailUtilisateur varchar(150))
select u.nomUt, u.prenomUt, u.username, u.dateInscription, u.emailUt, u.permissions, aUt.dateAboUt
from EtreAboUtilisateur aUt inner join Utilisateur u on aUt.emailUt=u.emailUt
where aUt.emailUtAbo = emailUtilisateur
ORDER BY u.username;

-- sabonnerTag permet d'abonner l'email donné au tag donné
create procedure sabonnerTag(IN mail1 text, IN v_idTag int)
BEGIN
    INSERT INTO EtreAboTag(emailUt, idTag, dateAboTag) VALUES (mail1, v_idTag, now());
END;

-- sabonnerUtilisateur permet d'abonner l'email1 au mail2
create procedure sabonnerUtilisateur(IN mail1 text, IN mail2 text)
BEGIN
    INSERT INTO EtreAboUtilisateur(emailUt, emailUtAbo, dateAboUt) VALUES (mail1, mail2, now());
END;

-- supprimerTouite supprime le touite et tous ses liens (tags, image, publierPar et vote)
create procedure supprimerTouite(IN v_idTouite int)
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

-- verifierUsernameInAbonnement pareil que etreAboUtilisateur mais en username
create procedure verifierUsernameInAbonnement(IN v_username varchar(150), IN usernameCible varchar(150))
BEGIN
    SELECT COUNT(*) AS nb_ligne FROM EtreAboUtilisateur ea
                                         INNER JOIN Utilisateur u ON u.emailUt=ea.emailUt
                                         INNER JOIN Utilisateur uC ON uC.emailUt=ea.emailUtAbo
    WHERE u.username=v_username AND uC.username=usernameCible;
end;

-- voir permet d'incrémenter le nombre de vue d'un touite
create procedure voir(IN v_idTouite int)
BEGIN
    UPDATE Touite
    SET nbVue = nbVue+1
    WHERE idTouite=v_idTouite;
END;

-- voter permet à un mail de voter un touite avec une note défini (1 ou 0 (Liker ou Disliker))
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




-- Incrémenter vue
CREATE PROCEDURE voir(v_idTouite INT)
BEGIN
    UPDATE Touite
    SET nbVue = nbVue+1
    WHERE idTouite=v_idTouite;
END;