-- Algemene stored-procedure bundel voor de Voedselbank applicatie.
-- Dit bestand bevat alle procedures zodat je ze in 1 keer kunt uitvoeren.

-- ==========================================================================
-- Klanten
-- ==========================================================================

DROP PROCEDURE IF EXISTS SP_GetAllKlanten;

DELIMITER $$

CREATE PROCEDURE SP_GetAllKlanten()
BEGIN
	SELECT
		 KL.Id
		,KL.KlantAdresId
		,KL.GezinsNaam
		,KL.GeboorteDatum
		,KL.Telefoon
		,KL.Email
		,KL.AantalVolwassenen
		,KL.AantalKinderen
		,KL.AantalBabys

		,KA.Straat
		,KA.Huisnummer
		,KA.Toevoeging
		,KA.Postcode
		,KA.Plaats
		,KA.Land

		,GROUP_CONCAT(DISTINCT SW.WensNaam ORDER BY SW.WensNaam SEPARATOR ', ') AS Wensen
	FROM Klant AS KL
	INNER JOIN KlantAdres AS KA
		ON KA.Id = KL.KlantAdresId
	LEFT JOIN KlantSpecifiekeWens AS KSW
		ON KSW.KlantId = KL.Id
	LEFT JOIN SpecifiekeWens AS SW
		ON SW.Id = KSW.SpecifiekeWensId
	GROUP BY
		 KL.Id
		,KL.KlantAdresId
		,KL.GezinsNaam
		,KL.GeboorteDatum
		,KL.Telefoon
		,KL.Email
		,KL.AantalVolwassenen
		,KL.AantalKinderen
		,KL.AantalBabys
		,KA.Straat
		,KA.Huisnummer
		,KA.Toevoeging
		,KA.Postcode
		,KA.Plaats
		,KA.Land
	ORDER BY KL.Id;
END$$

DELIMITER ;

DROP PROCEDURE IF EXISTS SP_CreateKlant;

DELIMITER $$

CREATE PROCEDURE SP_CreateKlant(
    IN p_gezinsnaam VARCHAR(120),
    IN p_geboortedatum DATE,
    IN p_telefoon VARCHAR(20),
    IN p_email VARCHAR(150),
    IN p_aantalVolwassenen TINYINT UNSIGNED,
    IN p_aantalKinderen TINYINT UNSIGNED,
    IN p_aantalBabys TINYINT UNSIGNED,
    IN p_straat VARCHAR(120),
    IN p_huisnummer VARCHAR(10),
    IN p_postcode VARCHAR(7),
    IN p_plaats VARCHAR(80)
)
BEGIN
    DECLARE v_klantAdresId INT UNSIGNED;

    INSERT INTO KlantAdres (
        Straat,
        Huisnummer,
        Postcode,
        Plaats,
        Land,
        IsActief,
        DatumAangemaakt,
        DatumGewijzigd
    ) VALUES (
        p_straat,
        p_huisnummer,
        p_postcode,
        p_plaats,
        'Nederland',
        1,
        SYSDATE(6),
        SYSDATE(6)
    );

    SET v_klantAdresId = LAST_INSERT_ID();

    INSERT INTO Klant (
        KlantAdresId,
        GezinsNaam,
        GeboorteDatum,
        Telefoon,
        Email,
        AantalVolwassenen,
        AantalKinderen,
        AantalBabys,
        IsActief,
        DatumAangemaakt,
        DatumGewijzigd
    ) VALUES (
        v_klantAdresId,
        p_gezinsnaam,
        p_geboortedatum,
        p_telefoon,
        p_email,
        p_aantalVolwassenen,
        p_aantalKinderen,
        p_aantalBabys,
        1,
        SYSDATE(6),
        SYSDATE(6)
    );

    SELECT LAST_INSERT_ID() AS new_id;
END$$

DELIMITER ;

DROP PROCEDURE IF EXISTS SP_GetKlantById;

DELIMITER $$

CREATE PROCEDURE SP_GetKlantById(
    IN p_id INT
)
BEGIN
    SELECT
         KL.Id
        ,KL.KlantAdresId
        ,KL.GezinsNaam
        ,KL.GeboorteDatum
        ,KL.Telefoon
        ,KL.Email
        ,KL.AantalVolwassenen
        ,KL.AantalKinderen
        ,KL.AantalBabys
        ,KA.Straat
        ,KA.Huisnummer
        ,KA.Toevoeging
        ,KA.Postcode
        ,KA.Plaats
        ,KA.Land
    FROM Klant AS KL
    INNER JOIN KlantAdres AS KA
        ON KA.Id = KL.KlantAdresId
    WHERE KL.Id = p_id
    LIMIT 1;
