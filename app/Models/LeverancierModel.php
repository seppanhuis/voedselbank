<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class LeverancierModel extends Model
{
    public function sp_GetAllLeveranciers()
    {
        try {
            // Lees overzicht via stored procedure.
            $leveranciers = DB::select('CALL SP_GetAllLeveranciers()');

            Log::info('Leveranciers opgehaald.', [
                'aantal' => count($leveranciers),
            ]);

            return $leveranciers;
        } catch (Throwable $e) {
            Log::error('Fout bij ophalen van leveranciers.', [
                'melding' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function sp_GetLeverancierById($id)
    {
        try {
            $leverancier = DB::selectOne('CALL SP_GetLeverancierById(:id)', [':id' => $id]);

            Log::info('Leveranciergegevens opgehaald op ID.', [
                'leverancier_id' => $id,
                'gevonden' => (bool) $leverancier,
            ]);

            return $leverancier;
        } catch (Throwable $e) {
            Log::error('Fout bij ophalen van leverancier op ID.', [
                'leverancier_id' => $id,
                'melding' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function sp_CreateLeverancier($bedrijfsnaam, $contactpersoonNaam, $contactpersoonEmail, $telefoon, $eerstvolgendeLevering, $straat, $huisnummer, $toevoeging, $postcode, $plaats, $land)
    {
        try {
            // Laat de database leverancier + adres in één procedure aanmaken.
            $row = DB::selectOne(
                'CALL SP_CreateLeverancier(:p_bedrijfsnaam, :p_contactpersoonNaam, :p_contactpersoonEmail, :p_telefoon, :p_eerstvolgendeLevering, :p_straat, :p_huisnummer, :p_toevoeging, :p_postcode, :p_plaats, :p_land)',
                [
                    ':p_bedrijfsnaam' => $bedrijfsnaam,
                    ':p_contactpersoonNaam' => $contactpersoonNaam,
                    ':p_contactpersoonEmail' => $contactpersoonEmail,
                    ':p_telefoon' => $telefoon,
                    ':p_eerstvolgendeLevering' => $eerstvolgendeLevering,
                    ':p_straat' => $straat,
                    ':p_huisnummer' => $huisnummer,
                    ':p_toevoeging' => $toevoeging,
                    ':p_postcode' => $postcode,
                    ':p_plaats' => $plaats,
                    ':p_land' => $land,
                ]
            );

            $newId = $row->new_id ?? null;

            Log::info('Nieuwe leverancier toegevoegd.', [
                'leverancier_id' => $newId,
                'bedrijfsnaam' => $bedrijfsnaam,
            ]);

            return $newId;
        } catch (Throwable $e) {
            Log::error('Fout bij toevoegen van leverancier.', [
                'bedrijfsnaam' => $bedrijfsnaam,
                'melding' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function sp_UpdateLeverancier($id, $bedrijfsnaam, $contactpersoonNaam, $contactpersoonEmail, $telefoon, $eerstvolgendeLevering, $straat, $huisnummer, $toevoeging, $postcode, $plaats, $land)
    {
        try {
            $row = DB::selectOne(
                'CALL SP_UpdateLeverancier(:p_id, :p_bedrijfsnaam, :p_contactpersoonNaam, :p_contactpersoonEmail, :p_telefoon, :p_eerstvolgendeLevering, :p_straat, :p_huisnummer, :p_toevoeging, :p_postcode, :p_plaats, :p_land)',
                [
                    ':p_id' => $id,
                    ':p_bedrijfsnaam' => $bedrijfsnaam,
                    ':p_contactpersoonNaam' => $contactpersoonNaam,
                    ':p_contactpersoonEmail' => $contactpersoonEmail,
                    ':p_telefoon' => $telefoon,
                    ':p_eerstvolgendeLevering' => $eerstvolgendeLevering,
                    ':p_straat' => $straat,
                    ':p_huisnummer' => $huisnummer,
                    ':p_toevoeging' => $toevoeging,
                    ':p_postcode' => $postcode,
                    ':p_plaats' => $plaats,
                    ':p_land' => $land,
                ]
            );

            $affected = $row->affected ?? 0;

            Log::info('Leverancier bijgewerkt.', [
                'leverancier_id' => $id,
                'affected' => $affected,
            ]);

            return $affected;
        } catch (Throwable $e) {
            Log::error('Fout bij bijwerken van leverancier.', [
                'leverancier_id' => $id,
                'melding' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function sp_DeleteLeverancier($id): array
    {
        try {
            // Procedure retourneert zowel affected rows als eventuele blokkade-melding.
            $row = DB::selectOne('CALL SP_DeleteLeverancier(:id)', [':id' => $id]);

            $result = [
                'affected' => $row->affected ?? 0,
                'message' => $row->message ?? null,
            ];

            Log::info('Leverancier verwijderd.', [
                'leverancier_id' => $id,
                'affected' => $result['affected'],
            ]);

            return $result;
        } catch (Throwable $e) {
            Log::error('Fout bij verwijderen van leverancier.', [
                'leverancier_id' => $id,
                'melding' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
