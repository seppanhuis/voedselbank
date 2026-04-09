DROP PROCEDURE IF EXISTS SP_UpdateProduct;
DELIMITER $$

CREATE PROCEDURE SP_UpdateProduct(
    IN p_id INT,
    IN p_categorieId INT,
    IN p_productNaam VARCHAR(150),
    IN p_ean CHAR(13),
    IN p_aantalOpVoorraad INT,
    IN p_eenheid VARCHAR(20),
    IN p_houdbaarTot DATE
)
BEGIN
    UPDATE Product
    SET
         CategorieId = p_categorieId
        ,ProductNaam = p_productNaam
        ,EAN = p_ean
        ,AantalOpVoorraad = p_aantalOpVoorraad
        ,Eenheid = p_eenheid
        ,HoudbaarTot = p_houdbaarTot
        ,DatumGewijzigd = SYSDATE(6)
    WHERE Id = p_id;

    SELECT ROW_COUNT() AS affected;
END$$

DELIMITER ;