END$$

DELIMITER ;

DROP PROCEDURE IF EXISTS SP_GetKlantWensenIds;

DELIMITER $$

CREATE PROCEDURE SP_GetKlantWensenIds(
    IN p_klantId INT
)
BEGIN
    SELECT KSW.SpecifiekeWensId
    FROM KlantSpecifiekeWens AS KSW
    WHERE KSW.KlantId = p_klantId
      AND KSW.IsActief = 1
    ORDER BY KSW.SpecifiekeWensId;
END$$

DELIMITER ;

DROP PROCEDURE IF EXISTS SP_SyncKlantWensen;

DELIMITER $$

CREATE PROCEDURE SP_SyncKlantWensen(
    IN p_klantId INT,
    IN p_wensIds TEXT
)
BEGIN
    DELETE FROM KlantSpecifiekeWens
    WHERE KlantId = p_klantId;

    IF p_wensIds IS NOT NULL AND TRIM(p_wensIds) <> '' THEN
        INSERT INTO KlantSpecifiekeWens (
            KlantId,
            SpecifiekeWensId,
            IsActief,
            DatumAangemaakt,
            DatumGewijzigd
        )
        SELECT
             p_klantId
            ,SW.Id
            ,1
            ,SYSDATE(6)
            ,SYSDATE(6)
        FROM SpecifiekeWens AS SW
        WHERE SW.IsActief = 1
          AND FIND_IN_SET(SW.Id, p_wensIds) > 0;
    END IF;

    SELECT ROW_COUNT() AS affected;
END$$

DELIMITER ;

DROP PROCEDURE IF EXISTS SP_UpdateKlant;

DELIMITER $$

CREATE PROCEDURE SP_UpdateKlant(
    IN p_id INT,
    IN p_gezinsnaam VARCHAR(120),
    IN p_geboortedatum DATE,
    IN p_telefoon VARCHAR(20),
    IN p_email VARCHAR(150),
    IN p_aantalVolwassenen TINYINT UNSIGNED,
    IN p_aantalKinderen TINYINT UNSIGNED,
    IN p_aantalBabys TINYINT UNSIGNED,
    IN p_straat VARCHAR(120),
    IN p_huisnummer VARCHAR(10),
    IN p_postcode VARCHAR(7),
    IN p_plaats VARCHAR(80)
)
BEGIN
    UPDATE Klant AS KL
    INNER JOIN KlantAdres AS KA
        ON KA.Id = KL.KlantAdresId
    SET
         KL.GezinsNaam = p_gezinsnaam
        ,KL.GeboorteDatum = p_geboortedatum
        ,KL.Telefoon = p_telefoon
        ,KL.Email = p_email
        ,KL.AantalVolwassenen = p_aantalVolwassenen
        ,KL.AantalKinderen = p_aantalKinderen
        ,KL.AantalBabys = p_aantalBabys
        ,KL.DatumGewijzigd = SYSDATE(6)
        ,KA.Straat = p_straat
        ,KA.Huisnummer = p_huisnummer
        ,KA.Postcode = p_postcode
        ,KA.Plaats = p_plaats
        ,KA.DatumGewijzigd = SYSDATE(6)
    WHERE KL.Id = p_id;

    SELECT ROW_COUNT() AS affected;
END$$

DELIMITER ;

DROP PROCEDURE IF EXISTS SP_DeleteKlant;

DELIMITER $$

CREATE PROCEDURE SP_DeleteKlant(
    IN p_id INT
)
BEGIN
    DECLARE v_affected INT DEFAULT 0;

    START TRANSACTION;

    DELETE FROM KlantSpecifiekeWens
    WHERE KlantId = p_id;

    DELETE FROM Klant
    WHERE Id = p_id;

    SET v_affected = ROW_COUNT();

    COMMIT;

    SELECT v_affected AS affected;
END$$

DELIMITER ;

-- ==========================================================================
-- Leveranciers
-- ==========================================================================

DROP PROCEDURE IF EXISTS SP_GetAllLeveranciers;

