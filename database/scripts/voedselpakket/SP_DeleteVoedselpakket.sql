DROP PROCEDURE IF EXISTS SP_DeleteVoedselpakket;

DELIMITER $$

CREATE PROCEDURE SP_DeleteVoedselpakket(
    IN p_id INT
)
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM Voedselpakket
        WHERE Id = p_id
    ) THEN
        SELECT 0 AS affected, 'Voedselpakket is niet verwijderd.' AS message;
    ELSE
        UPDATE Product AS P
        INNER JOIN VoedselpakketProduct AS VPP
            ON VPP.ProductId = P.Id
        SET
             P.AantalOpVoorraad = P.AantalOpVoorraad + VPP.Aantal
            ,P.DatumGewijzigd = SYSDATE(6)
        WHERE VPP.VoedselpakketId = p_id;

        DELETE FROM VoedselpakketProduct
        WHERE VoedselpakketId = p_id;

        DELETE FROM Voedselpakket
        WHERE Id = p_id;

        SELECT ROW_COUNT() AS affected, NULL AS message;
    END IF;
END$$

DELIMITER ;
