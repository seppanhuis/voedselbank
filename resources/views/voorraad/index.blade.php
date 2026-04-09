@php
    use Illuminate\Support\Carbon;
@endphp

<x-layouts::app :title="$title">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="rounded-xl border border-neutral-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
            @if (session('success'))
                <div class="mb-4 flex items-center justify-between rounded-lg bg-green-50 p-3 text-green-800 dark:bg-green-900/20 dark:text-green-200" id="successAlert">
                    <span>{{ session('success') }}</span>
                    <button onclick="document.getElementById('successAlert').remove()" class="ml-auto text-green-600 hover:text-green-800 dark:text-green-300 dark:hover:text-green-100">×</button>
                </div>
                <meta http-equiv="refresh" content="3">
            @endif

            @if (session('error'))
                <div class="mb-4 flex items-center justify-between rounded-lg bg-red-50 p-3 text-red-800 dark:bg-red-900/20 dark:text-red-200" id="errorAlert">
                    <span>{{ session('error') }}</span>
                    <button onclick="document.getElementById('errorAlert').remove()" class="ml-auto text-red-600 hover:text-red-800 dark:text-red-300 dark:hover:text-red-100">×</button>
                </div>
                <meta http-equiv="refresh" content="3">
            @endif

            <div class="mb-4 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">{{ $title }}</h1>
                    <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">Filter op categorie en sorteer op alle zichtbare kolommen.</p>
                </div>
                <a href="{{ route('voorraad.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-blue-700 dark:hover:bg-blue-500">Nieuw product</a>
            </div>

            <form method="GET" action="{{ route('voorraad.index') }}" class="mb-4 grid gap-3 md:grid-cols-[1fr_auto] md:items-end">
                <div>
                    <label for="categorie_id" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Categorie</label>
                    <select id="categorie_id" name="categorie_id" class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                        <option value="">Alle categorieën</option>
                        @foreach ($categorieen as $categorie)
                            <option value="{{ $categorie->Id }}" {{ (string) $categorieId === (string) $categorie->Id ? 'selected' : '' }}>{{ $categorie->CategorieNaam }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <input type="hidden" name="sort" value="{{ $sort }}">
                    <input type="hidden" name="direction" value="{{ $direction }}">
                    <button type="submit" class="inline-flex items-center rounded-lg bg-zinc-900 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-zinc-800 dark:bg-zinc-100 dark:text-zinc-900 dark:hover:bg-white">Filter</button>
                    <a href="{{ route('voorraad.index') }}" class="inline-flex items-center rounded-lg border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 transition-colors hover:border-zinc-400 hover:bg-zinc-50 dark:border-zinc-600 dark:text-zinc-200 dark:hover:bg-zinc-800">Reset</a>
                </div>
            </form>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-zinc-200 text-sm dark:divide-zinc-700">
                    <thead class="bg-zinc-50 dark:bg-zinc-800/60">
                        <tr>
                            <th class="px-3 py-2 text-left font-semibold text-zinc-700 dark:text-zinc-200">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'ean', 'direction' => $sort === 'ean' && $direction === 'asc' ? 'desc' : 'asc']) }}">EAN</a>
                            </th>
                            <th class="px-3 py-2 text-left font-semibold text-zinc-700 dark:text-zinc-200">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'productnaam', 'direction' => $sort === 'productnaam' && $direction === 'asc' ? 'desc' : 'asc']) }}">Productnaam</a>
                            </th>
                            <th class="px-3 py-2 text-left font-semibold text-zinc-700 dark:text-zinc-200">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'categorie', 'direction' => $sort === 'categorie' && $direction === 'asc' ? 'desc' : 'asc']) }}">Categorie</a>
                            </th>
                            <th class="px-3 py-2 text-left font-semibold text-zinc-700 dark:text-zinc-200">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'aantal', 'direction' => $sort === 'aantal' && $direction === 'asc' ? 'desc' : 'asc']) }}">Aantal</a>
                            </th>
                            <th class="px-3 py-2 text-left font-semibold text-zinc-700 dark:text-zinc-200">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'eenheid', 'direction' => $sort === 'eenheid' && $direction === 'asc' ? 'desc' : 'asc']) }}">Eenheid</a>
                            </th>
                            <th class="px-3 py-2 text-left font-semibold text-zinc-700 dark:text-zinc-200">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'houdbaar_tot', 'direction' => $sort === 'houdbaar_tot' && $direction === 'asc' ? 'desc' : 'asc']) }}">Houdbaar tot</a>
                            </th>
                            <th class="px-3 py-2 text-left font-semibold text-zinc-700 dark:text-zinc-200">Wijzigen</th>
                            <th class="px-3 py-2 text-left font-semibold text-zinc-700 dark:text-zinc-200">Verwijderen</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                        @forelse ($producten as $product)
                            <tr class="align-top">
                                <td class="px-3 py-2 text-zinc-900 dark:text-zinc-100">{{ $product->EAN }}</td>
                                <td class="px-3 py-2 text-zinc-700 dark:text-zinc-300">{{ $product->ProductNaam }}</td>
                                <td class="px-3 py-2 text-zinc-700 dark:text-zinc-300">{{ $product->CategorieNaam }}</td>
                                <td class="px-3 py-2 text-zinc-700 dark:text-zinc-300">{{ $product->AantalOpVoorraad }}</td>
                                <td class="px-3 py-2 text-zinc-700 dark:text-zinc-300">{{ $product->Eenheid }}</td>
                                <td class="px-3 py-2 text-zinc-700 dark:text-zinc-300">{{ $product->HoudbaarTot ? Carbon::parse($product->HoudbaarTot)->format('d-m-Y') : '-' }}</td>
                                <td class="px-3 py-2">
                                    <a href="{{ route('voorraad.edit', $product->Id) }}" class="inline-flex items-center rounded-md bg-amber-500 px-2 py-1 text-xs font-medium text-white transition-colors hover:bg-amber-600">Wijzigen</a>
                                </td>
                                <td class="px-3 py-2">
                                    <form action="{{ route('voorraad.destroy', $product->Id) }}" method="POST" onsubmit="return confirm('Weet je zeker dat je dit product wilt verwijderen?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center rounded-md bg-red-600 px-2 py-1 text-xs font-medium text-white transition-colors hover:bg-red-700">Verwijderen</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-3 py-6 text-center text-zinc-600 dark:text-zinc-300">Geen producten gevonden.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts::app>
