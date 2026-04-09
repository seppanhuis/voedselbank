DROP PROCEDURE IF EXISTS SP_UpdateVoedselpakket;

DELIMITER $$

CREATE PROCEDURE SP_UpdateVoedselpakket(
    IN p_id INT,
    IN p_klantId INT,
    IN p_datumSamengesteld DATE,
    IN p_datumUitgifte DATE,
    IN p_pakketStatus VARCHAR(20)
)
BEGIN
    UPDATE Voedselpakket
    SET
         KlantId = p_klantId
        ,DatumSamengesteld = p_datumSamengesteld
        ,DatumUitgifte = p_datumUitgifte
        ,PakketStatus = p_pakketStatus
        ,DatumGewijzigd = SYSDATE(6)
    WHERE Id = p_id;

    SELECT ROW_COUNT() AS affected;
END$$

DELIMITER ;