DELIMITER $$

CREATE PROCEDURE SP_GetAllLeveranciers()
BEGIN
    SELECT
         L.Id
        ,L.LeverancierAdresId
        ,L.Bedrijfsnaam
        ,L.ContactpersoonNaam
        ,L.ContactpersoonEmail
        ,L.Telefoon
        ,L.EerstvolgendeLevering
        ,L.IsActief
        ,LA.Straat
        ,LA.Huisnummer
        ,LA.Toevoeging
        ,LA.Postcode
        ,LA.Plaats
        ,LA.Land
    FROM Leverancier AS L
    INNER JOIN LeverancierAdres AS LA
        ON LA.Id = L.LeverancierAdresId
    ORDER BY L.Bedrijfsnaam;
END$$

DELIMITER ;

DROP PROCEDURE IF EXISTS SP_GetLeverancierById;

DELIMITER $$

CREATE PROCEDURE SP_GetLeverancierById(
    IN p_id INT
)
BEGIN
    SELECT
         L.Id
        ,L.LeverancierAdresId
        ,L.Bedrijfsnaam
        ,L.ContactpersoonNaam
        ,L.ContactpersoonEmail
        ,L.Telefoon
        ,L.EerstvolgendeLevering
        ,L.IsActief
        ,LA.Straat
        ,LA.Huisnummer
        ,LA.Toevoeging
        ,LA.Postcode
        ,LA.Plaats
        ,LA.Land
    FROM Leverancier AS L
    INNER JOIN LeverancierAdres AS LA
        ON LA.Id = L.LeverancierAdresId
    WHERE L.Id = p_id;
END$$

DELIMITER ;

DROP PROCEDURE IF EXISTS SP_CreateLeverancier;

DELIMITER $$

CREATE PROCEDURE SP_CreateLeverancier(
    IN p_bedrijfsnaam VARCHAR(120),
    IN p_contactpersoonNaam VARCHAR(120),
    IN p_contactpersoonEmail VARCHAR(150),
    IN p_telefoon VARCHAR(20),
    IN p_eerstvolgendeLevering DATETIME,
    IN p_straat VARCHAR(120),
    IN p_huisnummer VARCHAR(10),
    IN p_toevoeging VARCHAR(10),
    IN p_postcode VARCHAR(7),
    IN p_plaats VARCHAR(80),
    IN p_land VARCHAR(80)
)
BEGIN
    DECLARE v_adresId INT UNSIGNED;

    INSERT INTO LeverancierAdres (
        Straat,
        Huisnummer,
        Toevoeging,
        Postcode,
        Plaats,
        Land,
        IsActief,
        DatumAangemaakt,
        DatumGewijzigd
    ) VALUES (
        p_straat,
        p_huisnummer,
        p_toevoeging,
        p_postcode,
        p_plaats,
        COALESCE(NULLIF(p_land, ''), 'Nederland'),
        1,
        SYSDATE(6),
        SYSDATE(6)
    );

    SET v_adresId = LAST_INSERT_ID();

    INSERT INTO Leverancier (
        LeverancierAdresId,
        Bedrijfsnaam,
        ContactpersoonNaam,
        ContactpersoonEmail,
        Telefoon,
        EerstvolgendeLevering,
        IsActief,
        DatumAangemaakt,
        DatumGewijzigd
    ) VALUES (
        v_adresId,
        p_bedrijfsnaam,
        p_contactpersoonNaam,
        p_contactpersoonEmail,
        p_telefoon,
        p_eerstvolgendeLevering,
        1,
        SYSDATE(6),
        SYSDATE(6)
    );

    SELECT LAST_INSERT_ID() AS new_id;
END$$

DELIMITER ;

DROP PROCEDURE IF EXISTS SP_UpdateLeverancier;

DELIMITER $$

