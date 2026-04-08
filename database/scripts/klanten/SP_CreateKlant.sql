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

    -- Insert address first
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

    -- Insert customer
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
