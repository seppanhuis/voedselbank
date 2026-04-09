<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProductModel extends Model
{
    public function sp_GetAllProducten()
    {
        try {
            $producten = DB::select('CALL SP_GetAllProducten()');

            Log::info('Producten opgehaald.', [
                'aantal' => count($producten),
            ]);

            return $producten;
        } catch (Throwable $e) {
            Log::error('Fout bij ophalen van producten.', [
                'melding' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function sp_GetProductById($id)
    {
        try {
            $product = DB::selectOne('CALL SP_GetProductById(:id)', [':id' => $id]);

            Log::info('Productgegevens opgehaald op ID.', [
                'product_id' => $id,
                'gevonden' => (bool) $product,
            ]);

            return $product;
        } catch (Throwable $e) {
            Log::error('Fout bij ophalen van product op ID.', [
                'product_id' => $id,
                'melding' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function getAllCategorieen()
    {
        try {
            return DB::select('SELECT Id, CategorieNaam FROM Categorie WHERE IsActief = 1 ORDER BY CategorieNaam');
        } catch (Throwable $e) {
            Log::error('Fout bij ophalen van categorieën.', [
                'melding' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function sp_CreateProduct($categorieId, $productNaam, $ean, $aantalOpVoorraad, $eenheid, $houdbaarTot)
    {
        try {
            $row = DB::selectOne(
                'CALL SP_CreateProduct(:p_categorieId, :p_productNaam, :p_ean, :p_aantalOpVoorraad, :p_eenheid, :p_houdbaarTot)',
                [
                    ':p_categorieId' => $categorieId,
                    ':p_productNaam' => $productNaam,
                    ':p_ean' => $ean,
                    ':p_aantalOpVoorraad' => $aantalOpVoorraad,
                    ':p_eenheid' => $eenheid,
                    ':p_houdbaarTot' => $houdbaarTot,
                ]
            );

            $newId = $row->new_id ?? null;

            Log::info('Nieuw product toegevoegd.', [
                'product_id' => $newId,
                'productnaam' => $productNaam,
            ]);

            return $newId;
        } catch (Throwable $e) {
            Log::error('Fout bij toevoegen van product.', [
                'productnaam' => $productNaam,
                'melding' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function sp_UpdateProduct($id, $categorieId, $productNaam, $ean, $aantalOpVoorraad, $eenheid, $houdbaarTot)
    {
        try {
            $row = DB::selectOne(
                'CALL SP_UpdateProduct(:p_id, :p_categorieId, :p_productNaam, :p_ean, :p_aantalOpVoorraad, :p_eenheid, :p_houdbaarTot)',
                [
                    ':p_id' => $id,
                    ':p_categorieId' => $categorieId,
                    ':p_productNaam' => $productNaam,
                    ':p_ean' => $ean,
                    ':p_aantalOpVoorraad' => $aantalOpVoorraad,
                    ':p_eenheid' => $eenheid,
                    ':p_houdbaarTot' => $houdbaarTot,
                ]
            );

            $affected = $row->affected ?? 0;

            Log::info('Product bijgewerkt.', [
                'product_id' => $id,
                'affected' => $affected,
            ]);

            return $affected;
        } catch (Throwable $e) {
            Log::error('Fout bij bijwerken van product.', [
                'product_id' => $id,
                'melding' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function sp_DeleteProduct($id): array
    {
        try {
            $row = DB::selectOne('CALL SP_DeleteProduct(:id)', [':id' => $id]);

            return [
                'affected' => $row->affected ?? 0,
                'blocked' => $row->blocked ?? 0,
                'message' => $row->message ?? null,
            ];
        } catch (Throwable $e) {
            Log::error('Fout bij verwijderen van product.', [
                'product_id' => $id,
                'melding' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
