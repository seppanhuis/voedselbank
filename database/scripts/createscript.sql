-- Step: 01
-- ***************************************************************
-- Doel : Maak een nieuwe database aan met de naam Voedselbank_Maaskantje
-- ***************************************************************
-- Versie       Datum           Auteur              Omschrijving
-- ******       *****           ******              ************
-- 01           08-04-2026      GitHub Copilot      Genormaliseerd create-script Voedselbank
-- ***************************************************************

DROP DATABASE IF EXISTS `Voedselbank_Maaskantje`;
CREATE DATABASE `Voedselbank_Maaskantje`;
USE `Voedselbank_Maaskantje`;

-- Step: 02
-- *****************************************************************************************************
-- Doel : Maak een nieuwe tabel aan met de naam Adres
-- *****************************************************************************************************

CREATE TABLE Adres
(
		 Id                         INT                 UNSIGNED    NOT NULL    AUTO_INCREMENT
		,Straat                     VARCHAR(120)                    NOT NULL
		,Huisnummer                 VARCHAR(10)                     NOT NULL
		,Toevoeging                 VARCHAR(10)                         NULL    DEFAULT NULL
		,Postcode                   VARCHAR(7)                      NOT NULL
		,Plaats                     VARCHAR(80)                     NOT NULL
		,Land                       VARCHAR(80)                     NOT NULL    DEFAULT 'Nederland'
		,IsActief                   BIT                             NOT NULL    DEFAULT b'1'
		,Opmerking                  VARCHAR(250)                        NULL    DEFAULT NULL
		,DatumAangemaakt            DATETIME(6)                     NOT NULL
		,DatumGewijzigd             DATETIME(6)                     NOT NULL
		,CONSTRAINT PK_Adres_Id PRIMARY KEY (Id)
		,CONSTRAINT UQ_Adres UNIQUE (Straat, Huisnummer, Toevoeging, Postcode, Plaats, Land)
) ENGINE=InnoDB;

-- Step: 03
-- *****************************************************************************************************
-- Doel : Maak een nieuwe tabel aan met de naam Leverancier
-- *****************************************************************************************************

