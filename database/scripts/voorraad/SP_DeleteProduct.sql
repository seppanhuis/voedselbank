DROP PROCEDURE IF EXISTS SP_DeleteProduct;

DELIMITER $$

CREATE PROCEDURE SP_DeleteProduct(
    IN p_id INT
)
BEGIN
    IF EXISTS (
        SELECT 1
        FROM VoedselpakketProduct
        WHERE ProductId = p_id
    ) THEN
        SELECT 0 AS affected, 1 AS blocked, 'Product kan niet worden verwijderd omdat het al in een voedselpakket is gebruikt.' AS message;
    ELSE
        DELETE FROM LeverancierProduct
        WHERE ProductId = p_id;

        DELETE FROM ProductMagazijnLocatie
        WHERE ProductId = p_id;

        DELETE FROM Product
        WHERE Id = p_id;

        SELECT ROW_COUNT() AS affected, 0 AS blocked, NULL AS message;
    END IF;
END$$

DELIMITER ;
