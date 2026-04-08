-- Step: 01
-- *****************************************************************************************************
-- Doel : Maak de database aan voor Voedselbank Maaskantje
-- *****************************************************************************************************
-- Versie       Datum           Auteur              Omschrijving
-- ****** ***** ****** ************
-- 01           08-04-2026      sep                Compleet script met 8 tabellen en auto-timestamps
-- *****************************************************************************************************

DROP DATABASE IF EXISTS `voedselbank`;
CREATE DATABASE `voedselbank`;
USE `voedselbank`;

-- Step: 02
-- *****************************************************************************************************
-- Doel : Tabel Categorie (Beheer van productcategorieën [cite: 79, 92])
-- *****************************************************************************************************
CREATE TABLE Categorie (
      Id                  TINYINT         UNSIGNED    NOT NULL    AUTO_INCREMENT
    , Naam                VARCHAR(50)                 NOT NULL
    , IsActief            BIT                         NOT NULL    DEFAULT 1
    , Opmerking           VARCHAR(250)                    NULL    DEFAULT NULL
    , DatumAangemaakt     DATETIME(6)                 NOT NULL    DEFAULT CURRENT_TIMESTAMP(6)
    , DatumGewijzigd      DATETIME(6)                 NOT NULL    DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
    , CONSTRAINT PK_Categorie_Id PRIMARY KEY (Id)
) ENGINE=InnoDB;

INSERT INTO Categorie (Naam) VALUES
      ('Aardappelen, groente, fruit')
    , ('Kaas, vleeswaren')
    , ('Zuivel, plantaardig en eieren')
    , ('Bakkerij en banket')
    , ('Frisdrank, sappen, koffie en thee');

-- Step: 03
-- *****************************************************************************************************
-- Doel : Tabel Leverancier (Beheer van contactgegevens en leveringen [cite: 43, 44])
-- *****************************************************************************************************
CREATE TABLE Leverancier (
      Id                  SMALLINT        UNSIGNED    NOT NULL    AUTO_INCREMENT
    , Bedrijfsnaam        VARCHAR(100)                NOT NULL
    , Adres               VARCHAR(100)                NOT NULL
    , Contactnaam         VARCHAR(100)                NOT NULL
    , Email               VARCHAR(100)                NOT NULL
    , Telefoon            VARCHAR(15)                 NOT NULL
    , EerstvolgendeLevering DATETIME                      NULL
    , IsActief            BIT                         NOT NULL    DEFAULT 1
    , Opmerking           VARCHAR(250)                    NULL    DEFAULT NULL
    , DatumAangemaakt     DATETIME(6)                 NOT NULL    DEFAULT CURRENT_TIMESTAMP(6)
    , DatumGewijzigd      DATETIME(6)                 NOT NULL    DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
    , CONSTRAINT PK_Leverancier_Id PRIMARY KEY (Id)
) ENGINE=InnoDB;

INSERT INTO Leverancier (Bedrijfsnaam, Adres, Contactnaam, Email, Telefoon, EerstvolgendeLevering) VALUES
      ('Super de Boer', 'Markt 1, Maaskantje', 'Jan Janssen', 'jan@sdb.nl', '0612345678', '2026-04-10 09:00')
    , ('Boer Harm', 'Zandweg 12, Schijndel', 'Harm Bakker', 'harm@boerharm.nl', '0687654321', '2026-04-11 14:00')
    , ('Bakkerij Bol', 'Kerkstraat 4, Maaskantje', 'Piet Bol', 'info@bakkerbol.nl', '0731122334', '2026-04-10 07:30')
    , ('Sligro', 'Groothandelweg 1, Veghel', 'Klantenservice', 'contact@sligro.nl', '0413222222', '2026-04-12 10:00')
    , ('Hanos', 'Logistiekpad 5, Eindhoven', 'Beheerder', 'eindhoven@hanos.nl', '0403334445', '2026-04-15 11:00');

