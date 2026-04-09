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
