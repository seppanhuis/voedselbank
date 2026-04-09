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
        $newIds = array_values(array_unique(array_map('intval', $wensenIds ?? [])));

        if (empty($newIds)) {
            return;
        }

        $this->sp_SyncKlantWensen($klantId, $newIds);
    }

    public function getWensenIdsForKlant($klantId)
    {
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
    }

    public function syncWensenForKlant($klantId, $wensenIds)
    {
        $currentIds = $this->getWensenIdsForKlant($klantId);
        $newIds = array_values(array_unique(array_map('intval', $wensenIds ?? [])));

        sort($currentIds);
        sort($newIds);

        if ($currentIds === $newIds) {
            return false;
        }

        $this->sp_SyncKlantWensen($klantId, $newIds);

        return true;
    }

    public function sp_SyncKlantWensen($klantId, $wensenIds)
    {
        $row = DB::selectOne(
            'CALL SP_SyncKlantWensen(:klantId, :wensIds)',
            [
                ':klantId' => $klantId,
                ':wensIds' => implode(',', $wensenIds),
            ]
        );

        return $row->affected ?? 0;
    }

    public function sp_DeleteKlant($id)
    {
        $row = DB::selectOne(
            'CALL sp_DeleteKlant(:id)',
            [
                ':id' => $id,
            ]
        );

        return $row->affected;
    }

    public function sp_GetKlantById($id)
    {
        return DB::selectOne(
            'CALL SP_GetKlantById(:id)',
            [
                ':id' => $id,
            ]
        );
    }

    public function sp_UpdateKlant($id, $gezinsnaam, $geboortedatum, $telefoon, $email, $aantalVolwassenen, $aantalKinderen, $aantalBabys, $straat, $huisnummer, $postcode, $plaats)
    {
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

        return $row->affected ?? 0;
    }
}
