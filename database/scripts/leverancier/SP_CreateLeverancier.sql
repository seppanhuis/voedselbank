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
