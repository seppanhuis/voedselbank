DROP PROCEDURE IF EXISTS SP_SyncKlantWensen;

DELIMITER $$

CREATE PROCEDURE SP_SyncKlantWensen(
    IN p_klantId INT,
    IN p_wensIds TEXT
)
BEGIN
    DELETE FROM KlantSpecifiekeWens
    WHERE KlantId = p_klantId;

    IF p_wensIds IS NOT NULL AND TRIM(p_wensIds) <> '' THEN
        INSERT INTO KlantSpecifiekeWens (
            KlantId,
            SpecifiekeWensId,
            IsActief,
            DatumAangemaakt,
            DatumGewijzigd
        )
        SELECT
             p_klantId
            ,SW.Id
            ,1
            ,SYSDATE(6)
            ,SYSDATE(6)
        FROM SpecifiekeWens AS SW
        WHERE SW.IsActief = 1
          AND FIND_IN_SET(SW.Id, p_wensIds) > 0;
    END IF;

    SELECT ROW_COUNT() AS affected;
END$$

DELIMITER ;
