DROP PROCEDURE IF EXISTS SP_GetKlantWensenIds;

DELIMITER $$

CREATE PROCEDURE SP_GetKlantWensenIds(
    IN p_klantId INT
)
BEGIN
    SELECT KSW.SpecifiekeWensId
    FROM KlantSpecifiekeWens AS KSW
    WHERE KSW.KlantId = p_klantId
      AND KSW.IsActief = 1
    ORDER BY KSW.SpecifiekeWensId;
END$$

DELIMITER ;
