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
        idadherent   Int NOT NULL ,
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
        numremise   Int NOT NULL ,
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

