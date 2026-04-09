DROP PROCEDURE IF EXISTS SP_CreateProduct;

DELIMITER $$

CREATE PROCEDURE SP_CreateProduct(
    IN p_categorieId INT,
    IN p_productNaam VARCHAR(150),
    IN p_ean CHAR(13),
    IN p_aantalOpVoorraad INT,
    IN p_eenheid VARCHAR(20),
    IN p_houdbaarTot DATE
)
BEGIN
    INSERT INTO Product (
        CategorieId,
        ProductNaam,
        EAN,
        AantalOpVoorraad,
        Eenheid,
        HoudbaarTot,
        IsActief,
        DatumAangemaakt,
        DatumGewijzigd
    ) VALUES (
        p_categorieId,
        p_productNaam,
        p_ean,
        p_aantalOpVoorraad,
        p_eenheid,
        p_houdbaarTot,
        1,
        SYSDATE(6),
        SYSDATE(6)
    );

    SELECT LAST_INSERT_ID() AS new_id;
END$$

DELIMITER ;
