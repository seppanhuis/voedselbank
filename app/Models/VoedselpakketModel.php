<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class VoedselpakketModel extends Model
{
    public function sp_GetAllVoedselpakketten()
    {
        // Haalt alle pakketten op voor het overzichtsscherm.
        try {
            // Deze procedure levert het complete overzicht voor de lijstpagina.
            $pakketten = DB::select('CALL SP_GetAllVoedselpakketten()');

            Log::info('Voedselpakketten opgehaald.', [
                'aantal' => count($pakketten),
            ]);

            // Resultaat direct teruggeven aan de controller.
            return $pakketten;
        } catch (Throwable $e) {
            Log::error('Fout bij ophalen van voedselpakketten.', [
                'melding' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function sp_GetVoedselpakketById($id)
    {
        // Haalt één pakket op via het unieke ID.
        try {
            // Eén pakket ophalen voor detail- en bewerkpagina's.
            return DB::selectOne('CALL SP_GetVoedselpakketById(:id)', [':id' => $id]);
        } catch (Throwable $e) {
            Log::error('Fout bij ophalen van voedselpakket op ID.', [
                'pakket_id' => $id,
                'melding' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function sp_GetVoedselpakketProducten($id)
    {
        // Haalt alle productregels op die bij een pakket horen.
        try {
            // De regels van het pakket komen apart terug voor het overzicht.
            return DB::select('CALL SP_GetVoedselpakketProducten(:id)', [':id' => $id]);
        } catch (Throwable $e) {
            Log::error('Fout bij ophalen van voedselpakketregels.', [
                'pakket_id' => $id,
                'melding' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function sp_CreateVoedselpakket($klantId, $datumSamengesteld, $datumUitgifte, $pakketStatus)
    {
        // Maakt een nieuw pakket aan en geeft het nieuwe pakket-ID terug.
        try {
            // Nieuwe record aanmaken en het nieuwe ID uit de procedure terughalen.
            $row = DB::selectOne(
                'CALL SP_CreateVoedselpakket(:p_klantId, :p_datumSamengesteld, :p_datumUitgifte, :p_pakketStatus)',
                [
                    ':p_klantId' => $klantId,
                    ':p_datumSamengesteld' => $datumSamengesteld,
                    ':p_datumUitgifte' => $datumUitgifte,
                    ':p_pakketStatus' => $pakketStatus,
                ]
            );

            $newId = $row->new_id ?? null;

            Log::info('Voedselpakket aangemaakt.', [
                'pakket_id' => $newId,
                'klant_id' => $klantId,
            ]);

            // Dit ID wordt gebruikt om daarna de productregels toe te voegen.
            return $newId;
        } catch (Throwable $e) {
            Log::error('Fout bij aanmaken van voedselpakket.', [
                'klant_id' => $klantId,
                'melding' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function sp_AddVoedselpakketProduct($pakketId, $productId, $aantal)
    {
        // Voegt een productregel toe aan een pakket.
        try {
            // Koppelt een product aan het pakket en past de voorraad direct aan.
            return DB::selectOne(
                'CALL SP_AddVoedselpakketProduct(:p_pakketId, :p_productId, :p_aantal)',
                [
                    ':p_pakketId' => $pakketId,
                    ':p_productId' => $productId,
                    ':p_aantal' => $aantal,
                ]
            );
        } catch (Throwable $e) {
            Log::error('Fout bij toevoegen van product aan voedselpakket.', [
                'pakket_id' => $pakketId,
                'product_id' => $productId,
                'melding' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function sp_DeleteVoedselpakketProducten($pakketId)
    {
        // Verwijdert alle productregels van een pakket.
        try {
            // Bij verwijderen van regels moet eerst de voorraad teruggezet worden.
            return DB::selectOne('CALL SP_DeleteVoedselpakketProducten(:id)', [':id' => $pakketId]);
        } catch (Throwable $e) {
            Log::error('Fout bij verwijderen van voedselpakketregels.', [
                'pakket_id' => $pakketId,
                'melding' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function sp_UpdateVoedselpakket($pakketId, $klantId, $datumSamengesteld, $datumUitgifte, $pakketStatus)
    {
        // Werkt de hoofdgegevens van een bestaand pakket bij.
        try {
            // Alleen de hoofdgegevens van het pakket worden hier bijgewerkt.
            $row = DB::selectOne(
                'CALL SP_UpdateVoedselpakket(:p_id, :p_klantId, :p_datumSamengesteld, :p_datumUitgifte, :p_pakketStatus)',
                [
                    ':p_id' => $pakketId,
                    ':p_klantId' => $klantId,
                    ':p_datumSamengesteld' => $datumSamengesteld,
                    ':p_datumUitgifte' => $datumUitgifte,
                    ':p_pakketStatus' => $pakketStatus,
                ]
            );

            return $row->affected ?? 0;
        } catch (Throwable $e) {
            Log::error('Fout bij bijwerken van voedselpakket.', [
                'pakket_id' => $pakketId,
                'melding' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function sp_DeleteVoedselpakket($pakketId)
    {
        // Verwijdert een pakket en geeft het resultaat terug.
        try {
            // De procedure geeft zowel het resultaat als een eventuele melding terug.
            $row = DB::selectOne('CALL SP_DeleteVoedselpakket(:id)', [':id' => $pakketId]);

            return [
                // affected = aantal verwijderde hoofdpakketten (0 of 1).
                'affected' => $row->affected ?? 0,
                'message' => $row->message ?? null,
            ];
        } catch (Throwable $e) {
            Log::error('Fout bij verwijderen van voedselpakket.', [
                'pakket_id' => $pakketId,
                'melding' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
