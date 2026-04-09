<?php

namespace App\Http\Controllers;

use App\Models\ProductModel;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * ProductController
 * 
 * Beheert alle acties gerelateerd aan producten in de voorraadbeheer.
 * Alleen beschikbaar voor directie en magazijnmedewerkers.
 */
class ProductController extends Controller
{
    private ProductModel $productModel;

    /**
     * Constructor - initialiseert ProductModel
     */
    public function __construct()
    {
        $this->productModel = new ProductModel();
    }

    private function authorizeVoorraadBeheer(): void
    {
        // Voorraadbeheer is alleen beschikbaar voor directie en magazijnmedewerkers.
        abort_unless(
            auth()->check() && (auth()->user()->isDirectie() || auth()->user()->isMagazijnmedewerker()),
            403
        );
    }

    private function productValidationRules(?int $id = null): array
    {
        // Validatieregels voor product aanmaken/bijwerken
        // Bij bijwerken: voeg ID toe voor unique constraint
        $eanRule = 'required|digits:13|unique:Product,EAN';
        $productNaamRule = 'required|string|max:150|unique:Product,ProductNaam';

        if ($id !== null) {
            $eanRule .= ',' . $id . ',Id';
            $productNaamRule .= ',' . $id . ',Id';
        }

        return [
            'categorie_id' => 'required|integer|exists:Categorie,Id',
            'productnaam' => $productNaamRule,
            'ean' => $eanRule,
            'aantal_op_voorraad' => 'required|integer|min:0',
            'eenheid' => 'required|string|max:20',
            'houdbaar_tot' => 'nullable|date',
        ];
    }

    private function productValidationMessages(): array
    {
        // Aangepaste foutmeldingen voor validatie
        return [
            'required' => ':attribute is verplicht.',
            'string' => ':attribute moet tekst zijn.',
            'integer' => ':attribute moet een heel getal zijn.',
            'min' => ':attribute moet minimaal :min zijn.',
            'digits' => ':attribute moet precies :digits cijfers bevatten.',
            'date' => ':attribute moet een geldige datum zijn.',
            'unique' => 'Deze waarde is al in gebruik.',
            'exists' => 'De gekozen categorie bestaat niet.',
        ];
    }

    private function productValidationAttributes(): array
    {
        // Gebruiksvriendelijke namen voor validatiefouten
        return [
            'categorie_id' => 'categorie',
            'productnaam' => 'productnaam',
            'ean' => 'EAN-nummer',
            'aantal_op_voorraad' => 'aantal op voorraad',
            'eenheid' => 'eenheid',
            'houdbaar_tot' => 'houdbaar tot',
        ];
    }

    private function sortProducts(Collection $producten, string $sort, string $direction): Collection
    {
        // Map query-veld naar echte kolomnamen uit het overzicht.
        $allowedSorts = [
            'ean' => 'EAN',
            'productnaam' => 'ProductNaam',
            'categorie' => 'CategorieNaam',
            'aantal' => 'AantalOpVoorraad',
            'eenheid' => 'Eenheid',
            'houdbaar_tot' => 'HoudbaarTot',
        ];

        $sortColumn = $allowedSorts[$sort] ?? 'EAN';

        return $producten->sortBy(
            static fn ($product) => data_get($product, $sortColumn),
            SORT_NATURAL | SORT_FLAG_CASE,
            $direction === 'desc'
        )->values();
    }

