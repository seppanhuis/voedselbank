-- ***************************************************************
-- Database: VoedselbankDB
-- ***************************************************************

DROP DATABASE IF EXISTS `voedselbank`;
CREATE DATABASE `voedselbank`;
USE `voedselbank`;

-- ***************************************************************
-- TABEL: Categorie
-- ***************************************************************

CREATE TABLE Categorie
(
     Id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT
    ,Naam VARCHAR(100) NOT NULL
    ,IsActief BIT NOT NULL DEFAULT 1
    ,Opmerking VARCHAR(250) NULL
    ,DatumAangemaakt DATETIME(6) NOT NULL
    ,DatumGewijzigd DATETIME(6) NOT NULL
    ,CONSTRAINT PK_Categorie PRIMARY KEY (Id)
) ENGINE=InnoDB;

INSERT INTO Categorie (Naam, DatumAangemaakt, DatumGewijzigd) VALUES
 ('Groente en fruit', SYSDATE(6), SYSDATE(6)),
 ('Zuivel', SYSDATE(6), SYSDATE(6)),
 ('Dranken', SYSDATE(6), SYSDATE(6)),
 ('Snacks', SYSDATE(6), SYSDATE(6)),
 ('Hygiëne', SYSDATE(6), SYSDATE(6));

-- ***************************************************************
-- TABEL: Leverancier
-- ***************************************************************

CREATE TABLE Leverancier
(
     Id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT
    ,Bedrijfsnaam VARCHAR(100) NOT NULL
    ,Adres VARCHAR(150) NOT NULL
    ,Contactpersoon VARCHAR(100) NOT NULL
    ,Email VARCHAR(100) NOT NULL
    ,Telefoon VARCHAR(20) NOT NULL
    ,VolgendeLevering DATETIME NOT NULL
    ,IsActief BIT NOT NULL DEFAULT 1
    ,Opmerking VARCHAR(250) NULL
    ,DatumAangemaakt DATETIME(6) NOT NULL
    ,DatumGewijzigd DATETIME(6) NOT NULL
    ,CONSTRAINT PK_Leverancier PRIMARY KEY (Id)
) ENGINE=InnoDB;

INSERT INTO Leverancier (Bedrijfsnaam, Adres, Contactpersoon, Email, Telefoon, VolgendeLevering, DatumAangemaakt, DatumGewijzigd) VALUES
 ('Jumbo','Straat 1','Jan','jan@jumbo.nl','0611111111',SYSDATE(),SYSDATE(6),SYSDATE(6)),
 ('AH','Straat 2','Piet','piet@ah.nl','0622222222',SYSDATE(),SYSDATE(6),SYSDATE(6)),
 ('Lidl','Straat 3','Klaas','klaas@lidl.nl','0633333333',SYSDATE(),SYSDATE(6),SYSDATE(6)),
 ('Boer BV','Straat 4','Henk','henk@boer.nl','0644444444',SYSDATE(),SYSDATE(6),SYSDATE(6)),
 ('Makro','Straat 5','Sara','sara@makro.nl','0655555555',SYSDATE(),SYSDATE(6),SYSDATE(6));

-- ***************************************************************
-- TABEL: Product
-- ***************************************************************

CREATE TABLE Product
(
     Id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT
    ,Naam VARCHAR(100) NOT NULL UNIQUE
    ,EAN VARCHAR(13) NOT NULL UNIQUE
    ,Aantal INT NOT NULL
    ,CategorieId SMALLINT UNSIGNED NOT NULL
    ,LeverancierId SMALLINT UNSIGNED NOT NULL
    ,IsActief BIT NOT NULL DEFAULT 1
    ,Opmerking VARCHAR(250) NULL
    ,DatumAangemaakt DATETIME(6) NOT NULL
    ,DatumGewijzigd DATETIME(6) NOT NULL
    ,CONSTRAINT PK_Product PRIMARY KEY (Id)
    ,CONSTRAINT FK_Product_Categorie FOREIGN KEY (CategorieId) REFERENCES Categorie(Id)
    ,CONSTRAINT FK_Product_Leverancier FOREIGN KEY (LeverancierId) REFERENCES Leverancier(Id)
) ENGINE=InnoDB;

INSERT INTO Product (Naam, EAN, Aantal, CategorieId, LeverancierId, DatumAangemaakt, DatumGewijzigd) VALUES
 ('Appel','1111111111111',100,1,1,SYSDATE(6),SYSDATE(6)),
 ('Melk','2222222222222',50,2,2,SYSDATE(6),SYSDATE(6)),
 ('Cola','3333333333333',80,3,3,SYSDATE(6),SYSDATE(6)),
 ('Chips','4444444444444',60,4,4,SYSDATE(6),SYSDATE(6)),
 ('Shampoo','5555555555555',40,5,5,SYSDATE(6),SYSDATE(6));

-- ***************************************************************
-- TABEL: Klant
-- ***************************************************************

CREATE TABLE Klant
(
     Id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT
    ,Naam VARCHAR(100) NOT NULL
    ,Adres VARCHAR(150) NOT NULL
    ,Telefoon VARCHAR(20) NOT NULL
    ,Email VARCHAR(100) NOT NULL
    ,Volwassenen TINYINT NOT NULL
    ,Kinderen TINYINT NOT NULL
    ,Babys TINYINT NOT NULL
    ,IsActief BIT NOT NULL DEFAULT 1
    ,Opmerking VARCHAR(250) NULL
    ,DatumAangemaakt DATETIME(6) NOT NULL
    ,DatumGewijzigd DATETIME(6) NOT NULL
    ,CONSTRAINT PK_Klant PRIMARY KEY (Id)
) ENGINE=InnoDB;

