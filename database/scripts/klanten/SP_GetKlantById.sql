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
