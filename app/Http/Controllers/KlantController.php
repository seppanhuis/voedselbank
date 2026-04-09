<?php

namespace App\Http\Controllers;

use App\Models\KlantModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class KlantController extends Controller
{
    private const ADMIN_KLANT_EMAIL = 'admin@gmail.com';

    private $klantModel;

    public function __construct()
    {
        $this->klantModel = new KlantModel();
    }

    private function klantValidationRules(?int $id = null): array
    {
        // Bij update mag hetzelfde e-mailadres van de huidige klant blijven staan.
        $emailRule = 'required|email|max:150|unique:Klant,Email';

        if ($id !== null) {
            $emailRule .= ',' . $id . ',Id';
        }

        return [
            'gezinsnaam' => 'required|string|max:120',
            'geboortedatum' => 'nullable|date',
            'telefoon' => 'required|string|max:20',
            'email' => $emailRule,
            'aantal_volwassenen' => 'required|integer|min:0',
            'aantal_kinderen' => 'required|integer|min:0',
            'aantal_babys' => 'required|integer|min:0',
            'straat' => 'required|string|max:120',
            'huisnummer' => 'required|string|max:10',
            'postcode' => 'required|string|max:7',
            'plaats' => 'required|string|max:80',
            'wensen' => 'nullable|array',
            'wensen.*' => 'integer|exists:SpecifiekeWens,Id',
        ];
    }

    private function klantValidationMessages(): array
    {
        // Algemene Nederlandse validatiemeldingen voor klant-formulieren.
        return [
            'required' => ':attribute is verplicht.',
            'string' => ':attribute moet tekst zijn.',
            'max' => ':attribute mag maximaal :max tekens bevatten.',
            'date' => ':attribute moet een geldige datum zijn.',
            'email' => ':attribute moet een geldig e-mailadres zijn.',
            'unique' => 'Dit e-mailadres is al in gebruik. Kies een ander e-mailadres of neem contact op met de beheerder.',
            'integer' => ':attribute moet een heel getal zijn.',
            'min' => ':attribute moet minimaal :min zijn.',
            'array' => ':attribute moet een lijst zijn.',
            'exists' => 'Een of meer gekozen waarden voor :attribute zijn ongeldig.',
        ];
    }

    private function klantValidationAttributes(): array
    {
        return [
            'gezinsnaam' => 'gezinsnaam',
            'geboortedatum' => 'geboortedatum',
            'telefoon' => 'telefoonnummer',
            'email' => 'e-mailadres',
            'aantal_volwassenen' => 'aantal volwassenen',
            'aantal_kinderen' => 'aantal kinderen',
            'aantal_babys' => 'aantal baby\'s',
            'straat' => 'straat',
            'huisnummer' => 'huisnummer',
            'postcode' => 'postcode',
            'plaats' => 'plaats',
            'wensen' => 'wensen',
            'wensen.*' => 'wens',
        ];
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Als in de URL een querywaarde "empty" staat, simuleer een lege dataset.
        $simulateEmpty = collect($request->query())
            ->flatten()
            ->contains(static fn ($value) => strtolower((string) $value) === 'empty');

        // Overzichtspagina met alle klanten.
        $klanten = $simulateEmpty ? [] : $this->klantModel->sp_GetAllKlanten();

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
        // Nodig om checkboxen/selecties voor wensen te vullen.
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
        // Valideer invoer met Nederlandse meldingen.
        $data = $request->validate(
            $this->klantValidationRules(),
            $this->klantValidationMessages(),
            $this->klantValidationAttributes()
        );

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

        // Eventuele wensen direct koppelen aan de zojuist aangemaakte klant.

        if (!empty($data['wensen'])) {
            // Koppel alleen wensen als er ook echt iets is gekozen.
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

        // Voor edit zijn alle wensen + huidige selectie nodig.
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
        $validated = $request->validate(
            $this->klantValidationRules((int) $id),
            $this->klantValidationMessages(),
            $this->klantValidationAttributes()
        );

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

        // Controleer los of de gekoppelde wensen zijn aangepast.
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
        try {
            $klant = $this->klantModel->sp_GetKlantById($id);

            // Voorkom dat een directie-gebruiker het admin-klantaccount verwijdert.
            if (
                auth()->check()
                && auth()->user()->isDirectie()
                && $klant
                && strtolower((string) ($klant->Email ?? '')) === self::ADMIN_KLANT_EMAIL
            ) {
                return redirect()->route('klant.index')
                    ->with('error', 'Het admin-account in de klantentabel mag niet verwijderd worden.');
            }

            // Verwijderen en op basis van affected rows feedback tonen.
            $result = $this->klantModel->sp_DeleteKlant($id);

            if ($result > 0) {
                return redirect()->route('klant.index')
                    ->with('success', 'Klant is succesvol verwijdert');
            }

            return redirect()->route('klant.index')
                ->with('error', 'Klant is niet verwijdert');
        } catch (Throwable $e) {
            Log::error('Klant verwijderen mislukt.', [
                'klant_id' => $id,
                'melding' => $e->getMessage(),
            ]);

            return redirect()->route('klant.index')
                ->with('error', 'Klant kan niet worden verwijderd omdat er nog gekoppelde gegevens bestaan.');
        }
    }
}
