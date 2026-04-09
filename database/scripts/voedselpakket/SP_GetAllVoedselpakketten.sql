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