    /**
     * Toont alle producten in het voorraadbeheer
     * Ondersteunt filteren op categorie en sorteren
     */
    public function index(Request $request)
    {
        $this->authorizeVoorraadBeheer();

        // Met ?test=empty kan een lege lijst getest worden.
        $simulateEmpty = collect($request->query())
            ->flatten()
            ->contains(static fn ($value) => strtolower((string) $value) === 'empty');

        $sort = (string) $request->query('sort', 'ean');
        $direction = strtolower((string) $request->query('direction', 'asc')) === 'desc' ? 'desc' : 'asc';
        $categorieId = (int) $request->query('categorie_id', 0);

        $producten = $simulateEmpty
            ? collect([])
            : collect($this->productModel->sp_GetAllProducten());

        // Filter alleen op categorie wanneer daadwerkelijk een categorie is gekozen.
        if ($categorieId > 0) {
            $producten = $producten->filter(static fn ($product) => (int) ($product->CategorieId ?? 0) === $categorieId);
        }

        return view('voorraad.index', [
            'title' => 'Voorraad overzicht',
            'producten' => $this->sortProducts($producten, $sort, $direction),
            'categorieen' => $simulateEmpty ? [] : $this->productModel->getAllCategorieen(),
            'categorieId' => $categorieId,
            'sort' => $sort,
            'direction' => $direction,
        ]);
    }

    /**
     * Toont formulier voor nieuw product
     */
    public function create()
    {
        $this->authorizeVoorraadBeheer();

        return view('voorraad.create', [
            'title' => 'Nieuw product toevoegen',
            'categorieen' => $this->productModel->getAllCategorieen(),
        ]);
    }

    /**
     * Slaat nieuw product op in de database
     * Valideert invoer en roept stored procedure aan
     */
    public function store(Request $request)
    {
        $this->authorizeVoorraadBeheer();

        $validated = $request->validate(
            $this->productValidationRules(),
            $this->productValidationMessages(),
            $this->productValidationAttributes()
        );

        $this->productModel->sp_CreateProduct(
            $validated['categorie_id'],
            $validated['productnaam'],
            $validated['ean'],
            $validated['aantal_op_voorraad'],
            $validated['eenheid'],
            $validated['houdbaar_tot'] ?? null
        );

        return redirect()
            ->route('voorraad.index')
            ->with('success', 'Product ' . $validated['productnaam'] . ' succesvol toegevoegd.');
    }

    /**
     * Toont formulier voor wijzigen van bestaand product
     */
    public function edit(int $id)
    {
        $this->authorizeVoorraadBeheer();

        $product = $this->productModel->sp_GetProductById($id);

        abort_if(!$product, 404);

        return view('voorraad.edit', [
            'title' => 'Product wijzigen',
            'product' => $product,
            'categorieen' => $this->productModel->getAllCategorieen(),
        ]);
    }

    /**
     * Werkt bestaand product bij in de database
     * Valideert invoer en controleert of wijzigingen daadwerkelijk zijn gemaakt
     */
    public function update(Request $request, int $id)
    {
        $this->authorizeVoorraadBeheer();

        $validated = $request->validate(
            $this->productValidationRules($id),
            $this->productValidationMessages(),
            $this->productValidationAttributes()
        );

        $affected = $this->productModel->sp_UpdateProduct(
            $id,
            $validated['categorie_id'],
            $validated['productnaam'],
            $validated['ean'],
            $validated['aantal_op_voorraad'],
            $validated['eenheid'],
            $validated['houdbaar_tot'] ?? null
        );

        if ($affected === 0) {
            return back()
                ->withInput()
                ->with('error', 'Er is niets gewijzigd of het product bestaat niet.');
        }

        return redirect()
            ->route('voorraad.index')
            ->with('success', 'Product succesvol bijgewerkt.');
    }

    /**
     * Verwijdert product uit de database
     * Controleert of product niet in een voedselpakket wordt gebruikt
     */
    public function destroy(int $id)
    {
        $this->authorizeVoorraadBeheer();

        $result = $this->productModel->sp_DeleteProduct($id);

        if (($result['affected'] ?? 0) > 0) {
            return redirect()
                ->route('voorraad.index')
                ->with('success', 'Product is succesvol verwijderd.');
        }

        return redirect()
            ->route('voorraad.index')
            ->with('error', $result['message'] ?? 'Product kan niet worden verwijderd omdat het al in een voedselpakket is gebruikt.');
    }
}
