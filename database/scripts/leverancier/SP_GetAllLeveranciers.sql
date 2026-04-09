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
