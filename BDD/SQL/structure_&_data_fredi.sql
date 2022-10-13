#------------------------------------------------------------
#        Script MySQL.
#------------------------------------------------------------


#------------------------------------------------------------
# Table: LIGUE
#------------------------------------------------------------

CREATE TABLE LIGUE(
        idligue  Int  Auto_increment  NOT NULL ,
        libligue Varchar (25) NOT NULL ,
        libsport Varchar (25) NOT NULL
	,CONSTRAINT LIGUE_PK PRIMARY KEY (idligue)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: CLUB
#------------------------------------------------------------

CREATE TABLE CLUB(
        idclub         Int  Auto_increment  NOT NULL ,
        libclub        Varchar (25) NOT NULL ,
        adrpostaleclub Varchar (50) NOT NULL ,
        idligue        Int NOT NULL
	,CONSTRAINT CLUB_PK PRIMARY KEY (idclub)

	,CONSTRAINT CLUB_LIGUE_FK FOREIGN KEY (idligue) REFERENCES LIGUE(idligue)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: UTILISATEUR
#------------------------------------------------------------

CREATE TABLE UTILISATEUR(
        idutil       Int  Auto_increment  NOT NULL ,
        pseudoutil   Varchar (25) NOT NULL ,
        mdputil      Varchar (150) NOT NULL ,
        mailutil     Varchar (150) NOT NULL ,
        nomutil      Varchar (25) NOT NULL ,
        prenomutil   Varchar (25) NOT NULL ,
        isadmin      Bool NOT NULL ,
        iscontroleur Bool NOT NULL
	,CONSTRAINT UTILISATEUR_PK PRIMARY KEY (idutil)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: ADHERENT
#------------------------------------------------------------

CREATE TABLE ADHERENT(
        idutil       Int NOT NULL ,
        idadherent   Int  Auto_increment  NOT NULL ,
        numls        Varchar (50) NOT NULL ,
        adresseadh   Varchar (150) NOT NULL ,
        pseudoutil   Varchar (25) NOT NULL ,
        mdputil      Varchar (150) NOT NULL ,
        mailutil     Varchar (150) NOT NULL ,
        nomutil      Varchar (25) NOT NULL ,
        prenomutil   Varchar (25) NOT NULL ,
        isadmin      Bool NOT NULL ,
        iscontroleur Bool NOT NULL ,
        idclub       Int NOT NULL
	,CONSTRAINT ADHERENT_PK PRIMARY KEY (idutil)

	,CONSTRAINT ADHERENT_UTILISATEUR_FK FOREIGN KEY (idutil) REFERENCES UTILISATEUR(idutil)
	,CONSTRAINT ADHERENT_CLUB0_FK FOREIGN KEY (idclub) REFERENCES CLUB(idclub)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: PERIODE
#------------------------------------------------------------

CREATE TABLE PERIODE(
        idperiode          Int  Auto_increment  NOT NULL ,
        isactive           Bool NOT NULL ,
        libperiode         Varchar (25) NOT NULL ,
        montantforfaitaire Decimal NOT NULL ,
        libforfait         Varchar (25) NOT NULL ,
        prixforfait        Decimal NOT NULL
	,CONSTRAINT PERIODE_PK PRIMARY KEY (idperiode)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: NOTE
#------------------------------------------------------------

CREATE TABLE NOTE(
        idnote      Int  Auto_increment  NOT NULL ,
        indvalidite Varchar (25) NOT NULL ,
        dateremise  Date NOT NULL ,
        numremise   Int  Auto_increment  NOT NULL ,
        idutil      Int NOT NULL ,
        idperiode   Int NOT NULL
	,CONSTRAINT NOTE_PK PRIMARY KEY (idnote)

	,CONSTRAINT NOTE_ADHERENT_FK FOREIGN KEY (idutil) REFERENCES ADHERENT(idutil)
	,CONSTRAINT NOTE_PERIODE0_FK FOREIGN KEY (idperiode) REFERENCES PERIODE(idperiode)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: MOTIF
#------------------------------------------------------------

CREATE TABLE MOTIF(
        idmotif  Int  Auto_increment  NOT NULL ,
        libmotif Varchar (25) NOT NULL
	,CONSTRAINT MOTIF_PK PRIMARY KEY (idmotif)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: LIGNE
#------------------------------------------------------------

CREATE TABLE LIGNE(
        idligne       Int  Auto_increment  NOT NULL ,
        libdep        Varchar (25) NOT NULL ,
        datedep       Date NOT NULL ,
        motifdep      Varchar (25) NOT NULL ,
        fraispeage    Datetime NOT NULL ,
        kmparcourus   Decimal NOT NULL ,
        fraisrepas    Decimal NOT NULL ,
        fraislogement Decimal NOT NULL ,
        idnote        Int NOT NULL ,
        idmotif       Int NOT NULL
	,CONSTRAINT LIGNE_PK PRIMARY KEY (idligne)

	,CONSTRAINT LIGNE_NOTE_FK FOREIGN KEY (idnote) REFERENCES NOTE(idnote)
	,CONSTRAINT LIGNE_MOTIF0_FK FOREIGN KEY (idmotif) REFERENCES MOTIF(idmotif)
)ENGINE=InnoDB;

-- Ligues
INSERT INTO ligue (id_ligue, lib_ligue)
VALUES 
(1, 'Football'),
(2, 'Handball'),
(3, 'Golf'),
(4, 'Judo'),
(5, 'Rugby');

-- Club
INSERT INTO  club (id_club, lib_club, id_ligue)
VALUES
(1, 'Club de Foot de Nancy', 1),
(2, 'Club de Foot de Metz', 1),
(3, 'Club de Handball de Lunéville', 2),
(4, 'Club de Handball de Epinal', 2),
(5, 'Club de Golf de Verdun', 3),
(6, 'Club de Golf de Longwy', 3),
(7, 'Club de Judo de ThionVille', 4),
(8, 'Club de Judo de bar-le-Duc', 4),
(9, 'Club de Rugby de Yutz', 5),
(10, 'Club de Rugby de Bitche', 5);

-- Utilisateurs
INSERT INTO  utilisateur (id_utilisateur, pseudo, mdp, mail, nom, prenom, role)
VALUES 
(1, 'Darksasuke', 'Q8h6ntA5BVHPwjd', 'Darksasuke@gmail.com', 'Richard', 'Cuterrie', 1),
(2, 'Poney123', 'xiYaaVhauuBj4Oc', 'Poney123@gmail.com', 'Henry', 'Car', 1),
(3, 'DocteurSol', 'OrpSP3hdwJVJuF6', 'DocteurSol@gmail.com', 'Khaoutar', 'Tiflette', 1),
(4, 'SISRcNUL', '5gcFm9XbyJ6sNqP', 'SISRcNUL@gmail.com', 'Jean', 'Bonbeur', 2),
(5, 'JaimelaM2L', 'TKX90MBx8c1bMi4', 'JaimelaM2L@gmail.com', 'Ibrhima', 'Carronie', 1),
(6, 'FouBrave', '5cYqqgMn3unc9tA', 'FouBrave@gmail.com', 'Angélica', 'Kahuète', 1),
(7, 'GoodRiku', 'OBZvDPjCrNMVrfw', 'GoodRiku@gmail.com', 'Oscar', 'Got', 2),
(8, 'CookieVif', 'jsA05o8nN80NJuS', 'CookieVif@gmail.com', 'Vladimir', 'Aclette', 1);

-- Adherent
INSERT INTO  adherent (id_adherent, nr_licence, adr1, adr2, adr3, id_utilisateur, id_club)
VALUES
(10, 'L654', 'Adresse1', 'Adresse2', 'Adresse3', 1, 2),
(11, 'L987', 'Adresse1', 'Adresse2', 'Adresse3', 2, 3),
(12, 'L665', 'Adresse1', 'Adresse2', 'Adresse3', 3, 5),
(13, 'L689', 'Adresse1', 'Adresse2', 'Adresse3', 4, 6),
(14, 'L682', 'Adresse1', 'Adresse2', 'Adresse3', 5, 8),
(15, 'L892', 'Adresse1', 'Adresse2', 'Adresse3', 6, 5),
(16, 'L836', 'Adresse1', 'Adresse2', 'Adresse3', 7, 4),
(17, 'L368', 'Adresse1', 'Adresse2', 'Adresse3', 8, 7);

-- Motifs
INSERT INTO  motif (id_motif, lib_motif)
VALUES
(10, 'Motif 1'),
(11, 'Motif 2'),
(12, 'Motif 3'),
(13, 'Motif 4'),
(14, 'Motif 5'),
(15, 'Motif 6'),
(16, 'Motif 7'),
(17, 'Motif 8');

-- Periode
INSERT INTO  periode (id_periode, lib_periode, est_active, mt_km)
VALUES
(1, 'Période 1', 1, 1.752),
(2, 'Période 2', 0, 1.85),
(3, 'Période 3', 1, 1.782),
(4, 'Période 4', 0, 1.72),
(5, 'Période 5', 1, 1.78),
(6, 'Période 6', 1, 1.7278),
(7, 'Période 7', 0, 1.728),
(8, 'Période 8', 1, 1.272);

-- Note
INSERT INTO  note (id_note, est_valide, mt_total, dat_remise, nr_ordre, id_periode, id_utilisateur)
VALUES
(1, 0, 725.75, '2022-06-25', 4, 7, 5),
(2, 1, 752.68, '2022-09-14', 6, 6, 4),
(3, 0, 575.95, '2022-08-25', 5, 5, 3),
(4, 1, 727.85, '2022-05-30', 8, 4, 2),
(5, 1, 525.23, '2022-06-14', 7, 3, 6),
(6, 0, 752.56, '2022-04-25', 5, 2, 7),
(7, 1, 877.75, '2022-07-15', 8, 1, 8),
(8, 1, 654.78, '2022-05-25', 5, 8, 1);

-- Ligne
INSERT INTO  ligne (id_ligne, dat_ligne, lib_trajet, nb_km, mt_km, mt_peage, mt_repas, mt_hebergement, mt_total, id_motif, id_note)
VALUES 
(1, '2022-06-25', 'Trajet 1', 254, 1.752, 1.752, 1.752, 1.752, 1.752, 10, 1),
(2, '2022-09-14', 'Trajet 2', 146, 1.85, 1.85, 1.85, 1.85, 1.85, 11, 2),
(3, '2022-08-25', 'Trajet 3', 255, 1.782, 1.782, 1.782, 1.782, 1.782, 12, 3),
(4, '2022-05-30', 'Trajet 4', 308, 1.72, 1.72, 1.72, 1.72, 1.72, 13, 4),
(5, '2022-06-14', 'Trajet 5', 147, 1.78, 1.78, 1.78, 1.78, 1.78, 14, 5),
(6, '2022-04-25', 'Trajet 6', 255, 1.7278, 1.7278, 1.7278, 1.7278, 1.7278, 15, 6),
(7, '2022-07-15', 'Trajet 7', 158, 1.728, 1.728, 1.728, 1.728, 1.728, 16, 7),
(8, '2022-05-25', 'Trajet 8', 255, 1.272, 1.272, 1.272, 1.272, 1.272, 17, 8);