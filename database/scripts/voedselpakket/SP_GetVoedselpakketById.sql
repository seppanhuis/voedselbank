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
