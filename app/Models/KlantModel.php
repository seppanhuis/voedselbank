<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class KlantModel extends Model
{
    public function sp_GetAllKlanten()
    {
        try {
            // Haal alle klanten op via stored procedure :).
            $klanten = DB::select('CALL SP_GetAllKlanten()');

            Log::info('Klantgegevens opgehaald.', [
                'aantal' => count($klanten),
            ]);

            return $klanten;
        } catch (Throwable $e) {
            Log::error('Fout bij ophalen van klanten.', [
                'melding' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function sp_CreateKlant($gezinsnaam, $geboortedatum, $telefoon, $email, $aantalVolwassenen, $aantalKinderen, $aantalBabys, $straat, $huisnummer, $postcode, $plaats)
    {
        try {
            // Maak een klant aan en lees het nieuwe ID terug.
            $row = DB::selectOne(
                'CALL SP_CreateKlant(:p_gezinsnaam, :p_geboortedatum, :p_telefoon, :p_email, :p_aantalVolwassenen, :p_aantalKinderen, :p_aantalBabys, :p_straat, :p_huisnummer, :p_postcode, :p_plaats)',
                [
                    ':p_gezinsnaam' => $gezinsnaam,
                    ':p_geboortedatum' => $geboortedatum,
                    ':p_telefoon' => $telefoon,
                    ':p_email' => $email,
                    ':p_aantalVolwassenen' => $aantalVolwassenen,
                    ':p_aantalKinderen' => $aantalKinderen,
                    ':p_aantalBabys' => $aantalBabys,
                    ':p_straat' => $straat,
                    ':p_huisnummer' => $huisnummer,
                    ':p_postcode' => $postcode,
                    ':p_plaats' => $plaats,
                ]
            );

            $newId = $row->new_id ?? null;

            Log::info('Nieuwe klant toegevoegd.', [
                'klant_id' => $newId,
                'email' => $email,
            ]);

            return $newId;
        } catch (Throwable $e) {
            Log::error('Fout bij toevoegen van klant.', [
                'email' => $email,
                'melding' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function getAllWensen()
    {
        try {
            // Alleen actieve wensen tonen, alfabetisch gesorteerd.
            return DB::select('SELECT Id, WensNaam FROM SpecifiekeWens WHERE IsActief = 1 ORDER BY WensNaam');
        } catch (Throwable $e) {
            Log::error('Fout bij ophalen van wensen.', [
                'melding' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function addWensesToKlant($klantId, $wensenIds)
    {
        // Normaliseer de invoer zodat alleen unieke numerieke ID's overblijven.
        $newIds = array_values(array_unique(array_map('intval', $wensenIds ?? [])));

        if (empty($newIds)) {
            return;
        }

        $this->sp_SyncKlantWensen($klantId, $newIds);
    }

    public function getWensenIdsForKlant($klantId)
    {
        try {
            // Gebruik een platte int-array zodat vergelijken/syncen betrouwbaar is.
            return collect(DB::select(
                'CALL SP_GetKlantWensenIds(:klantId)',
                [
                    ':klantId' => $klantId,
                ]
            ))
                ->pluck('SpecifiekeWensId')
                ->map(static fn ($id) => (int) $id)
                ->values()
                ->all();
        } catch (Throwable $e) {
            Log::error('Fout bij ophalen van wensen van klant.', [
                'klant_id' => $klantId,
                'melding' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function syncWensenForKlant($klantId, $wensenIds)
    {
        $currentIds = $this->getWensenIdsForKlant($klantId);
        $newIds = array_values(array_unique(array_map('intval', $wensenIds ?? [])));

        sort($currentIds);
        sort($newIds);

        if ($currentIds === $newIds) {
            // Geen wijziging in wensen, dus geen database-update nodig.
            return false;
        }

        $this->sp_SyncKlantWensen($klantId, $newIds);

        return true;
    }

    public function sp_SyncKlantWensen($klantId, $wensenIds)
    {
        try {
            // De procedure verwacht een komma-gescheiden lijst met wens-ID's.
            $row = DB::selectOne(
                'CALL SP_SyncKlantWensen(:klantId, :wensIds)',
                [
                    ':klantId' => $klantId,
                    ':wensIds' => implode(',', $wensenIds),
                ]
            );

            return $row->affected ?? 0;
        } catch (Throwable $e) {
            Log::error('Fout bij synchroniseren van klantwensen.', [
                'klant_id' => $klantId,
                'melding' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function sp_DeleteKlant($id)
    {
        try {
            // Verwijderen gebeurt in de database via de procedure.
            $row = DB::selectOne(
                'CALL sp_DeleteKlant(:id)',
                [
                    ':id' => $id,
                ]
            );

            $affected = $row->affected ?? 0;

            Log::info('Klant verwijderd.', [
                'klant_id' => $id,
                'affected' => $affected,
            ]);

            return $affected;
        } catch (Throwable $e) {
            Log::error('Fout bij verwijderen van klant.', [
                'klant_id' => $id,
                'melding' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function sp_GetKlantById($id)
    {
        try {
            // Een enkele klant ophalen voor de edit/detail pagina.
            $klant = DB::selectOne(
                'CALL SP_GetKlantById(:id)',
                [
                    ':id' => $id,
                ]
            );

            Log::info('Klantgegevens opgehaald op ID.', [
                'klant_id' => $id,
                'gevonden' => (bool) $klant,
            ]);

            return $klant;
        } catch (Throwable $e) {
            Log::error('Fout bij ophalen van klant op ID.', [
                'klant_id' => $id,
                'melding' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function sp_UpdateKlant($id, $gezinsnaam, $geboortedatum, $telefoon, $email, $aantalVolwassenen, $aantalKinderen, $aantalBabys, $straat, $huisnummer, $postcode, $plaats)
    {
        try {
            // Werk een bestaande klant bij op basis van ID.
            $row = DB::selectOne(
                'CALL SP_UpdateKlant(:id, :gezinsnaam, :geboortedatum, :telefoon, :email, :aantalVolwassenen, :aantalKinderen, :aantalBabys, :straat, :huisnummer, :postcode, :plaats)',
                [
                    ':id' => $id,
                    ':gezinsnaam' => $gezinsnaam,
                    ':geboortedatum' => $geboortedatum,
                    ':telefoon' => $telefoon,
                    ':email' => $email,
                    ':aantalVolwassenen' => $aantalVolwassenen,
                    ':aantalKinderen' => $aantalKinderen,
                    ':aantalBabys' => $aantalBabys,
                    ':straat' => $straat,
                    ':huisnummer' => $huisnummer,
                    ':postcode' => $postcode,
                    ':plaats' => $plaats,
                ]
            );

            $affected = $row->affected ?? 0;

            Log::info('Klant bijgewerkt.', [
                'klant_id' => $id,
                'affected' => $affected,
            ]);

            return $affected;
        } catch (Throwable $e) {
            Log::error('Fout bij bijwerken van klant.', [
                'klant_id' => $id,
                'melding' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
