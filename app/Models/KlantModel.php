<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class KlantModel extends Model
{
    public function sp_GetAllKlanten()
    {
        return DB::select('CALL SP_GetAllKlanten()');
    }

    public function sp_CreateKlant($gezinsnaam, $geboortedatum, $telefoon, $email, $aantalVolwassenen, $aantalKinderen, $aantalBabys, $straat, $huisnummer, $postcode, $plaats)
    {
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

        return $row->new_id;
    }

    public function getAllWensen()
    {
        return DB::select('SELECT Id, WensNaam FROM SpecifiekeWens WHERE IsActief = 1 ORDER BY WensNaam');
    }

    public function addWensesToKlant($klantId, $wensenIds)
    {
        foreach ($wensenIds as $wensenId) {
            DB::insert(
                'INSERT INTO KlantSpecifiekeWens (KlantId, SpecifiekeWensId, IsActief, DatumAangemaakt, DatumGewijzigd) VALUES (?, ?, 1, SYSDATE(6), SYSDATE(6))',
                [$klantId, $wensenId]
            );
        }
    }
}