CREATE PROCEDURE SP_UpdateLeverancier(
    IN p_id INT,
    IN p_bedrijfsnaam VARCHAR(120),
    IN p_contactpersoonNaam VARCHAR(120),
    IN p_contactpersoonEmail VARCHAR(150),
    IN p_telefoon VARCHAR(20),
    IN p_eerstvolgendeLevering DATETIME,
    IN p_straat VARCHAR(120),
    IN p_huisnummer VARCHAR(10),
    IN p_toevoeging VARCHAR(10),
    IN p_postcode VARCHAR(7),
    IN p_plaats VARCHAR(80),
    IN p_land VARCHAR(80)
)
BEGIN
    UPDATE Leverancier AS L
    INNER JOIN LeverancierAdres AS LA
        ON LA.Id = L.LeverancierAdresId
    SET
         L.Bedrijfsnaam = p_bedrijfsnaam
        ,L.ContactpersoonNaam = p_contactpersoonNaam
        ,L.ContactpersoonEmail = p_contactpersoonEmail
        ,L.Telefoon = p_telefoon
        ,L.EerstvolgendeLevering = p_eerstvolgendeLevering
        ,L.DatumGewijzigd = SYSDATE(6)
        ,LA.Straat = p_straat
        ,LA.Huisnummer = p_huisnummer
        ,LA.Toevoeging = p_toevoeging
        ,LA.Postcode = p_postcode
        ,LA.Plaats = p_plaats
        ,LA.Land = COALESCE(NULLIF(p_land, ''), 'Nederland')
        ,LA.DatumGewijzigd = SYSDATE(6)
    WHERE L.Id = p_id;

    SELECT ROW_COUNT() AS affected;
END$$

DELIMITER ;

DROP PROCEDURE IF EXISTS SP_DeleteLeverancier;

DELIMITER $$

CREATE PROCEDURE SP_DeleteLeverancier(
    IN p_id INT
)
BEGIN
    DELETE FROM LeverancierProduct
    WHERE LeverancierId = p_id;

    DELETE FROM Leverancier
    WHERE Id = p_id;

    SELECT ROW_COUNT() AS affected;
END$$

DELIMITER ;

-- ==========================================================================
-- Voorraad
-- ==========================================================================

DROP PROCEDURE IF EXISTS SP_GetAllProducten;

DELIMITER $$

CREATE PROCEDURE SP_GetAllProducten()
BEGIN
    SELECT
         P.Id
        ,P.CategorieId
        ,C.CategorieNaam
        ,P.ProductNaam
        ,P.EAN
        ,P.AantalOpVoorraad
        ,P.Eenheid
        ,P.HoudbaarTot
        ,P.IsActief
    FROM Product AS P
    INNER JOIN Categorie AS C
        ON C.Id = P.CategorieId
    ORDER BY P.EAN;
END$$

DELIMITER ;

DROP PROCEDURE IF EXISTS SP_GetProductById;

DELIMITER $$

CREATE PROCEDURE SP_GetProductById(
    IN p_id INT
)
BEGIN
    SELECT
         P.Id
        ,P.CategorieId
        ,C.CategorieNaam
        ,P.ProductNaam
        ,P.EAN
        ,P.AantalOpVoorraad
        ,P.Eenheid
        ,P.HoudbaarTot
        ,P.IsActief
    FROM Product AS P
    INNER JOIN Categorie AS C
        ON C.Id = P.CategorieId
    WHERE P.Id = p_id;
END$$

DELIMITER ;

DROP PROCEDURE IF EXISTS SP_CreateProduct;

DELIMITER $$

CREATE PROCEDURE SP_CreateProduct(
    IN p_categorieId INT,
    IN p_productNaam VARCHAR(150),
    IN p_ean CHAR(13),
    IN p_aantalOpVoorraad INT,
    IN p_eenheid VARCHAR(20),
    IN p_houdbaarTot DATE
)
BEGIN
    INSERT INTO Product (
        CategorieId,
        ProductNaam,
        EAN,
        AantalOpVoorraad,
        Eenheid,
        HoudbaarTot,
        IsActief,
        DatumAangemaakt,
        DatumGewijzigd
    ) VALUES (
        p_categorieId,
        p_productNaam,
        p_ean,
        p_aantalOpVoorraad,
        p_eenheid,
        p_houdbaarTot,
        1,
        SYSDATE(6),
        SYSDATE(6)
    );

    SELECT LAST_INSERT_ID() AS new_id;
END$$

DELIMITER ;

DROP PROCEDURE IF EXISTS SP_UpdateProduct;

DELIMITER $$

