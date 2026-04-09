DROP PROCEDURE IF EXISTS SP_AddVoedselpakketProduct;

DELIMITER $$

CREATE PROCEDURE SP_AddVoedselpakketProduct(
    IN p_voedselpakketId INT,
    IN p_productId INT,
    IN p_aantal INT
)
BEGIN
    DECLARE v_voorraad INT UNSIGNED;

    SELECT AantalOpVoorraad
    INTO v_voorraad
    FROM Product
    WHERE Id = p_productId
    FOR UPDATE;

    IF v_voorraad < p_aantal THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Er is onvoldoende voorraad voor dit product.';
    END IF;

    INSERT INTO VoedselpakketProduct (
        VoedselpakketId,
        ProductId,
        Aantal,
        IsActief,
        DatumAangemaakt,
        DatumGewijzigd
    ) VALUES (
        p_voedselpakketId,
        p_productId,
        p_aantal,
        1,
        SYSDATE(6),
        SYSDATE(6)
    );

    UPDATE Product
    SET
         AantalOpVoorraad = AantalOpVoorraad - p_aantal
        ,DatumGewijzigd = SYSDATE(6)
    WHERE Id = p_productId;

    SELECT ROW_COUNT() AS affected;
END$$

DELIMITER ;
