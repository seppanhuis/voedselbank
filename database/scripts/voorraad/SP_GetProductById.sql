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