CREATE PROCEDURE SP_UpdateProduct(
    IN p_id INT,
    IN p_categorieId INT,
    IN p_productNaam VARCHAR(150),
    IN p_ean CHAR(13),
    IN p_aantalOpVoorraad INT,
    IN p_eenheid VARCHAR(20),
    IN p_houdbaarTot DATE
)
BEGIN
    UPDATE Product
    SET
         CategorieId = p_categorieId
        ,ProductNaam = p_productNaam
        ,EAN = p_ean
        ,AantalOpVoorraad = p_aantalOpVoorraad
        ,Eenheid = p_eenheid
        ,HoudbaarTot = p_houdbaarTot
        ,DatumGewijzigd = SYSDATE(6)
    WHERE Id = p_id;

    SELECT ROW_COUNT() AS affected;
END$$

DELIMITER ;

DROP PROCEDURE IF EXISTS SP_DeleteProduct;

DELIMITER $$

CREATE PROCEDURE SP_DeleteProduct(
    IN p_id INT
)
BEGIN
    IF EXISTS (
        SELECT 1
        FROM VoedselpakketProduct
        WHERE ProductId = p_id
    ) THEN
        SELECT 0 AS affected, 1 AS blocked, 'Product kan niet worden verwijderd omdat het al in een voedselpakket is gebruikt.' AS message;
    ELSE
        DELETE FROM LeverancierProduct
        WHERE ProductId = p_id;

        DELETE FROM ProductMagazijnLocatie
        WHERE ProductId = p_id;

        DELETE FROM Product
        WHERE Id = p_id;

        SELECT ROW_COUNT() AS affected, 0 AS blocked, NULL AS message;
    END IF;
END$$

DELIMITER ;

-- ==========================================================================
-- Voedselpakketten
-- ==========================================================================

DROP PROCEDURE IF EXISTS SP_GetAllVoedselpakketten;

DELIMITER $$

CREATE PROCEDURE SP_GetAllVoedselpakketten()
BEGIN
    SELECT
         VP.Id
        ,VP.KlantId
        ,K.GezinsNaam
        ,VP.DatumSamengesteld
        ,VP.DatumUitgifte
        ,VP.PakketStatus
        ,COUNT(DISTINCT VPP.Id) AS AantalProductRegels
        ,COALESCE(SUM(VPP.Aantal), 0) AS TotaalAantal
    FROM Voedselpakket AS VP
    INNER JOIN Klant AS K
        ON K.Id = VP.KlantId
    LEFT JOIN VoedselpakketProduct AS VPP
        ON VPP.VoedselpakketId = VP.Id
    GROUP BY
         VP.Id
        ,VP.KlantId
        ,K.GezinsNaam
        ,VP.DatumSamengesteld
        ,VP.DatumUitgifte
        ,VP.PakketStatus
    ORDER BY VP.Id DESC;
END$$

DELIMITER ;

DROP PROCEDURE IF EXISTS SP_GetVoedselpakketById;

DELIMITER $$

CREATE PROCEDURE SP_GetVoedselpakketById(
    IN p_id INT
)
BEGIN
    SELECT
         VP.Id
        ,VP.KlantId
        ,K.GezinsNaam
        ,VP.DatumSamengesteld
        ,VP.DatumUitgifte
        ,VP.PakketStatus
        ,VP.IsActief
        ,K.Email
        ,K.Telefoon
    FROM Voedselpakket AS VP
    INNER JOIN Klant AS K
        ON K.Id = VP.KlantId
    WHERE VP.Id = p_id;
END$$

DELIMITER ;

DROP PROCEDURE IF EXISTS SP_GetVoedselpakketProducten;

DELIMITER $$

CREATE PROCEDURE SP_GetVoedselpakketProducten(
    IN p_id INT
)
BEGIN
    SELECT
         VPP.Id
        ,VPP.VoedselpakketId
        ,VPP.ProductId
        ,P.ProductNaam
        ,P.EAN
        ,C.CategorieNaam
        ,VPP.Aantal
    FROM VoedselpakketProduct AS VPP
    INNER JOIN Product AS P
        ON P.Id = VPP.ProductId
    INNER JOIN Categorie AS C
        ON C.Id = P.CategorieId
    WHERE VPP.VoedselpakketId = p_id
    ORDER BY P.ProductNaam;
END$$

DELIMITER ;

DROP PROCEDURE IF EXISTS SP_CreateVoedselpakket;

DELIMITER $$