-- Step: 04
-- *****************************************************************************************************
-- Doel : Tabel Product (Voorraadbeheer met uniek EAN-nummer [cite: 46, 74, 75])
-- *****************************************************************************************************
CREATE TABLE Product (
      Id                  INT             UNSIGNED    NOT NULL    AUTO_INCREMENT
    , EAN                 CHAR(13)                    NOT NULL
    , Naam                VARCHAR(100)                NOT NULL
    , CategorieId         TINYINT         UNSIGNED    NOT NULL
    , AantalVoorraad      INT                         NOT NULL    DEFAULT 0
    , IsActief            BIT                         NOT NULL    DEFAULT 1
    , Opmerking           VARCHAR(250)                    NULL    DEFAULT NULL
    , DatumAangemaakt     DATETIME(6)                 NOT NULL    DEFAULT CURRENT_TIMESTAMP(6)
    , DatumGewijzigd      DATETIME(6)                 NOT NULL    DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
    , CONSTRAINT PK_Product_Id PRIMARY KEY (Id)
    , CONSTRAINT FK_Product_Categorie FOREIGN KEY (CategorieId) REFERENCES Categorie(Id)
    , CONSTRAINT UC_Product_EAN UNIQUE (EAN)
) ENGINE=InnoDB;

INSERT INTO Product (EAN, Naam, CategorieId, AantalVoorraad) VALUES
      ('8710400311234', 'Halfvolle Melk 1L', 3, 50)
    , ('8710400311567', 'Zak Appels 1kg', 1, 20)
    , ('8710400311890', 'Volkoren Brood', 4, 15)
    , ('8710400311001', 'Cola 1.5L', 5, 40)
    , ('8710400311222', 'Jong Belegen Kaas', 2, 10);

-- Step: 05
-- *****************************************************************************************************
-- Doel : Tabel Klant (Gezinssamenstelling en contactgegevens [cite: 58, 94])
-- *****************************************************************************************************
CREATE TABLE Klant (
      Id                  INT             UNSIGNED    NOT NULL    AUTO_INCREMENT
    , Gezinsnaam          VARCHAR(100)                NOT NULL
    , Adres               VARCHAR(100)                NOT NULL
    , Email               VARCHAR(100)                NOT NULL
    , Telefoon            VARCHAR(15)                 NOT NULL
    , AantalVolwassenen   TINYINT                     NOT NULL    DEFAULT 1
    , AantalKinderen      TINYINT                     NOT NULL    DEFAULT 0
    , AantalBabys         TINYINT                     NOT NULL    DEFAULT 0
    , IsActief            BIT                         NOT NULL    DEFAULT 1
    , Opmerking           VARCHAR(250)                    NULL    DEFAULT NULL
    , DatumAangemaakt     DATETIME(6)                 NOT NULL    DEFAULT CURRENT_TIMESTAMP(6)
    , DatumGewijzigd      DATETIME(6)                 NOT NULL    DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
    , CONSTRAINT PK_Klant_Id PRIMARY KEY (Id)
) ENGINE=InnoDB;

INSERT INTO Klant (Gezinsnaam, Adres, Email, Telefoon, AantalVolwassenen, AantalKinderen, AantalBabys) VALUES
      ('Vries', 'Beukenlaan 1, Maaskantje', 'vries@mail.nl', '0622334455', 2, 2, 0)
    , ('Dijkstra', 'Heuvel 5, Maaskantje', 'dijkstra@ziggo.nl', '0655667788', 1, 0, 1)
    , ('Bakker', 'Dalpad 12, Schijndel', 'bakker@outlook.com', '0633441122', 2, 3, 0)
    , ('Smit', 'Zuidweg 2, Maaskantje', 'smit@hetnet.nl', '0699887766', 2, 0, 0)
    , ('De Jong', 'Hoofdstraat 100, Maaskantje', 'dejong@live.nl', '0611112222', 1, 1, 0);

-- Step: 06
-- *****************************************************************************************************
-- Doel : Tabel Wens (Lijst met mogelijke dieetwensen/allergieën [cite: 98, 100])
-- *****************************************************************************************************
CREATE TABLE Wens (
      Id                  TINYINT         UNSIGNED    NOT NULL    AUTO_INCREMENT
    , Beschrijving        VARCHAR(50)                 NOT NULL
    , IsActief            BIT                         NOT NULL    DEFAULT 1
    , Opmerking           VARCHAR(250)                    NULL    DEFAULT NULL
    , DatumAangemaakt     DATETIME(6)                 NOT NULL    DEFAULT CURRENT_TIMESTAMP(6)
    , DatumGewijzigd      DATETIME(6)                 NOT NULL    DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
    , CONSTRAINT PK_Wens_Id PRIMARY KEY (Id)
) ENGINE=InnoDB;

