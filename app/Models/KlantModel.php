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
}
