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
        $wensen = $this->klantModel->getAllWensen();

        return view('klant.create', [
            'title' => 'Nieuwe klant toevoegen',
            'wensen' => $wensen
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'gezinsnaam' => 'required|string|max:120',
            'geboortedatum' => 'nullable|date',
            'telefoon' => 'required|string|max:20',
            'email' => 'required|email|max:150|unique:Klant,Email',
            'aantal_volwassenen' => 'required|integer|min:0',
            'aantal_kinderen' => 'required|integer|min:0',
            'aantal_babys' => 'required|integer|min:0',
            'straat' => 'required|string|max:120',
            'huisnummer' => 'required|string|max:10',
            'postcode' => 'required|string|max:7',
            'plaats' => 'required|string|max:80',
            'wensen' => 'nullable|array',
            'wensen.*' => 'integer|exists:SpecifiekeWens,Id',
        ], [
            'email.unique' => 'Dit e-mailadres is al in gebruik. Kies een ander e-mailadres of neem contact op met de beheerder.',
        ]);

        $newId = $this->klantModel->sp_CreateKlant(
            $data['gezinsnaam'],
            $data['geboortedatum'] ?? null,
            $data['telefoon'],
            $data['email'],
            $data['aantal_volwassenen'],
            $data['aantal_kinderen'],
            $data['aantal_babys'],
            $data['straat'],
            $data['huisnummer'],
            $data['postcode'],
            $data['plaats']
        );

        if (!empty($data['wensen'])) {
            $this->klantModel->addWensesToKlant($newId, $data['wensen']);
        }

        return redirect()
            ->route('klant.index')
            ->with('success', 'Klant ' . $data['gezinsnaam'] . ' succesvol toegevoegd');
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
    public function edit($id)
    {
        $klant = $this->klantModel->sp_GetKlantById($id);
        abort_if(!$klant, 404);

        $wensen = $this->klantModel->getAllWensen();
        $selectedWensen = $this->klantModel->getWensenIdsForKlant($id);

        return view('klant.edit', [
            'title' => 'Klant wijzigen',
            'klant' => $klant,
            'wensen' => $wensen,
            'selectedWensen' => $selectedWensen,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'gezinsnaam' => 'required|string|max:120',
            'geboortedatum' => 'nullable|date',
            'telefoon' => 'required|string|max:20',
            'email' => 'required|email|max:150|unique:Klant,Email,' . $id . ',Id',
            'aantal_volwassenen' => 'required|integer|min:0',
            'aantal_kinderen' => 'required|integer|min:0',
            'aantal_babys' => 'required|integer|min:0',
            'straat' => 'required|string|max:120',
            'huisnummer' => 'required|string|max:10',
            'postcode' => 'required|string|max:7',
            'plaats' => 'required|string|max:80',
            'wensen' => 'nullable|array',
            'wensen.*' => 'integer|exists:SpecifiekeWens,Id',
        ]);

        $affected = $this->klantModel->sp_UpdateKlant(
            $id,
            $validated['gezinsnaam'],
            $validated['geboortedatum'] ?? null,
            $validated['telefoon'],
            $validated['email'],
            $validated['aantal_volwassenen'],
            $validated['aantal_kinderen'],
            $validated['aantal_babys'],
            $validated['straat'],
            $validated['huisnummer'],
            $validated['postcode'],
            $validated['plaats']
        );

        $wensenChanged = $this->klantModel->syncWensenForKlant($id, $validated['wensen'] ?? []);

        if ($affected === 0 && !$wensenChanged) {
            return back()
                ->withInput()
                ->with('error', 'Er is niets gewijzigd of de klant bestaat niet.');
        }

        return redirect()
            ->route('klant.index')
            ->with('success', 'Klant succesvol bijgewerkt.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $result = $this->klantModel->sp_DeleteKlant($id);

        if ($result > 0) {
            return redirect()->route('klant.index')
                ->with('success', 'Klant is succesvol verwijdert');
        }

        return redirect()->route('klant.index')
            ->with('error', 'Klant is niet verwijdert');
    }
}