INSERT INTO Wens (Beschrijving) VALUES
      ('Geen varkensvlees')
    , ('Glutenvrij')
    , ('Lactosevrij')
    , ('Veganistisch')
    , ('Vegetarisch');

-- Step: 07
-- *****************************************************************************************************
-- Doel : Koppeltabel Klant_Wens (Koppelt meerdere wensen aan één klant [cite: 103])
-- *****************************************************************************************************
CREATE TABLE Klant_Wens (
      KlantId             INT             UNSIGNED    NOT NULL
    , WensId              TINYINT         UNSIGNED    NOT NULL
    , IsActief            BIT                         NOT NULL    DEFAULT 1
    , Opmerking           VARCHAR(250)                    NULL    DEFAULT NULL
    , DatumAangemaakt     DATETIME(6)                 NOT NULL    DEFAULT CURRENT_TIMESTAMP(6)
    , DatumGewijzigd      DATETIME(6)                 NOT NULL    DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
    , CONSTRAINT PK_KlantWens PRIMARY KEY (KlantId, WensId)
    , CONSTRAINT FK_KW_Klant FOREIGN KEY (KlantId) REFERENCES Klant(Id)
    , CONSTRAINT FK_KW_Wens FOREIGN KEY (WensId) REFERENCES Wens(Id)
) ENGINE=InnoDB;

INSERT INTO Klant_Wens (KlantId, WensId) VALUES
      (1, 1)
    , (2, 3)
    , (3, 2)
    , (5, 5)
    , (1, 5);

-- Step: 08
-- *****************************************************************************************************
-- Doel : Tabel Voedselpakket (Registratie van samenstelling en uitgifte [cite: 54, 105, 107])
-- *****************************************************************************************************
CREATE TABLE Voedselpakket (
      Id                  INT             UNSIGNED    NOT NULL    AUTO_INCREMENT
    , KlantId             INT             UNSIGNED    NOT NULL
    , DatumSamenstelling  DATE                        NOT NULL
    , DatumUitgifte       DATE                            NULL
    , IsActief            BIT                         NOT NULL    DEFAULT 1
    , Opmerking           VARCHAR(250)                    NULL    DEFAULT NULL
    , DatumAangemaakt     DATETIME(6)                 NOT NULL    DEFAULT CURRENT_TIMESTAMP(6)
    , DatumGewijzigd      DATETIME(6)                 NOT NULL    DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
    , CONSTRAINT PK_Voedselpakket_Id PRIMARY KEY (Id)
    , CONSTRAINT FK_Pakket_Klant FOREIGN KEY (KlantId) REFERENCES Klant(Id)
) ENGINE=InnoDB;

INSERT INTO Voedselpakket (KlantId, DatumSamenstelling, DatumUitgifte) VALUES
      (1, '2026-04-02', '2026-04-03')
    , (2, '2026-04-02', '2026-04-03')
    , (3, '2026-04-09', NULL)
    , (4, '2026-04-09', NULL)
    , (5, '2026-04-02', '2026-04-03');

-- Step: 09
-- *****************************************************************************************************
-- Doel : Tabel Pakket_Product (Toevoegen van producten aan een pakket [cite: 61, 107, 109])
-- *****************************************************************************************************
CREATE TABLE Pakket_Product (
      VoedselpakketId     INT             UNSIGNED    NOT NULL
    , ProductId           INT             UNSIGNED    NOT NULL
    , Aantal              SMALLINT                    NOT NULL
    , IsActief            BIT                         NOT NULL    DEFAULT 1
    , Opmerking           VARCHAR(250)                    NULL    DEFAULT NULL
    , DatumAangemaakt     DATETIME(6)                 NOT NULL    DEFAULT CURRENT_TIMESTAMP(6)
    , DatumGewijzigd      DATETIME(6)                 NOT NULL    DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
    , CONSTRAINT PK_PakketProduct PRIMARY KEY (VoedselpakketId, ProductId)
    , CONSTRAINT FK_PP_Pakket FOREIGN KEY (VoedselpakketId) REFERENCES Voedselpakket(Id)
    , CONSTRAINT FK_PP_Product FOREIGN KEY (ProductId) REFERENCES Product(Id)
) ENGINE=InnoDB;

INSERT INTO Pakket_Product (VoedselpakketId, ProductId, Aantal) VALUES
      (1, 1, 2)
    , (1, 2, 1)
    , (2, 1, 1)
    , (2, 4, 2)
    , (5, 3, 3);