CREATE TABLE Leverancier
(
		 Id                         INT                 UNSIGNED    NOT NULL    AUTO_INCREMENT
		,AdresId                    INT                 UNSIGNED    NOT NULL
		,Bedrijfsnaam               VARCHAR(120)                    NOT NULL
		,ContactpersoonNaam         VARCHAR(120)                    NOT NULL
		,ContactpersoonEmail        VARCHAR(150)                    NOT NULL
		,Telefoon                   VARCHAR(20)                     NOT NULL
		,EerstvolgendeLevering      DATETIME                        NOT NULL
		,IsActief                   BIT                             NOT NULL    DEFAULT b'1'
		,Opmerking                  VARCHAR(250)                        NULL    DEFAULT NULL
		,DatumAangemaakt            DATETIME(6)                     NOT NULL
		,DatumGewijzigd             DATETIME(6)                     NOT NULL
		,CONSTRAINT PK_Leverancier_Id PRIMARY KEY (Id)
		,CONSTRAINT UQ_Leverancier_Bedrijfsnaam UNIQUE (Bedrijfsnaam)
		,CONSTRAINT UQ_Leverancier_ContactpersoonEmail UNIQUE (ContactpersoonEmail)
		,CONSTRAINT FK_Leverancier_Adres FOREIGN KEY (AdresId) REFERENCES Adres(Id)
			ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Step: 04
-- *****************************************************************************************************
-- Doel : Maak een nieuwe tabel aan met de naam Categorie
-- *****************************************************************************************************

CREATE TABLE Categorie
(
		 Id                         INT                 UNSIGNED    NOT NULL    AUTO_INCREMENT
		,CategorieNaam              VARCHAR(100)                    NOT NULL
		,IsActief                   BIT                             NOT NULL    DEFAULT b'1'
		,Opmerking                  VARCHAR(250)                        NULL    DEFAULT NULL
		,DatumAangemaakt            DATETIME(6)                     NOT NULL
		,DatumGewijzigd             DATETIME(6)                     NOT NULL
		,CONSTRAINT PK_Categorie_Id PRIMARY KEY (Id)
		,CONSTRAINT UQ_Categorie_CategorieNaam UNIQUE (CategorieNaam)
) ENGINE=InnoDB;

-- Step: 05
-- *****************************************************************************************************
-- Doel : Maak een nieuwe tabel aan met de naam Product
-- *****************************************************************************************************

CREATE TABLE Product
(
		 Id                         INT                 UNSIGNED    NOT NULL    AUTO_INCREMENT
		,CategorieId                INT                 UNSIGNED    NOT NULL
		,ProductNaam                VARCHAR(150)                    NOT NULL
		,EAN                        CHAR(13)                        NOT NULL
		,AantalOpVoorraad           INT                 UNSIGNED    NOT NULL    DEFAULT 0
		,Eenheid                    VARCHAR(20)                     NOT NULL    DEFAULT 'stuks'
		,HoudbaarTot                DATE                                NULL    DEFAULT NULL
		,IsActief                   BIT                             NOT NULL    DEFAULT b'1'
		,Opmerking                  VARCHAR(250)                        NULL    DEFAULT NULL
		,DatumAangemaakt            DATETIME(6)                     NOT NULL
		,DatumGewijzigd             DATETIME(6)                     NOT NULL
		,CONSTRAINT PK_Product_Id PRIMARY KEY (Id)
		,CONSTRAINT UQ_Product_ProductNaam UNIQUE (ProductNaam)
		,CONSTRAINT UQ_Product_EAN UNIQUE (EAN)
		,CONSTRAINT FK_Product_Categorie FOREIGN KEY (CategorieId) REFERENCES Categorie(Id)
			ON UPDATE CASCADE ON DELETE RESTRICT
		,CONSTRAINT CHK_Product_EAN_Lengte CHECK (CHAR_LENGTH(EAN) = 13)
) ENGINE=InnoDB;

-- Step: 06
-- *****************************************************************************************************
-- Doel : Maak een nieuwe koppeltabel aan met de naam LeverancierProduct
-- *****************************************************************************************************

CREATE TABLE LeverancierProduct
(
		 Id                         INT                 UNSIGNED    NOT NULL    AUTO_INCREMENT
		,LeverancierId              INT                 UNSIGNED    NOT NULL
		,ProductId                  INT                 UNSIGNED    NOT NULL
		,IsActief                   BIT                             NOT NULL    DEFAULT b'1'
		,Opmerking                  VARCHAR(250)                        NULL    DEFAULT NULL
		,DatumAangemaakt            DATETIME(6)                     NOT NULL
		,DatumGewijzigd             DATETIME(6)                     NOT NULL
		,CONSTRAINT PK_LeverancierProduct_Id PRIMARY KEY (Id)
		,CONSTRAINT UQ_LeverancierProduct UNIQUE (LeverancierId, ProductId)
		,CONSTRAINT FK_LeverancierProduct_Leverancier FOREIGN KEY (LeverancierId) REFERENCES Leverancier(Id)
			ON UPDATE CASCADE ON DELETE RESTRICT
		,CONSTRAINT FK_LeverancierProduct_Product FOREIGN KEY (ProductId) REFERENCES Product(Id)
			ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Step: 07
-- *****************************************************************************************************
-- Doel : Maak een nieuwe tabel aan met de naam Klant
-- *****************************************************************************************************

CREATE TABLE Klant
(
		 Id                         INT                 UNSIGNED    NOT NULL    AUTO_INCREMENT
		,AdresId                    INT                 UNSIGNED    NOT NULL
		,GezinsNaam                 VARCHAR(120)                    NOT NULL
		,Telefoon                   VARCHAR(20)                     NOT NULL
		,Email                      VARCHAR(150)                    NOT NULL
		,AantalVolwassenen          TINYINT             UNSIGNED    NOT NULL
		,AantalKinderen             TINYINT             UNSIGNED    NOT NULL
		,AantalBabys                TINYINT             UNSIGNED    NOT NULL
		,IsActief                   BIT                             NOT NULL    DEFAULT b'1'
		,Opmerking                  VARCHAR(250)                        NULL    DEFAULT NULL
		,DatumAangemaakt            DATETIME(6)                     NOT NULL
		,DatumGewijzigd             DATETIME(6)                     NOT NULL
		,CONSTRAINT PK_Klant_Id PRIMARY KEY (Id)
		,CONSTRAINT UQ_Klant_Email UNIQUE (Email)
		,CONSTRAINT FK_Klant_Adres FOREIGN KEY (AdresId) REFERENCES Adres(Id)
			ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Step: 08
-- *****************************************************************************************************
-- Doel : Maak een nieuwe tabel aan met de naam SpecifiekeWens
-- *****************************************************************************************************

CREATE TABLE SpecifiekeWens
(
		 Id                         INT                 UNSIGNED    NOT NULL    AUTO_INCREMENT
		,WensNaam                   VARCHAR(120)                    NOT NULL
		,WensType                   VARCHAR(30)                     NOT NULL
		,IsActief                   BIT                             NOT NULL    DEFAULT b'1'
		,Opmerking                  VARCHAR(250)                        NULL    DEFAULT NULL
		,DatumAangemaakt            DATETIME(6)                     NOT NULL
		,DatumGewijzigd             DATETIME(6)                     NOT NULL
		,CONSTRAINT PK_SpecifiekeWens_Id PRIMARY KEY (Id)
		,CONSTRAINT UQ_SpecifiekeWens_WensNaam UNIQUE (WensNaam)
) ENGINE=InnoDB;

-- Step: 09
-- *****************************************************************************************************
-- Doel : Maak een nieuwe tabel aan met de naam KlantSpecifiekeWens
-- *****************************************************************************************************

CREATE TABLE KlantSpecifiekeWens
(
		 Id                         INT                 UNSIGNED    NOT NULL    AUTO_INCREMENT
		,KlantId                    INT                 UNSIGNED    NOT NULL
		,SpecifiekeWensId           INT                 UNSIGNED    NOT NULL
		,IsActief                   BIT                             NOT NULL    DEFAULT b'1'
		,Opmerking                  VARCHAR(250)                        NULL    DEFAULT NULL
		,DatumAangemaakt            DATETIME(6)                     NOT NULL
		,DatumGewijzigd             DATETIME(6)                     NOT NULL
		,CONSTRAINT PK_KlantSpecifiekeWens_Id PRIMARY KEY (Id)
		,CONSTRAINT UQ_KlantSpecifiekeWens UNIQUE (KlantId, SpecifiekeWensId)
		,CONSTRAINT FK_KlantSpecifiekeWens_Klant FOREIGN KEY (KlantId) REFERENCES Klant(Id)
			ON UPDATE CASCADE ON DELETE RESTRICT
		,CONSTRAINT FK_KlantSpecifiekeWens_SpecifiekeWens FOREIGN KEY (SpecifiekeWensId) REFERENCES SpecifiekeWens(Id)
			ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Step: 10
-- *****************************************************************************************************
-- Doel : Maak een nieuwe tabel aan met de naam Voedselpakket
-- *****************************************************************************************************

CREATE TABLE Voedselpakket
(
		 Id                         INT                 UNSIGNED    NOT NULL    AUTO_INCREMENT
		,KlantId                    INT                 UNSIGNED    NOT NULL
		,DatumSamengesteld          DATE                            NOT NULL
		,DatumUitgifte              DATE                                NULL    DEFAULT NULL
		,PakketStatus               VARCHAR(20)                     NOT NULL    DEFAULT 'Samengesteld'
		,IsActief                   BIT                             NOT NULL    DEFAULT b'1'
		,Opmerking                  VARCHAR(250)                        NULL    DEFAULT NULL
		,DatumAangemaakt            DATETIME(6)                     NOT NULL
		,DatumGewijzigd             DATETIME(6)                     NOT NULL
		,CONSTRAINT PK_Voedselpakket_Id PRIMARY KEY (Id)
		,CONSTRAINT FK_Voedselpakket_Klant FOREIGN KEY (KlantId) REFERENCES Klant(Id)
			ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Step: 11
-- *****************************************************************************************************
-- Doel : Maak een nieuwe tabel aan met de naam VoedselpakketProduct
-- *****************************************************************************************************

CREATE TABLE VoedselpakketProduct
(
		 Id                         INT                 UNSIGNED    NOT NULL    AUTO_INCREMENT
		,VoedselpakketId            INT                 UNSIGNED    NOT NULL
		,ProductId                  INT                 UNSIGNED    NOT NULL
		,Aantal                     SMALLINT            UNSIGNED    NOT NULL
		,IsActief                   BIT                             NOT NULL    DEFAULT b'1'
		,Opmerking                  VARCHAR(250)                        NULL    DEFAULT NULL
		,DatumAangemaakt            DATETIME(6)                     NOT NULL
		,DatumGewijzigd             DATETIME(6)                     NOT NULL
		,CONSTRAINT PK_VoedselpakketProduct_Id PRIMARY KEY (Id)
		,CONSTRAINT UQ_VoedselpakketProduct UNIQUE (VoedselpakketId, ProductId)
		,CONSTRAINT FK_VoedselpakketProduct_Voedselpakket FOREIGN KEY (VoedselpakketId) REFERENCES Voedselpakket(Id)
			ON UPDATE CASCADE ON DELETE RESTRICT
		,CONSTRAINT FK_VoedselpakketProduct_Product FOREIGN KEY (ProductId) REFERENCES Product(Id)
			ON UPDATE CASCADE ON DELETE RESTRICT

) ENGINE=InnoDB;

-- Step: 12
-- *****************************************************************
-- Doel : Vul de tabel Adres met gegevens (minimaal 5)
-- *****************************************************************

INSERT INTO Adres
(
		 Straat
		,Huisnummer
		,Toevoeging
		,Postcode
		,Plaats
		,Land
		,IsActief
		,Opmerking
		,DatumAangemaakt
		,DatumGewijzigd
)
VALUES
	('Hoofdstraat', '12', 'A', '5461AA', 'Veghel', 'Nederland', 1, NULL, SYSDATE(6), SYSDATE(6))
 ,('Veldweg', '8', NULL, '5397LA', 'Lith', 'Nederland', 1, NULL, SYSDATE(6), SYSDATE(6))
 ,('Dorpsstraat', '101', NULL, '5473BN', 'Heeswijk-Dinther', 'Nederland', 1, NULL, SYSDATE(6), SYSDATE(6))
 ,('Industrieweg', '25', NULL, '5232CE', 'Den Bosch', 'Nederland', 1, NULL, SYSDATE(6), SYSDATE(6))
 ,('Melkstraat', '4', NULL, '5405AB', 'Uden', 'Nederland', 1, NULL, SYSDATE(6), SYSDATE(6))
 ,('Marktplein', '3', NULL, '5271AC', 'Sint-Michielsgestel', 'Nederland', 1, NULL, SYSDATE(6), SYSDATE(6))
 ,('Molenstraat', '14', NULL, '5271ZK', 'Sint-Michielsgestel', 'Nederland', 1, NULL, SYSDATE(6), SYSDATE(6))
 ,('Lijsterlaan', '7', NULL, '5271WE', 'Sint-Michielsgestel', 'Nederland', 1, NULL, SYSDATE(6), SYSDATE(6))
 ,('Pastoorstraat', '28', 'B', '5271GA', 'Sint-Michielsgestel', 'Nederland', 1, NULL, SYSDATE(6), SYSDATE(6))
 ,('Schutsboom', '51', NULL, '5271LP', 'Sint-Michielsgestel', 'Nederland', 1, NULL, SYSDATE(6), SYSDATE(6))
 ,('Akkerweg', '2', NULL, '5271RH', 'Sint-Michielsgestel', 'Nederland', 1, NULL, SYSDATE(6), SYSDATE(6));

-- Step: 13
-- *****************************************************************
-- Doel : Vul de tabel Leverancier met gegevens (minimaal 5)
-- *****************************************************************

INSERT INTO Leverancier
(
		 AdresId
		,Bedrijfsnaam
		,ContactpersoonNaam
		,ContactpersoonEmail
		,Telefoon
		,EerstvolgendeLevering
		,IsActief
		,Opmerking
		,DatumAangemaakt
		,DatumGewijzigd
)
VALUES
	(1, 'Jumbo Veghel', 'Iris van Dijk', 'inkoop@jumbo-veghel.nl', '+31 413 123456', '2026-04-10 09:00:00', 1, NULL, SYSDATE(6), SYSDATE(6))
 ,(2, 'Boer De Zonnedauw', 'Koen Vermeer', 'contact@zonnedauwboer.nl', '+31 412 456789', '2026-04-09 07:30:00', 1, NULL, SYSDATE(6), SYSDATE(6))
 ,(3, 'Bakkerij Van Mierlo', 'Nora van Mierlo', 'levering@bakkerijvanmierlo.nl', '+31 413 765432', '2026-04-11 06:15:00', 1, NULL, SYSDATE(6), SYSDATE(6))
 ,(4, 'Groothandel Maasland', 'Rachid El Hamidi', 'planning@maasland-groothandel.nl', '+31 73 8899001', '2026-04-12 08:45:00', 1, NULL, SYSDATE(6), SYSDATE(6))
 ,(5, 'Zuivelco Uden', 'Eline Smits', 'service@zuivelco-uden.nl', '+31 413 221199', '2026-04-09 10:00:00', 1, NULL, SYSDATE(6), SYSDATE(6))
 ,(6, 'Stichting Oogst Voor Elkaar', 'Milan de Groot', 'team@oogstvoorelkaar.nl', '+31 73 4455667', '2026-04-13 11:00:00', 1, 'Nieuw toegevoegd record in Leverancier', SYSDATE(6), SYSDATE(6));

-- Step: 14
-- *****************************************************************
-- Doel : Vul de tabel Categorie met gegevens (minimaal 5)
-- *****************************************************************

INSERT INTO Categorie
(
		 CategorieNaam
		,IsActief
		,Opmerking
		,DatumAangemaakt
		,DatumGewijzigd
)
VALUES
	('Aardappelen, groente, fruit', 1, NULL, SYSDATE(6), SYSDATE(6))
 ,('Kaas, vleeswaren', 1, NULL, SYSDATE(6), SYSDATE(6))
 ,('Zuivel, plantaardig en eieren', 1, NULL, SYSDATE(6), SYSDATE(6))
 ,('Bakkerij en banket', 1, NULL, SYSDATE(6), SYSDATE(6))
 ,('Frisdrank, sappen, koffie en thee', 1, NULL, SYSDATE(6), SYSDATE(6))
 ,('Pasta, rijst en wereldkeuken', 1, NULL, SYSDATE(6), SYSDATE(6))
 ,('Soepen, sauzen, kruiden en olie', 1, NULL, SYSDATE(6), SYSDATE(6))
 ,('Snoep, koek, chips en chocolade', 1, NULL, SYSDATE(6), SYSDATE(6))
 ,('Baby, verzorging en hygiene', 1, NULL, SYSDATE(6), SYSDATE(6));

-- Step: 15
-- *****************************************************************
-- Doel : Vul de tabel Product met gegevens (minimaal 5)
-- *****************************************************************

INSERT INTO Product
(
		 CategorieId
		,ProductNaam
		,EAN
		,AantalOpVoorraad
		,Eenheid
		,HoudbaarTot
		,IsActief
		,Opmerking
		,DatumAangemaakt
		,DatumGewijzigd
)
VALUES
	(1, 'Aardappelen 2kg', '8712345000001', 45, 'zak', '2026-06-30', 1, NULL, SYSDATE(6), SYSDATE(6))
	 ,(1, 'Wortelmix 1kg', '8712345000002', 30, 'zak', '2026-05-20', 1, NULL, SYSDATE(6), SYSDATE(6))
	 ,(3, 'Halfvolle melk 1L', '8712345000003', 80, 'pak', '2026-05-05', 1, NULL, SYSDATE(6), SYSDATE(6))
	 ,(4, 'Volkoren brood', '8712345000004', 25, 'stuks', '2026-04-12', 1, NULL, SYSDATE(6), SYSDATE(6))
	 ,(6, 'Penne 500g', '8712345000005', 60, 'pak', '2027-02-15', 1, NULL, SYSDATE(6), SYSDATE(6))
	 ,(7, 'Tomatensaus 500ml', '8712345000006', 50, 'fles', '2026-12-01', 1, NULL, SYSDATE(6), SYSDATE(6))
	 ,(5, 'Appelsap 1L', '8712345000007', 40, 'pak', '2026-11-10', 1, NULL, SYSDATE(6), SYSDATE(6))
	 ,(9, 'Babyvoeding 6+ maanden', '8712345000008', 20, 'pot', '2026-08-01', 1, NULL, SYSDATE(6), SYSDATE(6))
	 ,(2, 'Kipfiletplakjes 150g', '8712345000009', 18, 'verpakking', '2026-04-18', 1, NULL, SYSDATE(6), SYSDATE(6))
	 ,(8, 'Havermoutkoekjes 300g', '8712345000010', 35, 'pak', '2026-10-15', 1, NULL, SYSDATE(6), SYSDATE(6));

-- Step: 16
-- *****************************************************************
-- Doel : Vul de koppeltabel LeverancierProduct met gegevens
-- *****************************************************************

INSERT INTO LeverancierProduct
(
		 LeverancierId
		,ProductId
		,IsActief
		,Opmerking
		,DatumAangemaakt
		,DatumGewijzigd
)
VALUES
	(2, 1, 1, NULL, SYSDATE(6), SYSDATE(6))
	 ,(2, 2, 1, NULL, SYSDATE(6), SYSDATE(6))
	 ,(5, 3, 1, NULL, SYSDATE(6), SYSDATE(6))
	 ,(3, 4, 1, NULL, SYSDATE(6), SYSDATE(6))
	 ,(4, 5, 1, NULL, SYSDATE(6), SYSDATE(6))
	 ,(4, 6, 1, NULL, SYSDATE(6), SYSDATE(6))
	 ,(1, 7, 1, NULL, SYSDATE(6), SYSDATE(6))
	 ,(6, 8, 1, NULL, SYSDATE(6), SYSDATE(6))
	 ,(1, 9, 1, NULL, SYSDATE(6), SYSDATE(6))
	 ,(1, 10, 1, NULL, SYSDATE(6), SYSDATE(6));

-- Step: 17
-- *****************************************************************
-- Doel : Vul de tabel Klant met gegevens (minimaal 5)
-- *****************************************************************

INSERT INTO Klant
(
		 AdresId
		,GezinsNaam
		,Telefoon
		,Email
		,AantalVolwassenen
		,AantalKinderen
		,AantalBabys
		,IsActief
		,Opmerking
		,DatumAangemaakt
		,DatumGewijzigd
)
VALUES
	(7, 'Familie Jansen', '+31 6 11223344', 'familie.jansen@mail.nl', 2, 2, 0, 1, NULL, SYSDATE(6), SYSDATE(6))
 ,(8, 'Familie Peters', '+31 6 22334455', 'familie.peters@mail.nl', 1, 1, 1, 1, NULL, SYSDATE(6), SYSDATE(6))
 ,(9, 'Familie El Idrissi', '+31 6 33445566', 'familie.elidrissi@mail.nl', 2, 3, 0, 1, NULL, SYSDATE(6), SYSDATE(6))
 ,(10, 'Familie De Vries', '+31 6 44556677', 'familie.devries@mail.nl', 2, 0, 0, 1, NULL, SYSDATE(6), SYSDATE(6))
 ,(11, 'Familie Koster', '+31 6 55667788', 'familie.koster@mail.nl', 1, 2, 0, 1, NULL, SYSDATE(6), SYSDATE(6));

-- Step: 18
-- *****************************************************************
-- Doel : Vul de tabel SpecifiekeWens met gegevens (minimaal 5)
-- *****************************************************************

INSERT INTO SpecifiekeWens
(
		 WensNaam
		,WensType
		,IsActief
		,Opmerking
		,DatumAangemaakt
		,DatumGewijzigd
)
VALUES
	('Geen varkensvlees', 'Dieet', 1, NULL, SYSDATE(6), SYSDATE(6))
 ,('Allergisch voor gluten', 'Allergie', 1, NULL, SYSDATE(6), SYSDATE(6))
 ,('Allergisch voor pinda''s', 'Allergie', 1, NULL, SYSDATE(6), SYSDATE(6))
 ,('Allergisch voor lactose', 'Allergie', 1, NULL, SYSDATE(6), SYSDATE(6))
 ,('Vegetarisch', 'Dieet', 1, NULL, SYSDATE(6), SYSDATE(6))
 ,('Veganistisch', 'Dieet', 1, NULL, SYSDATE(6), SYSDATE(6))
 ,('Allergisch voor schaaldieren', 'Allergie', 1, NULL, SYSDATE(6), SYSDATE(6));

-- Step: 19
-- *****************************************************************
-- Doel : Vul de tabel KlantSpecifiekeWens met gegevens (minimaal 5)
-- *****************************************************************

INSERT INTO KlantSpecifiekeWens
(
		 KlantId
		,SpecifiekeWensId
		,IsActief
		,Opmerking
		,DatumAangemaakt
		,DatumGewijzigd
)
VALUES
	(1, 2, 1, NULL, SYSDATE(6), SYSDATE(6))
 ,(2, 4, 1, NULL, SYSDATE(6), SYSDATE(6))
 ,(2, 5, 1, NULL, SYSDATE(6), SYSDATE(6))
 ,(3, 1, 1, NULL, SYSDATE(6), SYSDATE(6))
 ,(3, 3, 1, NULL, SYSDATE(6), SYSDATE(6))
 ,(4, 5, 1, NULL, SYSDATE(6), SYSDATE(6))
 ,(5, 6, 1, NULL, SYSDATE(6), SYSDATE(6));

-- Step: 20
-- *****************************************************************
-- Doel : Vul de tabel Voedselpakket met gegevens (minimaal 5)
-- *****************************************************************

INSERT INTO Voedselpakket
(
		 KlantId
		,DatumSamengesteld
		,DatumUitgifte
		,PakketStatus
		,IsActief
		,Opmerking
		,DatumAangemaakt
		,DatumGewijzigd
)
VALUES
	(1, '2026-04-02', '2026-04-03', 'Uitgereikt', 1, NULL, SYSDATE(6), SYSDATE(6))
 ,(2, '2026-04-02', '2026-04-03', 'Uitgereikt', 1, NULL, SYSDATE(6), SYSDATE(6))
 ,(3, '2026-04-02', '2026-04-03', 'Uitgereikt', 1, NULL, SYSDATE(6), SYSDATE(6))
 ,(4, '2026-04-09', NULL, 'Samengesteld', 1, NULL, SYSDATE(6), SYSDATE(6))
 ,(5, '2026-04-09', NULL, 'Samengesteld', 1, NULL, SYSDATE(6), SYSDATE(6));

-- Step: 21
-- *****************************************************************
-- Doel : Vul de tabel VoedselpakketProduct met gegevens (minimaal 5)
-- *****************************************************************

INSERT INTO VoedselpakketProduct
(
		 VoedselpakketId
		,ProductId
		,Aantal
		,IsActief
		,Opmerking
		,DatumAangemaakt
		,DatumGewijzigd
)
VALUES
	(1, 1, 1, 1, NULL, SYSDATE(6), SYSDATE(6))
 ,(1, 3, 2, 1, NULL, SYSDATE(6), SYSDATE(6))
 ,(1, 4, 1, 1, NULL, SYSDATE(6), SYSDATE(6))
 ,(2, 2, 1, 1, NULL, SYSDATE(6), SYSDATE(6))
 ,(2, 8, 2, 1, NULL, SYSDATE(6), SYSDATE(6))
 ,(3, 5, 2, 1, NULL, SYSDATE(6), SYSDATE(6))
 ,(3, 6, 1, 1, NULL, SYSDATE(6), SYSDATE(6));