INSERT INTO Klant (Naam, Adres, Telefoon, Email, Volwassenen, Kinderen, Babys, DatumAangemaakt, DatumGewijzigd) VALUES
 ('Jansen','Straat 10','0611111111','jansen@mail.nl',2,2,1,SYSDATE(6),SYSDATE(6)),
 ('Pieters','Straat 11','0622222222','piet@mail.nl',2,1,0,SYSDATE(6),SYSDATE(6)),
 ('Klaas','Straat 12','0633333333','klaas@mail.nl',1,2,0,SYSDATE(6),SYSDATE(6)),
 ('Henk','Straat 13','0644444444','henk@mail.nl',2,0,0,SYSDATE(6),SYSDATE(6)),
 ('Sara','Straat 14','0655555555','sara@mail.nl',1,1,1,SYSDATE(6),SYSDATE(6));

-- ***************************************************************
-- TABEL: Wens
-- ***************************************************************

CREATE TABLE Wens
(
     Id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT
    ,Omschrijving VARCHAR(100) NOT NULL
    ,IsActief BIT NOT NULL DEFAULT 1
    ,Opmerking VARCHAR(250) NULL
    ,DatumAangemaakt DATETIME(6) NOT NULL
    ,DatumGewijzigd DATETIME(6) NOT NULL
    ,CONSTRAINT PK_Wens PRIMARY KEY (Id)
) ENGINE=InnoDB;

INSERT INTO Wens (Omschrijving, DatumAangemaakt, DatumGewijzigd) VALUES
 ('Geen varkensvlees',SYSDATE(6),SYSDATE(6)),
 ('Gluten allergie',SYSDATE(6),SYSDATE(6)),
 ('Vegan',SYSDATE(6),SYSDATE(6)),
 ('Vegetarisch',SYSDATE(6),SYSDATE(6)),
 ('Lactose intolerant',SYSDATE(6),SYSDATE(6));

-- ***************************************************************
-- TABEL: KlantWens
-- ***************************************************************

CREATE TABLE KlantWens
(
     Id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT
    ,KlantId SMALLINT UNSIGNED NOT NULL
    ,WensId SMALLINT UNSIGNED NOT NULL
    ,IsActief BIT NOT NULL DEFAULT 1
    ,Opmerking VARCHAR(250) NULL
    ,DatumAangemaakt DATETIME(6) NOT NULL
    ,DatumGewijzigd DATETIME(6) NOT NULL
    ,CONSTRAINT PK_KlantWens PRIMARY KEY (Id)
    ,CONSTRAINT FK_KlantWens_Klant FOREIGN KEY (KlantId) REFERENCES Klant(Id)
    ,CONSTRAINT FK_KlantWens_Wens FOREIGN KEY (WensId) REFERENCES Wens(Id)
) ENGINE=InnoDB;

-- ***************************************************************
-- TABEL: Voedselpakket
-- ***************************************************************

CREATE TABLE Voedselpakket
(
     Id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT
    ,KlantId SMALLINT UNSIGNED NOT NULL
    ,DatumSamenstelling DATE NOT NULL
    ,DatumUitgifte DATE NULL
    ,IsActief BIT NOT NULL DEFAULT 1
    ,Opmerking VARCHAR(250) NULL
    ,DatumAangemaakt DATETIME(6) NOT NULL
    ,DatumGewijzigd DATETIME(6) NOT NULL
    ,CONSTRAINT PK_Voedselpakket PRIMARY KEY (Id)
    ,CONSTRAINT FK_Pakket_Klant FOREIGN KEY (KlantId) REFERENCES Klant(Id)
) ENGINE=InnoDB;

INSERT INTO Voedselpakket (KlantId, DatumSamenstelling, DatumUitgifte, DatumAangemaakt, DatumGewijzigd) VALUES
 (1,DATE(SYSDATE()),DATE(SYSDATE()),SYSDATE(6),SYSDATE(6)),
 (2,DATE(SYSDATE()),DATE(SYSDATE()),SYSDATE(6),SYSDATE(6)),
 (3,DATE(SYSDATE()),DATE(SYSDATE()),SYSDATE(6),SYSDATE(6)),
 (4,DATE(SYSDATE()),DATE(SYSDATE()),SYSDATE(6),SYSDATE(6)),
 (5,DATE(SYSDATE()),DATE(SYSDATE()),SYSDATE(6),SYSDATE(6));

-- ***************************************************************
-- TABEL: PakketProduct
-- ***************************************************************

CREATE TABLE PakketProduct
(
     Id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT
    ,PakketId SMALLINT UNSIGNED NOT NULL
    ,ProductId SMALLINT UNSIGNED NOT NULL
    ,Aantal INT NOT NULL
    ,IsActief BIT NOT NULL DEFAULT 1
    ,Opmerking VARCHAR(250) NULL
    ,DatumAangemaakt DATETIME(6) NOT NULL
    ,DatumGewijzigd DATETIME(6) NOT NULL
    ,CONSTRAINT PK_PakketProduct PRIMARY KEY (Id)
    ,CONSTRAINT FK_PP_Pakket FOREIGN KEY (PakketId) REFERENCES Voedselpakket(Id)
    ,CONSTRAINT FK_PP_Product FOREIGN KEY (ProductId) REFERENCES Product(Id)
) ENGINE=InnoDB;
