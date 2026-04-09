DELIMITER $$

DROP PROCEDURE IF EXISTS sp_DeleteKlant$$

CREATE PROCEDURE sp_DeleteKlant(
    IN p_id INT
)
BEGIN
    DECLARE v_affected INT DEFAULT 0;

    START TRANSACTION;

    DELETE FROM KlantSpecifiekeWens
    WHERE KlantId = p_id;

    DELETE FROM Klant
    WHERE Id = p_id;

    SET v_affected = ROW_COUNT();

    COMMIT;

    SELECT v_affected AS affected;
END$$

DELIMITER ;
