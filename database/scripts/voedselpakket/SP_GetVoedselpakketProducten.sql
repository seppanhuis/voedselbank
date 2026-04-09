DROP PROCEDURE IF EXISTS SP_GetVoedselpakketProducten;

DELIMITER $$

CREATE PROCEDURE SP_GetVoedselpakketProducten(
    IN p_id INT
)
BEGIN
    SELECT
         VPP.Id
        ,VPP.VoedselpakketId
        ,VPP.ProductId
        ,P.ProductNaam
        ,P.EAN
        ,C.CategorieNaam
        ,VPP.Aantal
    FROM VoedselpakketProduct AS VPP
    INNER JOIN Product AS P
        ON P.Id = VPP.ProductId
    INNER JOIN Categorie AS C
        ON C.Id = P.CategorieId
    WHERE VPP.VoedselpakketId = p_id
    ORDER BY P.ProductNaam;
END$$

DELIMITER ;
