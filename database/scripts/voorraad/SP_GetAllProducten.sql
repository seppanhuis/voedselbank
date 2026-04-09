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