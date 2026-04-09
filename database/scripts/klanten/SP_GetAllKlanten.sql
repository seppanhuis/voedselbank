DROP PROCEDURE IF EXISTS SP_GetAllKlanten;

DELIMITER $$

CREATE PROCEDURE SP_GetAllKlanten()
BEGIN
	SELECT
		 KL.Id
		,KL.KlantAdresId
		,KL.GezinsNaam
		,KL.GeboorteDatum
		,KL.Telefoon
		,KL.Email
		,KL.AantalVolwassenen
		,KL.AantalKinderen
		,KL.AantalBabys

		,KA.Straat
		,KA.Huisnummer
		,KA.Toevoeging
		,KA.Postcode
		,KA.Plaats
		,KA.Land

		,GROUP_CONCAT(DISTINCT SW.WensNaam ORDER BY SW.WensNaam SEPARATOR ', ') AS Wensen
	FROM Klant AS KL
	INNER JOIN KlantAdres AS KA
		ON KA.Id = KL.KlantAdresId
	LEFT JOIN KlantSpecifiekeWens AS KSW
		ON KSW.KlantId = KL.Id
	LEFT JOIN SpecifiekeWens AS SW
		ON SW.Id = KSW.SpecifiekeWensId
	GROUP BY
		 KL.Id
		,KL.KlantAdresId
		,KL.GezinsNaam
		,KL.GeboorteDatum
		,KL.Telefoon
		,KL.Email
		,KL.AantalVolwassenen
		,KL.AantalKinderen
		,KL.AantalBabys
		,KA.Straat
		,KA.Huisnummer
		,KA.Toevoeging
		,KA.Postcode
		,KA.Plaats
		,KA.Land
	ORDER BY KL.Id;
END$$

DELIMITER ;
