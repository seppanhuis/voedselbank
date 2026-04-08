@php
    use Illuminate\Support\Carbon;
@endphp

<x-layouts::app :title="$title">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="rounded-xl border border-neutral-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
            <div class="mb-4 flex items-center justify-between">
                <h1 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">{{ $title }}</h1>
                <span class="rounded-md bg-zinc-100 px-3 py-1 text-sm font-medium text-zinc-700 dark:bg-zinc-800 dark:text-zinc-200">
                    {{ count($klanten) }} klanten
                </span>
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
                                <th class="px-3 py-2 text-left font-semibold text-zinc-700 dark:text-zinc-200">Acites</th>
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
                                    <td class="px-3 py-2 text-zinc-700 dark:text-zinc-300">nog geen acties</td>
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
