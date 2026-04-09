@php
    use Illuminate\Support\Carbon;
@endphp

<x-layouts::app :title="$title">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
            <div class="mb-6 flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">{{ $title }}</h1>
                    <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">Overzicht van de inhoud en uitgiftedata van dit pakket.</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('voedselpakket.edit', $pakket->Id) }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-blue-700 dark:hover:bg-blue-500">Wijzigen</a>
                    <form method="POST" action="{{ route('voedselpakket.destroy', $pakket->Id) }}" onsubmit="return confirm('Weet je zeker dat je dit voedselpakket wilt verwijderen?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-red-700">Verwijderen</button>
                    </form>
                    <a href="{{ route('voedselpakket.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 transition-colors hover:border-zinc-400 hover:bg-zinc-50 dark:border-zinc-600 dark:text-zinc-200 dark:hover:bg-zinc-800">Terug</a>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-4 rounded-lg bg-green-50 p-3 text-green-800 dark:bg-green-900/20 dark:text-green-200">{{ session('success') }}</div>
            @endif

            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-lg border border-zinc-200 bg-zinc-50 p-4 dark:border-zinc-700 dark:bg-zinc-800/50">
                    <div class="text-xs uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Pakketnummer</div>
                    <div class="mt-1 text-lg font-semibold text-zinc-900 dark:text-zinc-100">{{ $pakket->Id }}</div>
                </div>
                <div class="rounded-lg border border-zinc-200 bg-zinc-50 p-4 dark:border-zinc-700 dark:bg-zinc-800/50">
                    <div class="text-xs uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Klant</div>
                    <div class="mt-1 text-lg font-semibold text-zinc-900 dark:text-zinc-100">{{ $pakket->GezinsNaam }}</div>
                </div>
                <div class="rounded-lg border border-zinc-200 bg-zinc-50 p-4 dark:border-zinc-700 dark:bg-zinc-800/50">
                    <div class="text-xs uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Datum samengesteld</div>
                    <div class="mt-1 text-lg font-semibold text-zinc-900 dark:text-zinc-100">{{ $pakket->DatumSamengesteld ? Carbon::parse($pakket->DatumSamengesteld)->format('d-m-Y') : '-' }}</div>
                </div>
                <div class="rounded-lg border border-zinc-200 bg-zinc-50 p-4 dark:border-zinc-700 dark:bg-zinc-800/50">
                    <div class="text-xs uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Datum uitgifte</div>
                    <div class="mt-1 text-lg font-semibold text-zinc-900 dark:text-zinc-100">{{ $pakket->DatumUitgifte ? Carbon::parse($pakket->DatumUitgifte)->format('d-m-Y') : '-' }}</div>
                </div>
            </div>

            <div class="mt-6 overflow-x-auto">
                <table class="min-w-full divide-y divide-zinc-200 text-sm dark:divide-zinc-700">
                    <thead class="bg-zinc-50 dark:bg-zinc-800/60">
                        <tr>
                            <th class="px-3 py-2 text-left font-semibold text-zinc-700 dark:text-zinc-200">Product</th>
                            <th class="px-3 py-2 text-left font-semibold text-zinc-700 dark:text-zinc-200">Categorie</th>
                            <th class="px-3 py-2 text-left font-semibold text-zinc-700 dark:text-zinc-200">EAN</th>
                            <th class="px-3 py-2 text-left font-semibold text-zinc-700 dark:text-zinc-200">Aantal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                        @forelse ($regels as $regel)
                            <tr>
                                <td class="px-3 py-2 text-zinc-900 dark:text-zinc-100">{{ $regel->ProductNaam }}</td>
                                <td class="px-3 py-2 text-zinc-700 dark:text-zinc-300">{{ $regel->CategorieNaam }}</td>
                                <td class="px-3 py-2 text-zinc-700 dark:text-zinc-300">{{ $regel->EAN }}</td>
                                <td class="px-3 py-2 text-zinc-700 dark:text-zinc-300">{{ $regel->Aantal }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-3 py-6 text-center text-zinc-600 dark:text-zinc-300">Er zijn nog geen productregels gekoppeld aan dit pakket.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts::app>
