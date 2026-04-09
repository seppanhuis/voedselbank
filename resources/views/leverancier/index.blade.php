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

            <div class="mb-4 flex items-center justify-between gap-4">
                <h1 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">{{ $title }}</h1>
                <a href="{{ route('leverancier.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-blue-700 dark:hover:bg-blue-500">
                    <span>Nieuwe leverancier</span>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-zinc-200 text-sm dark:divide-zinc-700">
                    <thead class="bg-zinc-50 dark:bg-zinc-800/60">
                        <tr>
                            <th class="px-3 py-2 text-left font-semibold text-zinc-700 dark:text-zinc-200">Bedrijfsnaam</th>
                            <th class="px-3 py-2 text-left font-semibold text-zinc-700 dark:text-zinc-200">Contactpersoon</th>
                            <th class="px-3 py-2 text-left font-semibold text-zinc-700 dark:text-zinc-200">E-mail</th>
                            <th class="px-3 py-2 text-left font-semibold text-zinc-700 dark:text-zinc-200">Telefoon</th>
                            <th class="px-3 py-2 text-left font-semibold text-zinc-700 dark:text-zinc-200">Eerstvolgende levering</th>
                            <th class="px-3 py-2 text-left font-semibold text-zinc-700 dark:text-zinc-200">Adres</th>
                            <th class="px-3 py-2 text-left font-semibold text-zinc-700 dark:text-zinc-200">Wijzigen</th>
                            <th class="px-3 py-2 text-left font-semibold text-zinc-700 dark:text-zinc-200">Verwijderen</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                        @forelse ($leveranciers as $leverancier)
                            <tr class="align-top">
                                <td class="px-3 py-2 text-zinc-900 dark:text-zinc-100">{{ $leverancier->Bedrijfsnaam }}</td>
                                <td class="px-3 py-2 text-zinc-700 dark:text-zinc-300">{{ $leverancier->ContactpersoonNaam }}</td>
                                <td class="px-3 py-2 text-zinc-700 dark:text-zinc-300">{{ $leverancier->ContactpersoonEmail }}</td>
                                <td class="px-3 py-2 text-zinc-700 dark:text-zinc-300">{{ $leverancier->Telefoon }}</td>
                                <td class="px-3 py-2 text-zinc-700 dark:text-zinc-300">
                                    {{ $leverancier->EerstvolgendeLevering ? Carbon::parse($leverancier->EerstvolgendeLevering)->format('d-m-Y H:i') : '-' }}
                                </td>
                                <td class="px-3 py-2 text-zinc-700 dark:text-zinc-300">
                                    {{ $leverancier->Straat }} {{ $leverancier->Huisnummer }}{{ $leverancier->Toevoeging ? ' ' . $leverancier->Toevoeging : '' }}<br>
                                    {{ $leverancier->Postcode }} {{ $leverancier->Plaats }}
                                </td>
                                <td class="px-3 py-2">
                                    <a href="{{ route('leverancier.edit', $leverancier->Id) }}" class="inline-flex items-center rounded-md bg-amber-500 px-2 py-1 text-xs font-medium text-white transition-colors hover:bg-amber-600">
                                        Wijzigen
                                    </a>
                                </td>
                                <td class="px-3 py-2">
                                    <form action="{{ route('leverancier.destroy', $leverancier->Id) }}" method="POST" onsubmit="return confirm('Weet je zeker dat je deze leverancier wilt verwijderen?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center rounded-md bg-red-600 px-2 py-1 text-xs font-medium text-white transition-colors hover:bg-red-700">
                                            Verwijderen
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-3 py-6 text-center text-zinc-600 dark:text-zinc-300">Er zijn nog geen leveranciers gevonden.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts::app>
