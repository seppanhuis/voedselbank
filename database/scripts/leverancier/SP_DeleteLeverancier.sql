DROP PROCEDURE IF EXISTS SP_DeleteLeverancier;

DELIMITER $$

CREATE PROCEDURE SP_DeleteLeverancier(
    IN p_id INT
)
BEGIN
    DELETE FROM LeverancierProduct
    WHERE LeverancierId = p_id;

    DELETE FROM Leverancier
    WHERE Id = p_id;

    SELECT ROW_COUNT() AS affected;
END$$

DELIMITER ;
