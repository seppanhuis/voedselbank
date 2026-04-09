DROP PROCEDURE IF EXISTS SP_CreateVoedselpakket;

DELIMITER $$

CREATE PROCEDURE SP_CreateVoedselpakket(
    IN p_klantId INT,
    IN p_datumSamengesteld DATE,
    IN p_datumUitgifte DATE,
    IN p_pakketStatus VARCHAR(20)
)
BEGIN
    INSERT INTO Voedselpakket (
        KlantId,
        DatumSamengesteld,
        DatumUitgifte,
        PakketStatus,
        IsActief,
        DatumAangemaakt,
        DatumGewijzigd
    ) VALUES (
        p_klantId,
        p_datumSamengesteld,
        p_datumUitgifte,
        p_pakketStatus,
        1,
        SYSDATE(6),
        SYSDATE(6)
    );

    SELECT LAST_INSERT_ID() AS new_id;
END$$

DELIMITER ;
