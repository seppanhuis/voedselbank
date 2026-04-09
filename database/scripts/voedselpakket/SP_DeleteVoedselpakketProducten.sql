DROP PROCEDURE IF EXISTS SP_DeleteVoedselpakketProducten;

DELIMITER $$

CREATE PROCEDURE SP_DeleteVoedselpakketProducten(
    IN p_id INT
)
BEGIN
    UPDATE Product AS P
    INNER JOIN VoedselpakketProduct AS VPP
        ON VPP.ProductId = P.Id
    SET
         P.AantalOpVoorraad = P.AantalOpVoorraad + VPP.Aantal
        ,P.DatumGewijzigd = SYSDATE(6)
    WHERE VPP.VoedselpakketId = p_id;

    DELETE FROM VoedselpakketProduct
    WHERE VoedselpakketId = p_id;

    SELECT ROW_COUNT() AS affected;
END$$

DELIMITER ;
