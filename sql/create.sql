-- Tout en un (Suppression, Création, Insertion)
DROP TABLE IF EXISTS PublierPar;
DROP TABLE IF EXISTS UtiliserTag;
DROP TABLE IF EXISTS UtiliserImage;
DROP TABLE IF EXISTS EtreAboTag;
DROP TABLE IF EXISTS EtreAboUtilisateur;
DROP TABLE IF EXISTS Touite;
DROP TABLE IF EXISTS Image;
DROP TABLE IF EXISTS Tag;
DROP TABLE IF EXISTS Utilisateur;

-- Structure
CREATE TABLE Utilisateur (
                             emailUt VARCHAR(150) PRIMARY KEY,
                             nomUt VARCHAR(50) DEFAULT NULL,
                             prenomUt VARCHAR(50) DEFAULT NULL,
                             username VARCHAR(50) UNIQUE NOT NULL,
                             mdp VARCHAR(100) NOT NULL,
                             permissions VARCHAR(20) DEFAULT 'registered' -- admin, registered
);

CREATE TABLE Tag (
                     idTag INT PRIMARY KEY,
                     libelle VARCHAR(50) NOT NULL,
                     descriptionTag VARCHAR(500)
);

CREATE TABLE Image (
                       idImage INT PRIMARY KEY,
                       descriptionImg VARCHAR(500),
                       cheminSrc VARCHAR(250) NOT NULL
);

CREATE TABLE Touite (
                        idTouite INT PRIMARY KEY,
                        texte VARCHAR(235),-- Un Touite est limité à 235 caractères selon l'énoncé
                        date DATE NOT NULL,
                        notePertinence INT DEFAULT 0,
                        nbLike INT DEFAULT 0,
                        nbDislike INT DEFAULT 0,
                        nbRetouite INT DEFAULT 0,
                        nbVue INT DEFAULT 0
);

CREATE TABLE EtreAboUtilisateur (
                                    emailUt VARCHAR(150),
                                    emailUtAbo VARCHAR(150),
                                    PRIMARY KEY (emailUt, emailUtAbo),
                                    FOREIGN KEY (emailUt) REFERENCES Utilisateur(emailUt),
                                    FOREIGN KEY (emailUtAbo) REFERENCES Utilisateur(emailUt)
);

CREATE TABLE EtreAboTag (
                            emailUt VARCHAR(150),
                            idTag INT,
                            PRIMARY KEY (emailUt, idTag),
                            FOREIGN KEY (emailUt) REFERENCES Utilisateur(emailUt),
                            FOREIGN KEY (idTag) REFERENCES Tag(idTag)
);

CREATE TABLE UtiliserImage (
                               idTouite INT,
                               idImage INT,
                               PRIMARY KEY (idTouite, idImage),
                               FOREIGN KEY (idTouite) REFERENCES Touite(idTouite),
                               FOREIGN KEY (idImage) REFERENCES Image(idImage)
);

CREATE TABLE UtiliserTag (
                             idTouite INT,
                             idTag INT,
                             PRIMARY KEY (idTouite, idTag),
                             FOREIGN KEY (idTouite) REFERENCES Touite(idTouite),
                             FOREIGN KEY (idTag) REFERENCES Tag(idTag)
);

CREATE TABLE PublierPar (
                            idTouite INT,
                            emailUt VARCHAR(150),
                            PRIMARY KEY (idTouite, emailUt),
                            FOREIGN KEY (idTouite) REFERENCES Touite(idTouite),
                            FOREIGN KEY (emailUt) REFERENCES Utilisateur(emailUt)
);

-- Data
INSERT INTO Utilisateur (emailUt, nomUt, prenomUt, username, mdp, permissions) VALUES ('jane.doe@example.com', 'Doe', 'Jane', 'Jany', '$2y$12$0IdkqBJBR6pDDSpsVxdFN.91rZ//1ZT9qEG.r/CZIg1', 'admin'); # password1
INSERT INTO Utilisateur (emailUt, nomUt, prenomUt, username, mdp, permissions) VALUES ('bob.smith@example.com', 'Smith', 'Bob', 'Bobby', '$2y$12$2aYwGKJVWe7jEDFzyelZX.K.czD3uVtlaqOC4iFCqdY', 'registered'); # test12
INSERT INTO Utilisateur (emailUt, nomUt, prenomUt, username, mdp, permissions) VALUES ('alice.johnson@example.com', 'Johnson', 'Alice', 'AlJ', '$2y$12$wZcVDMJZ4ZEXDNLMg28jBubqz.8sc4PsRrWWaeN3PsT556NpOBfH2', 'registered'); # Alice123!
INSERT INTO Utilisateur (emailUt, nomUt, prenomUt, username, mdp, permissions) VALUES ('charlie.brown@example.com', 'Brown', 'Charlie','Charliette', '$2y$12$.qUMjSN4uitSOISSlqKe7.xhXAspEpRuui.SZ3OMGR3TlkrH5sZ0K', 'registered'); # MonMotDePasse
INSERT INTO Utilisateur (emailUt, nomUt, prenomUt, username, mdp, permissions) VALUES ('john.doe@example.com', 'Doe', 'John', 'JohnDoeee', '$2y$12$URpt9yu6CNbA6m4k4.MwF.ZNnuBnv0DK6sGl67WJDUh', 'admin'); # password

