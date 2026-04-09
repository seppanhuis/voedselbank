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
