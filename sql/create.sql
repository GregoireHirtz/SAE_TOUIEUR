-- Tout en un (Suppression, Création, Insertion)
-- Tout en un (Suppression, Création, Insertion)
DROP TABLE IF EXISTS PublicationAuteur;
DROP TABLE IF EXISTS TouiteContenirTag;
DROP TABLE IF EXISTS TouiteContenirImage;
DROP TABLE IF EXISTS AbonnementTag;
DROP TABLE IF EXISTS AbonnementUtilisateur;
DROP TABLE IF EXISTS Touite;
DROP TABLE IF EXISTS Image;
DROP TABLE IF EXISTS Tag;
DROP TABLE IF EXISTS Utilisateur;

CREATE TABLE Utilisateur (
                             emailUt VARCHAR(150) PRIMARY KEY,
                             nomUt VARCHAR(50),
                             prenomUt VARCHAR(50)
);

CREATE TABLE Tag (
                     idTag INT PRIMARY KEY,
                     libelle VARCHAR(50),
                     descriptionTag VARCHAR(500)
);

CREATE TABLE Image (
                       idImage INT PRIMARY KEY,
                       descriptionImg VARCHAR(500),
                       cheminSrc VARCHAR(250)
);

CREATE TABLE Touite (
                        idTouite INT PRIMARY KEY,
                        texte VARCHAR(235),-- Un Touite est limité à 235 caractères selon l'énoncé
                        date DATE,
                        notePertinence INT
);

CREATE TABLE AbonnementUtilisateur (
                                       emailUt VARCHAR(150),
                                       emailUtAbo VARCHAR(150),
                                       PRIMARY KEY (emailUt, emailUtAbo),
                                       FOREIGN KEY (emailUt) REFERENCES Utilisateur(emailUt),
                                       FOREIGN KEY (emailUtAbo) REFERENCES Utilisateur(emailUt)
);

CREATE TABLE AbonnementTag (
                               emailUt VARCHAR(150),
                               idTag INT,
                               PRIMARY KEY (emailUt, idTag),
                               FOREIGN KEY (emailUt) REFERENCES Utilisateur(emailUt),
                               FOREIGN KEY (idTag) REFERENCES Tag(idTag)
);

CREATE TABLE TouiteContenirImage (
                                     idTouite INT,
                                     idImage INT,
                                     PRIMARY KEY (idTouite, idImage),
                                     FOREIGN KEY (idTouite) REFERENCES Touite(idTouite),
                                     FOREIGN KEY (idImage) REFERENCES Image(idImage)
);

CREATE TABLE TouiteContenirTag (
                                   idTouite INT,
                                   idTag INT,
                                   PRIMARY KEY (idTouite, idTag),
                                   FOREIGN KEY (idTouite) REFERENCES Touite(idTouite),
                                   FOREIGN KEY (idTag) REFERENCES Tag(idTag)
);

CREATE TABLE PublicationAuteur (
                                   idTouite INT,
                                   emailUt VARCHAR(150),
                                   PRIMARY KEY (idTouite, emailUt),
                                   FOREIGN KEY (idTouite) REFERENCES Touite(idTouite),
                                   FOREIGN KEY (emailUt) REFERENCES Utilisateur(emailUt)
);

INSERT INTO Utilisateur (emailUt, nomUt, prenomUt) VALUES ('jane.doe@example.com', 'Doe', 'Jane');
INSERT INTO Utilisateur (emailUt, nomUt, prenomUt) VALUES ('bob.smith@example.com', 'Smith', 'Bob');
INSERT INTO Utilisateur (emailUt, nomUt, prenomUt) VALUES ('alice.johnson@example.com', 'Johnson', 'Alice');
INSERT INTO Utilisateur (emailUt, nomUt, prenomUt) VALUES ('charlie.brown@example.com', 'Brown', 'Charlie');
INSERT INTO Utilisateur (emailUt, nomUt, prenomUt) VALUES ('john.doe@example.com', 'Doe', 'John');

INSERT INTO Tag (idTag, libelle, descriptionTag) VALUES (1, '#Touiteur', 'Un tag pour les touites sur Touiteur');
INSERT INTO Tag (idTag, libelle, descriptionTag) VALUES (2, '#Nouveau', 'Un tag pour les nouveaux touites');
INSERT INTO Tag (idTag, libelle, descriptionTag) VALUES (3, '#Ancien', 'Un tag pour les anciens touites');

