<?php

namespace App\Http\Controllers;

use App\Models\KlantModel;
use Illuminate\Http\Request;

class KlantController extends Controller
{
    private $klantModel;

    public function __construct()
    {
        $this->klantModel = new KlantModel();
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $klanten = $this->klantModel->sp_GetAllKlanten();

        return view('klant.index',[
            'title' => 'Klanten overzicht',
            'klanten' => $klanten
            ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(KlantModel $klantModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KlantModel $klantModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KlantModel $klantModel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KlantModel $klantModel)
    {
        //
    }
}
