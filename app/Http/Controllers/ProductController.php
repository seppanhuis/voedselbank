<?php

namespace App\Http\Controllers;

use App\Models\ProductModel;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ProductController extends Controller
{
    private ProductModel $productModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
    }

    private function authorizeVoorraadBeheer(): void
    {
        abort_unless(
            auth()->check() && (auth()->user()->isDirectie() || auth()->user()->isMagazijnmedewerker()),
            403
        );
    }

    private function productValidationRules(?int $id = null): array
    {
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

    public function index(Request $request)
    {
        $this->authorizeVoorraadBeheer();

        $simulateEmpty = collect($request->query())
            ->flatten()
            ->contains(static fn ($value) => strtolower((string) $value) === 'empty');

        $sort = (string) $request->query('sort', 'ean');
        $direction = strtolower((string) $request->query('direction', 'asc')) === 'desc' ? 'desc' : 'asc';
        $categorieId = (int) $request->query('categorie_id', 0);

        $producten = $simulateEmpty
            ? collect([])
            : collect($this->productModel->sp_GetAllProducten());

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

    public function create()
    {
        $this->authorizeVoorraadBeheer();

        return view('voorraad.create', [
            'title' => 'Nieuw product toevoegen',
            'categorieen' => $this->productModel->getAllCategorieen(),
        ]);
    }

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