INSERT INTO Image (idImage, descriptionImg, cheminSrc) VALUES (1, 'Une image de profil', '/chemin/vers/image.jpg');
INSERT INTO Image (idImage, descriptionImg, cheminSrc) VALUES (2, 'Une autre image de profil', '/chemin/vers/autre_image.jpg');
INSERT INTO Image (idImage, descriptionImg, cheminSrc) VALUES (3, 'Une image de paysage', '/chemin/vers/paysage.jpg');

INSERT INTO Touite (idTouite, texte, date, notePertinence) VALUES (1, 'Bonjour Touiteur !', '2023-11-06', 10);
INSERT INTO Touite (idTouite, texte, date, notePertinence) VALUES (2, 'Salut Touiteur !', '2023-11-07', 8);
INSERT INTO Touite (idTouite, texte, date, notePertinence) VALUES (3, 'Bonjour à tous sur Touiteur !', '2023-11-08', 9);
INSERT INTO Touite (idTouite, texte, date, notePertinence) VALUES (4, 'Bonne nuit, Touiteur !', '2023-11-09', 7);
INSERT INTO Touite (idTouite, texte, date, notePertinence) VALUES (5, 'Salut tout le monde sur Touiteur !', '2023-11-10', 6);

INSERT INTO AbonnementUtilisateur (emailUt, emailUtAbo) VALUES ('john.doe@example.com', 'jane.doe@example.com');
INSERT INTO AbonnementUtilisateur (emailUt, emailUtAbo) VALUES ('jane.doe@example.com', 'john.doe@example.com');
INSERT INTO AbonnementUtilisateur (emailUt, emailUtAbo) VALUES ('bob.smith@example.com', 'jane.doe@example.com');
INSERT INTO AbonnementUtilisateur (emailUt, emailUtAbo) VALUES ('alice.johnson@example.com', 'jane.doe@example.com');
INSERT INTO AbonnementUtilisateur (emailUt, emailUtAbo) VALUES ('charlie.brown@example.com', 'bob.smith@example.com');

INSERT INTO AbonnementTag (emailUt, idTag) VALUES ('john.doe@example.com', 1);
INSERT INTO AbonnementTag (emailUt, idTag) VALUES ('jane.doe@example.com', 2);
INSERT INTO AbonnementTag (emailUt, idTag) VALUES ('bob.smith@example.com', 1);
INSERT INTO AbonnementTag (emailUt, idTag) VALUES ('alice.johnson@example.com', 3);
INSERT INTO AbonnementTag (emailUt, idTag) VALUES ('charlie.brown@example.com', 2);

INSERT INTO TouiteContenirImage (idTouite, idImage) VALUES (1, 1);
INSERT INTO TouiteContenirImage (idTouite, idImage) VALUES (2, 2);
INSERT INTO TouiteContenirImage (idTouite, idImage) VALUES (3, 1);
INSERT INTO TouiteContenirImage (idTouite, idImage) VALUES (4, 3);
INSERT INTO TouiteContenirImage (idTouite, idImage) VALUES (5, 2);

INSERT INTO TouiteContenirTag (idTouite, idTag) VALUES (1, 1);
INSERT INTO TouiteContenirTag (idTouite, idTag) VALUES (2, 2);
INSERT INTO TouiteContenirTag (idTouite, idTag) VALUES (3, 1);
INSERT INTO TouiteContenirTag (idTouite, idTag) VALUES (4, 3);
INSERT INTO TouiteContenirTag (idTouite, idTag) VALUES (5, 2);

INSERT INTO PublicationAuteur (idTouite, emailUt) VALUES (1, 'john.doe@example.com');
INSERT INTO PublicationAuteur (idTouite, emailUt) VALUES (2, 'jane.doe@example.com');
INSERT INTO PublicationAuteur (idTouite, emailUt) VALUES (3, 'bob.smith@example.com');
INSERT INTO PublicationAuteur (idTouite, emailUt) VALUES (4, 'alice.johnson@example.com');
INSERT INTO PublicationAuteur (idTouite, emailUt) VALUES (5, 'charlie.brown@example.com');