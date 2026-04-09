@php
    use Illuminate\Support\Carbon;
@endphp

<x-layouts::app :title="$title">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="rounded-xl border border-neutral-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
            @if (session('success'))
                <div class="mb-4 flex items-center justify-between rounded-lg bg-green-50 p-3 text-green-800 dark:bg-green-900/20 dark:text-green-200" id="successAlert">
                    <span>{{ session('success') }}</span>
                    <button onclick="document.getElementById('successAlert').remove()" class="ml-auto text-green-600 hover:text-green-800 dark:text-green-300 dark:hover:text-green-100">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>
                <meta http-equiv="refresh" content="3">
            @endif

            @if (session('error'))
                <div class="mb-4 flex items-center justify-between rounded-lg bg-red-50 p-3 text-red-800 dark:bg-red-900/20 dark:text-red-200" id="errorAlert">
                    <span>{{ session('error') }}</span>
                    <button onclick="document.getElementById('errorAlert').remove()" class="ml-auto text-red-600 hover:text-red-800 dark:text-red-300 dark:hover:text-red-100">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>
                <meta http-equiv="refresh" content="3">
            @endif

            <div class="mb-4 flex items-center justify-between">
                <h1 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">{{ $title }}</h1>
                <div class="flex items-center gap-2">
                    <span class="rounded-md bg-zinc-100 px-3 py-1 text-sm font-medium text-zinc-700 dark:bg-zinc-800 dark:text-zinc-200">
                        {{ count($klanten) }} klanten
                    </span>
                    <a href="{{ route('klant.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-blue-700 dark:hover:bg-blue-500">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Nieuwe klant
                    </a>
                </div>
            </div>

            @if (count($klanten) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-zinc-200 text-sm dark:divide-zinc-700">
                        <thead class="bg-zinc-50 dark:bg-zinc-800/60">
                            <tr>
                                <th class="px-3 py-2 text-left font-semibold text-zinc-700 dark:text-zinc-200">Gezinsnaam</th>
                                <th class="px-3 py-2 text-left font-semibold text-zinc-700 dark:text-zinc-200">Geboortedatum</th>
                                <th class="px-3 py-2 text-left font-semibold text-zinc-700 dark:text-zinc-200">Telefoon</th>
                                <th class="px-3 py-2 text-left font-semibold text-zinc-700 dark:text-zinc-200">E-mail</th>
                                <th class="px-3 py-2 text-left font-semibold text-zinc-700 dark:text-zinc-200">Samenstelling</th>
                                <th class="px-3 py-2 text-left font-semibold text-zinc-700 dark:text-zinc-200">Adres</th>
                                <th class="px-3 py-2 text-left font-semibold text-zinc-700 dark:text-zinc-200">Wensen</th>
                                <th class="px-3 py-2 text-left font-semibold text-zinc-700 dark:text-zinc-200">Wijzigen</th>
                                <th class="px-3 py-2 text-left font-semibold text-zinc-700 dark:text-zinc-200">Verwijderen</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                            @foreach ($klanten as $klant)
                                <tr class="align-top">
                                    <td class="px-3 py-2 text-zinc-900 dark:text-zinc-100">{{ $klant->GezinsNaam }}</td>
                                    <td class="px-3 py-2 text-zinc-700 dark:text-zinc-300">
                                        {{ $klant->GeboorteDatum ? Carbon::parse($klant->GeboorteDatum)->format('d-m-Y') : '-' }}
                                    </td>
                                    <td class="px-3 py-2 text-zinc-700 dark:text-zinc-300">{{ $klant->Telefoon }}</td>
                                    <td class="px-3 py-2 text-zinc-700 dark:text-zinc-300">{{ $klant->Email }}</td>
                                    <td class="px-3 py-2 text-zinc-700 dark:text-zinc-300">
                                        {{ $klant->AantalVolwassenen }} volw, {{ $klant->AantalKinderen }} kind, {{ $klant->AantalBabys }} baby
                                    </td>
                                    <td class="px-3 py-2 text-zinc-700 dark:text-zinc-300">
                                        {{ $klant->Straat }} {{ $klant->Huisnummer }}{{ $klant->Toevoeging ? ' ' . $klant->Toevoeging : '' }}<br>
                                        {{ $klant->Postcode }} {{ $klant->Plaats }}, {{ $klant->Land }}
                                    </td>
                                    <td class="px-3 py-2 text-zinc-700 dark:text-zinc-300">{{ $klant->Wensen ?: '-' }}</td>
                                    <td class="px-3 py-2 text-zinc-700 dark:text-zinc-300">
                                        <form action="{{ route('klant.edit', $klant->Id) }}" method="POST">
                                            @csrf
                                            @method('GET')
                                            <button type="submit" class="inline-flex items-center rounded-md bg-amber-500 px-2 py-1 text-xs font-medium text-white transition-colors hover:bg-amber-600 dark:hover:bg-amber-400">
                                                Wijzigen
                                            </button>
                                        </form>
                                    </td>
                                    <td class="px-3 py-2 text-zinc-700 dark:text-zinc-300">
                                        @php
                                            $isBeschermdAdminKlant = auth()->check()
                                                && auth()->user()->isDirectie()
                                                && strtolower((string) ($klant->Email ?? '')) === 'admin@gmail.com';
                                        @endphp

                                        @if ($isBeschermdAdminKlant)
                                            <button type="button" disabled title="Admin-account kan niet verwijderd worden" class="inline-flex cursor-not-allowed items-center rounded-md bg-zinc-400 px-2 py-1 text-xs font-medium text-white opacity-70 dark:bg-zinc-600">
                                                Verwijderen
                                            </button>
                                        @else
                                            <form action="{{ route('klant.destroy', $klant->Id) }}" method="POST"
                                                onsubmit="return confirm('Weet je zeker dat je deze klant wilt verwijderen?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center rounded-md bg-red-600 px-2 py-1 text-xs font-medium text-white transition-colors hover:bg-red-700 dark:hover:bg-red-500">
                                                    Verwijderen
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="rounded-lg border border-dashed border-zinc-300 p-6 text-center text-zinc-600 dark:border-zinc-700 dark:text-zinc-300">
                    Er zijn nog geen klanten gevonden.
                </div>
            @endif
        </div>
    </div>
</x-layouts::app>