CREATE PROCEDURE SP_CreateVoedselpakket(
    IN p_klantId INT,
    IN p_datumSamengesteld DATE,
    IN p_datumUitgifte DATE,
    IN p_pakketStatus VARCHAR(20)
)
BEGIN
    INSERT INTO Voedselpakket (
        KlantId,
        DatumSamengesteld,
        DatumUitgifte,
        PakketStatus,
        IsActief,
        DatumAangemaakt,
        DatumGewijzigd
    ) VALUES (
        p_klantId,
        p_datumSamengesteld,
        p_datumUitgifte,
        p_pakketStatus,
        1,
        SYSDATE(6),
        SYSDATE(6)
    );

    SELECT LAST_INSERT_ID() AS new_id;
END$$

DELIMITER ;

DROP PROCEDURE IF EXISTS SP_AddVoedselpakketProduct;

DELIMITER $$

CREATE PROCEDURE SP_AddVoedselpakketProduct(
    IN p_voedselpakketId INT,
    IN p_productId INT,
    IN p_aantal INT
)
BEGIN
    DECLARE v_voorraad INT UNSIGNED;

    SELECT AantalOpVoorraad
    INTO v_voorraad
    FROM Product
    WHERE Id = p_productId
    FOR UPDATE;

    IF v_voorraad < p_aantal THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Er is onvoldoende voorraad voor dit product.';
    END IF;

    INSERT INTO VoedselpakketProduct (
        VoedselpakketId,
        ProductId,
        Aantal,
        IsActief,
        DatumAangemaakt,
        DatumGewijzigd
    ) VALUES (
        p_voedselpakketId,
        p_productId,
        p_aantal,
        1,
        SYSDATE(6),
        SYSDATE(6)
    );

    UPDATE Product
    SET
         AantalOpVoorraad = AantalOpVoorraad - p_aantal
        ,DatumGewijzigd = SYSDATE(6)
    WHERE Id = p_productId;

    SELECT ROW_COUNT() AS affected;
END$$

DELIMITER ;

DROP PROCEDURE IF EXISTS SP_DeleteVoedselpakketProducten;

DELIMITER $$

CREATE PROCEDURE SP_DeleteVoedselpakketProducten(
    IN p_id INT
)
BEGIN
    UPDATE Product AS P
    INNER JOIN VoedselpakketProduct AS VPP
        ON VPP.ProductId = P.Id
    SET
         P.AantalOpVoorraad = P.AantalOpVoorraad + VPP.Aantal
        ,P.DatumGewijzigd = SYSDATE(6)
    WHERE VPP.VoedselpakketId = p_id;

    DELETE FROM VoedselpakketProduct
    WHERE VoedselpakketId = p_id;

    SELECT ROW_COUNT() AS affected;
END$$

DELIMITER ;

DROP PROCEDURE IF EXISTS SP_UpdateVoedselpakket;

DELIMITER $$

CREATE PROCEDURE SP_UpdateVoedselpakket(
    IN p_id INT,
    IN p_klantId INT,
    IN p_datumSamengesteld DATE,
    IN p_datumUitgifte DATE,
    IN p_pakketStatus VARCHAR(20)
)
BEGIN
    UPDATE Voedselpakket
    SET
         KlantId = p_klantId
        ,DatumSamengesteld = p_datumSamengesteld
        ,DatumUitgifte = p_datumUitgifte
        ,PakketStatus = p_pakketStatus
        ,DatumGewijzigd = SYSDATE(6)
    WHERE Id = p_id;

    SELECT ROW_COUNT() AS affected;
END$$

DELIMITER ;

DROP PROCEDURE IF EXISTS SP_DeleteVoedselpakket;

DELIMITER $$

CREATE PROCEDURE SP_DeleteVoedselpakket(
    IN p_id INT
)
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM Voedselpakket
        WHERE Id = p_id
    ) THEN
        SELECT 0 AS affected, 'Voedselpakket is niet verwijderd.' AS message;
    ELSE
        UPDATE Product AS P
        INNER JOIN VoedselpakketProduct AS VPP
            ON VPP.ProductId = P.Id
        SET
             P.AantalOpVoorraad = P.AantalOpVoorraad + VPP.Aantal
            ,P.DatumGewijzigd = SYSDATE(6)
        WHERE VPP.VoedselpakketId = p_id;

        DELETE FROM VoedselpakketProduct
        WHERE VoedselpakketId = p_id;

        DELETE FROM Voedselpakket
        WHERE Id = p_id;

        SELECT ROW_COUNT() AS affected, NULL AS message;
    END IF;
END$$

DELIMITER ;
