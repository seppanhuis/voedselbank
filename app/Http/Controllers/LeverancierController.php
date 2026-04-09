<?php

namespace App\Http\Controllers;

use App\Models\LeverancierModel;
use Illuminate\Http\Request;

class LeverancierController extends Controller
{
    private LeverancierModel $leverancierModel;

    public function __construct()
    {
        $this->leverancierModel = new LeverancierModel();
    }

    private function authorizeLeverancierBeheer(): void
    {
        // Alleen directie en magazijnmedewerkers mogen leveranciers beheren.
        abort_unless(
            auth()->check() && (auth()->user()->isDirectie() || auth()->user()->isMagazijnmedewerker()),
            403
        );
    }

    private function leverancierValidationRules(?int $id = null): array
    {
        $emailRule = 'required|email|max:150|unique:Leverancier,ContactpersoonEmail';
        $bedrijfsnaamRule = 'required|string|max:120|unique:Leverancier,Bedrijfsnaam';

        if ($id !== null) {
            $emailRule .= ',' . $id . ',Id';
            $bedrijfsnaamRule .= ',' . $id . ',Id';
        }

        return [
            'bedrijfsnaam' => $bedrijfsnaamRule,
            'contactpersoon_naam' => 'required|string|max:120',
            'contactpersoon_email' => $emailRule,
            'telefoon' => 'required|string|max:20',
            'eerstvolgende_levering' => 'required|date',
            'straat' => 'required|string|max:120',
            'huisnummer' => 'required|string|max:10',
            'toevoeging' => 'nullable|string|max:10',
            'postcode' => 'required|string|max:7',
            'plaats' => 'required|string|max:80',
            'land' => 'nullable|string|max:80',
        ];
    }

    private function leverancierValidationMessages(): array
    {
        return [
            'required' => ':attribute is verplicht.',
            'string' => ':attribute moet tekst zijn.',
            'max' => ':attribute mag maximaal :max tekens bevatten.',
            'date' => ':attribute moet een geldige datum zijn.',
            'email' => ':attribute moet een geldig e-mailadres zijn.',
            'unique' => 'Deze waarde is al in gebruik.',
        ];
    }

    private function leverancierValidationAttributes(): array
    {
        return [
            'bedrijfsnaam' => 'bedrijfsnaam',
            'contactpersoon_naam' => 'contactpersoon naam',
            'contactpersoon_email' => 'contactpersoon e-mailadres',
            'telefoon' => 'telefoonnummer',
            'eerstvolgende_levering' => 'eerstvolgende levering',
            'straat' => 'straat',
            'huisnummer' => 'huisnummer',
            'toevoeging' => 'toevoeging',
            'postcode' => 'postcode',
            'plaats' => 'plaats',
            'land' => 'land',
        ];
    }

    public function index(Request $request)
    {
        $this->authorizeLeverancierBeheer();

        // Met ?test=empty kan een lege tabelweergave getest worden zonder databasegegevens.
        $simulateEmpty = collect($request->query())
            ->flatten()
            ->contains(static fn ($value) => strtolower((string) $value) === 'empty');

        return view('leverancier.index', [
            'title' => 'Leveranciers overzicht',
            'leveranciers' => $simulateEmpty ? [] : $this->leverancierModel->sp_GetAllLeveranciers(),
        ]);
    }

    public function create()
    {
        $this->authorizeLeverancierBeheer();

        return view('leverancier.create', [
            'title' => 'Nieuwe leverancier toevoegen',
        ]);
    }

    public function store(Request $request)
    {
        $this->authorizeLeverancierBeheer();

        $validated = $request->validate(
            $this->leverancierValidationRules(),
            $this->leverancierValidationMessages(),
            $this->leverancierValidationAttributes()
        );

        $this->leverancierModel->sp_CreateLeverancier(
            $validated['bedrijfsnaam'],
            $validated['contactpersoon_naam'],
            $validated['contactpersoon_email'],
            $validated['telefoon'],
            $validated['eerstvolgende_levering'],
            $validated['straat'],
            $validated['huisnummer'],
            $validated['toevoeging'] ?? null,
            $validated['postcode'],
            $validated['plaats'],
            $validated['land'] ?? 'Nederland'
        );

        return redirect()
            ->route('leverancier.index')
            ->with('success', 'Leverancier ' . $validated['bedrijfsnaam'] . ' succesvol toegevoegd.');
    }

    public function edit(int $id)
    {
        $this->authorizeLeverancierBeheer();

        $leverancier = $this->leverancierModel->sp_GetLeverancierById($id);

        abort_if(!$leverancier, 404);

        return view('leverancier.edit', [
            'title' => 'Leverancier wijzigen',
            'leverancier' => $leverancier,
        ]);
    }

    public function update(Request $request, int $id)
    {
        $this->authorizeLeverancierBeheer();

        $validated = $request->validate(
            $this->leverancierValidationRules($id),
            $this->leverancierValidationMessages(),
            $this->leverancierValidationAttributes()
        );

        $affected = $this->leverancierModel->sp_UpdateLeverancier(
            $id,
            $validated['bedrijfsnaam'],
            $validated['contactpersoon_naam'],
            $validated['contactpersoon_email'],
            $validated['telefoon'],
            $validated['eerstvolgende_levering'],
            $validated['straat'],
            $validated['huisnummer'],
            $validated['toevoeging'] ?? null,
            $validated['postcode'],
            $validated['plaats'],
            $validated['land'] ?? 'Nederland'
        );

        if ($affected === 0) {
            return back()
                ->withInput()
                ->with('error', 'Er is niets gewijzigd of de leverancier bestaat niet.');
        }

        return redirect()
            ->route('leverancier.index')
            ->with('success', 'Leverancier succesvol bijgewerkt.');
    }

    public function destroy(int $id)
    {
        $this->authorizeLeverancierBeheer();

        $result = $this->leverancierModel->sp_DeleteLeverancier($id);

        if (($result['affected'] ?? 0) > 0) {
            return redirect()
                ->route('leverancier.index')
                ->with('success', 'Leverancier is succesvol verwijderd.');
        }

        return redirect()
            ->route('leverancier.index')
            ->with('error', $result['message'] ?? 'Leverancier is niet verwijderd.');
    }
}
