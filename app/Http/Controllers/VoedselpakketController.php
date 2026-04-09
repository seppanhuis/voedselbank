<?php

namespace App\Http\Controllers;

use App\Models\KlantModel;
use App\Models\ProductModel;
use App\Models\VoedselpakketModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class VoedselpakketController extends Controller
{
    private VoedselpakketModel $voedselpakketModel;

    private KlantModel $klantModel;

    private ProductModel $productModel;

    public function __construct()
    {
        // Deze controller werkt met aparte modellen voor pakketten, klanten en producten.
        $this->voedselpakketModel = new VoedselpakketModel();
        $this->klantModel = new KlantModel();
        $this->productModel = new ProductModel();
    }

    private function authorizeVoedselpakketBeheer(): void
    {
        // Alleen vrijwilligers en directie mogen voedselpakketten beheren.
        abort_unless(
            auth()->check() && (auth()->user()->isDirectie() || auth()->user()->isVrijwilliger()),
            403
        );
    }

    private function pakketValidationRules(): array
    {
        return [
            'klant_id' => 'required|integer|exists:Klant,Id',
            'datum_samengesteld' => 'required|date',
            'datum_uitgifte' => 'nullable|date|after_or_equal:datum_samengesteld',
            'pakket_regels' => 'required|array|min:1',
            'pakket_regels.*.product_id' => 'required|integer|distinct|exists:Product,Id',
            'pakket_regels.*.aantal' => 'required|integer|min:1',
        ];
    }

    private function pakketValidationMessages(): array
    {
        return [
            'required' => ':attribute is verplicht.',
            'integer' => ':attribute moet een heel getal zijn.',
            'exists' => 'Een geselecteerd item bestaat niet.',
            'date' => ':attribute moet een geldige datum zijn.',
            'after_or_equal' => ':attribute moet gelijk zijn aan of na de samenstellingsdatum liggen.',
            'array' => ':attribute moet een lijst zijn.',
            'min' => ':attribute moet minimaal :min bevatten.',
            'distinct' => 'Een product mag maar één keer in hetzelfde pakket voorkomen.',
        ];
    }

    private function pakketValidationAttributes(): array
    {
        return [
            'klant_id' => 'klant',
            'datum_samengesteld' => 'datum samengesteld',
            'datum_uitgifte' => 'datum uitgifte',
            'pakket_regels' => 'pakketregels',
            'pakket_regels.*.product_id' => 'product',
            'pakket_regels.*.aantal' => 'aantal',
        ];
    }

    public function index(Request $request)
    {
        $this->authorizeVoedselpakketBeheer();

        // Testmodus: als in de query 'empty' staat, tonen we expres geen pakketten.
        $simulateEmpty = collect($request->query())
            ->flatten()
            ->contains(static fn ($value) => strtolower((string) $value) === 'empty');

        return view('voedselpakket.index', [
            'title' => 'Voedselpakketten overzicht',
            'pakketten' => $simulateEmpty ? [] : $this->voedselpakketModel->sp_GetAllVoedselpakketten(),
        ]);
    }

    public function create()
    {
        $this->authorizeVoedselpakketBeheer();

        // Voor het samenstellen van een pakket hebben we klanten en producten nodig.
        return view('voedselpakket.create', [
            'title' => 'Voedselpakket samenstellen',
            'klanten' => $this->klantModel->sp_GetAllKlanten(),
            'producten' => $this->productModel->sp_GetAllProducten(),
        ]);
    }

    public function store(Request $request)
    {
        $this->authorizeVoedselpakketBeheer();

        // Basisvalidatie voor pakketgegevens en de regels per product.
        $validated = $request->validate(
            $this->pakketValidationRules(),
            $this->pakketValidationMessages(),
            $this->pakketValidationAttributes()
        );

        // Producten worden eerst ingelezen zodat we voorraad kunnen controleren.
        $producten = collect($this->productModel->sp_GetAllProducten())->keyBy('Id');

        // Valideer voorraad vooraf, zodat we geen halve transacties krijgen.
        foreach ($validated['pakket_regels'] as $index => $regel) {
            $product = $producten->get((int) $regel['product_id']);

            if (!$product) {
                throw ValidationException::withMessages([
                    'pakket_regels.' . $index . '.product_id' => 'Het gekozen product bestaat niet.',
                ]);
            }

            if ((int) $regel['aantal'] > (int) $product->AantalOpVoorraad) {
                throw ValidationException::withMessages([
                    'pakket_regels.' . $index . '.aantal' => 'Er is onvoldoende voorraad voor ' . $product->ProductNaam . '.',
                ]);
            }
        }

        try {
            // Alles wordt in een transactie gedaan, zodat pakket en regels samen slagen of falen.
            DB::beginTransaction();

            // De status hangt af van het feit of het pakket al uitgegeven is.
            $pakketStatus = $validated['datum_uitgifte'] ? 'Uitgereikt' : 'Samengesteld';
            $pakketId = $this->voedselpakketModel->sp_CreateVoedselpakket(
                $validated['klant_id'],
                $validated['datum_samengesteld'],
                $validated['datum_uitgifte'] ?? null,
                $pakketStatus
            );

            // Daarna worden alle pakketregels een voor een opgeslagen.
            foreach ($validated['pakket_regels'] as $regel) {
                $this->voedselpakketModel->sp_AddVoedselpakketProduct(
                    $pakketId,
                    (int) $regel['product_id'],
                    (int) $regel['aantal']
                );
            }

            DB::commit();

            return redirect()
                ->route('voedselpakket.show', $pakketId)
                ->with('success', 'Voedselpakket succesvol samengesteld.');
        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Fout bij opslaan van voedselpakket.', [
                'melding' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Voedselpakket kon niet worden opgeslagen. Probeer het opnieuw.');
        }
    }

    public function show(int $id)
    {
        $this->authorizeVoedselpakketBeheer();

        // Detailgegevens van het pakket ophalen; zonder pakket stoppen we met 404.
        $pakket = $this->voedselpakketModel->sp_GetVoedselpakketById($id);

        abort_if(!$pakket, 404);

        return view('voedselpakket.show', [
            'title' => 'Voedselpakket ' . $pakket->Id,
            'pakket' => $pakket,
            'regels' => $this->voedselpakketModel->sp_GetVoedselpakketProducten($id),
        ]);
    }

    public function edit(int $id)
    {
        $this->authorizeVoedselpakketBeheer();

        // Eerst controleren of het pakket bestaat voordat we de bewerkpagina tonen.
        $pakket = $this->voedselpakketModel->sp_GetVoedselpakketById($id);

        abort_if(!$pakket, 404);

        return view('voedselpakket.edit', [
            'title' => 'Voedselpakket wijzigen',
            'pakket' => $pakket,
            'regels' => $this->voedselpakketModel->sp_GetVoedselpakketProducten($id),
            'klanten' => $this->klantModel->sp_GetAllKlanten(),
            'producten' => $this->productModel->sp_GetAllProducten(),
        ]);
    }

    public function update(Request $request, int $id)
    {
        $this->authorizeVoedselpakketBeheer();

        // Zelfde validatie als bij opslaan, zodat de invoerregels consistent blijven.
        $validated = $request->validate(
            $this->pakketValidationRules(),
            $this->pakketValidationMessages(),
            $this->pakketValidationAttributes()
        );

        // Bestaand pakket ophalen om te controleren of bijwerken zinvol is.
        $pakket = $this->voedselpakketModel->sp_GetVoedselpakketById($id);

        abort_if(!$pakket, 404);

        // Productgegevens worden opnieuw gebruikt voor voorraadcontrole.
        $producten = collect($this->productModel->sp_GetAllProducten())->keyBy('Id');

        try {
            // Oude regels worden verwijderd en daarna opnieuw opgebouwd.
            DB::beginTransaction();

            $this->voedselpakketModel->sp_DeleteVoedselpakketProducten($id);

            // Iedere nieuwe regel wordt apart gecontroleerd en daarna toegevoegd.
            foreach ($validated['pakket_regels'] as $index => $regel) {
                $product = $producten->get((int) $regel['product_id']);

                if (!$product) {
                    throw ValidationException::withMessages([
                        'pakket_regels.' . $index . '.product_id' => 'Het gekozen product bestaat niet.',
                    ]);
                }

                if ((int) $regel['aantal'] > (int) $product->AantalOpVoorraad) {
                    throw ValidationException::withMessages([
                        'pakket_regels.' . $index . '.aantal' => 'Er is onvoldoende voorraad voor ' . $product->ProductNaam . '.',
                    ]);
                }

                $this->voedselpakketModel->sp_AddVoedselpakketProduct(
                    $id,
                    (int) $regel['product_id'],
                    (int) $regel['aantal']
                );
            }

            $pakketStatus = $validated['datum_uitgifte'] ? 'Uitgereikt' : 'Samengesteld';
            $affected = $this->voedselpakketModel->sp_UpdateVoedselpakket(
                $id,
                $validated['klant_id'],
                $validated['datum_samengesteld'],
                $validated['datum_uitgifte'] ?? null,
                $pakketStatus
            );

            DB::commit();

            if ($affected === 0) {
                return back()
                    ->withInput()
                    ->with('error', 'Er is niets gewijzigd of het pakket bestaat niet.');
            }

            return redirect()
                ->route('voedselpakket.show', $id)
                ->with('success', 'Voedselpakket succesvol bijgewerkt.');
        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Fout bij bijwerken van voedselpakket.', [
                'pakket_id' => $id,
                'melding' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Voedselpakket kon niet worden bijgewerkt. Probeer het opnieuw.');
        }
    }

    public function destroy(int $id)
    {
        $this->authorizeVoedselpakketBeheer();

        // Verwijderen gaat via de stored procedure, inclusief de bijbehorende meldingen.
        $result = $this->voedselpakketModel->sp_DeleteVoedselpakket($id);

        if (($result['affected'] ?? 0) > 0) {
            return redirect()
                ->route('voedselpakket.index')
                ->with('success', 'Voedselpakket is succesvol verwijderd.');
        }

        return redirect()
            ->route('voedselpakket.index')
            ->with('error', $result['message'] ?? 'Voedselpakket is niet verwijderd.');
    }
}