INSERT INTO Tag (idTag, libelle, descriptionTag) VALUES (1, '#Touiteur', 'Un tag pour les touites sur Touiteur');
INSERT INTO Tag (idTag, libelle, descriptionTag) VALUES (2, '#Nouveau', 'Un tag pour les nouveaux touites');
INSERT INTO Tag (idTag, libelle, descriptionTag) VALUES (3, '#Ancien', 'Un tag pour les anciens touites');

INSERT INTO Image (idImage, descriptionImg, cheminSrc) VALUES (1, 'Une image de profil', '/chemin/vers/image.jpg');
INSERT INTO Image (idImage, descriptionImg, cheminSrc) VALUES (2, 'Une autre image de profil', '/chemin/vers/autre_image.jpg');
INSERT INTO Image (idImage, descriptionImg, cheminSrc) VALUES (3, 'Une image de paysage', '/chemin/vers/paysage.jpg');

INSERT INTO Touite (idTouite, texte, date) VALUES (1, 'Bonjour Touiteur !', '2023-11-06');
INSERT INTO Touite (idTouite, texte, date) VALUES (2, 'Salut Touiteur !', '2023-11-07');
INSERT INTO Touite (idTouite, texte, date) VALUES (3, 'Bonjour à tous sur Touiteur !', '2023-11-08');
INSERT INTO Touite (idTouite, texte, date) VALUES (4, 'Bonne nuit, Touiteur !', '2023-11-09');
INSERT INTO Touite (idTouite, texte, date) VALUES (5, 'Salut tout le monde sur Touiteur !', '2023-11-10');

INSERT INTO EtreAboUtilisateur (emailUt, emailUtAbo) VALUES ('john.doe@example.com', 'jane.doe@example.com');
INSERT INTO EtreAboUtilisateur (emailUt, emailUtAbo) VALUES ('jane.doe@example.com', 'john.doe@example.com');
INSERT INTO EtreAboUtilisateur (emailUt, emailUtAbo) VALUES ('bob.smith@example.com', 'jane.doe@example.com');
INSERT INTO EtreAboUtilisateur (emailUt, emailUtAbo) VALUES ('alice.johnson@example.com', 'jane.doe@example.com');
INSERT INTO EtreAboUtilisateur (emailUt, emailUtAbo) VALUES ('charlie.brown@example.com', 'bob.smith@example.com');

INSERT INTO EtreAboTag (emailUt, idTag) VALUES ('john.doe@example.com', 1);
INSERT INTO EtreAboTag (emailUt, idTag) VALUES ('jane.doe@example.com', 2);
INSERT INTO EtreAboTag (emailUt, idTag) VALUES ('bob.smith@example.com', 1);
INSERT INTO EtreAboTag (emailUt, idTag) VALUES ('alice.johnson@example.com', 3);
INSERT INTO EtreAboTag (emailUt, idTag) VALUES ('charlie.brown@example.com', 2);

INSERT INTO UtiliserImage (idTouite, idImage) VALUES (1, 1);
INSERT INTO UtiliserImage (idTouite, idImage) VALUES (2, 2);
INSERT INTO UtiliserImage (idTouite, idImage) VALUES (3, 1);
INSERT INTO UtiliserImage (idTouite, idImage) VALUES (4, 3);
INSERT INTO UtiliserImage (idTouite, idImage) VALUES (5, 2);

INSERT INTO UtiliserTag (idTouite, idTag) VALUES (1, 1);
INSERT INTO UtiliserTag (idTouite, idTag) VALUES (2, 2);
INSERT INTO UtiliserTag (idTouite, idTag) VALUES (3, 1);
INSERT INTO UtiliserTag (idTouite, idTag) VALUES (4, 3);
INSERT INTO UtiliserTag (idTouite, idTag) VALUES (5, 2);

INSERT INTO PublierPar (idTouite, emailUt) VALUES (1, 'john.doe@example.com');
INSERT INTO PublierPar (idTouite, emailUt) VALUES (2, 'jane.doe@example.com');
INSERT INTO PublierPar (idTouite, emailUt) VALUES (3, 'bob.smith@example.com');
INSERT INTO PublierPar (idTouite, emailUt) VALUES (4, 'alice.johnson@example.com');
INSERT INTO PublierPar (idTouite, emailUt) VALUES (5, 'charlie.brown@example.com');


COMMIT;