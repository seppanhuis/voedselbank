<x-layouts::app :title="$title">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
            <div class="mb-6 flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">{{ $title }}</h1>
                    <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">Pas de gegevens aan en sla de wijzigingen op.</p>
                </div>
                <a href="{{ route('klant.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 transition-colors hover:border-zinc-400 hover:bg-zinc-50 dark:border-zinc-600 dark:text-zinc-200 dark:hover:bg-zinc-800">
                    Terug
                </a>
            </div>

            @if ($errors->any())
                <div class="mb-6 rounded-lg bg-red-50 p-4 text-red-800 dark:bg-red-900/20 dark:text-red-200">
                    <h3 class="font-semibold">Controleer uw invoer:</h3>
                    <ul class="mt-2 list-inside list-disc space-y-1 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('klant.update', $klant->Id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="gezinsnaam" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Gezinsnaam</label>
                    <input type="text" id="gezinsnaam" name="gezinsnaam" value="{{ old('gezinsnaam', $klant->GezinsNaam) }}" required maxlength="120" class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100" />
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div>
                        <label for="geboortedatum" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Geboortedatum</label>
                        <input type="date" id="geboortedatum" name="geboortedatum" value="{{ old('geboortedatum', $klant->GeboorteDatum) }}" class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100" />
                    </div>
                    <div>
                        <label for="telefoon" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Telefoon</label>
                        <input type="text" id="telefoon" name="telefoon" value="{{ old('telefoon', $klant->Telefoon) }}" required maxlength="20" class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100" />
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">E-mail</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $klant->Email) }}" required maxlength="150" class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100" />
                    </div>
                </div>

                <div class="rounded-lg border border-zinc-200 bg-zinc-50 p-4 dark:border-zinc-700 dark:bg-zinc-800/50">
                    <h3 class="mb-4 font-medium text-zinc-900 dark:text-zinc-100">Samenstelling gezin</h3>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div>
                            <label for="aantal_volwassenen" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Volwassenen</label>
                            <input type="number" id="aantal_volwassenen" name="aantal_volwassenen" value="{{ old('aantal_volwassenen', $klant->AantalVolwassenen) }}" required min="0" class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100" />
                        </div>
                        <div>
                            <label for="aantal_kinderen" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Kinderen</label>
                            <input type="number" id="aantal_kinderen" name="aantal_kinderen" value="{{ old('aantal_kinderen', $klant->AantalKinderen) }}" required min="0" class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100" />
                        </div>
                        <div>
                            <label for="aantal_babys" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Baby's</label>
                            <input type="number" id="aantal_babys" name="aantal_babys" value="{{ old('aantal_babys', $klant->AantalBabys) }}" required min="0" class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100" />
                        </div>
                    </div>
                </div>

                <div class="rounded-lg border border-zinc-200 bg-zinc-50 p-4 dark:border-zinc-700 dark:bg-zinc-800/50">
                    <h3 class="mb-4 font-medium text-zinc-900 dark:text-zinc-100">Specifieke wensen</h3>
                    @php
                        $activeWensen = old('wensen', $selectedWensen);
                    @endphp
                    @if (count($wensen) > 0)
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach ($wensen as $wens)
                                <label for="wens_{{ $wens->Id }}" class="flex cursor-pointer items-center gap-2 rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 transition-colors hover:border-blue-300 hover:bg-blue-50 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-200 dark:hover:border-blue-500/50 dark:hover:bg-blue-900/20">
                                    <input
                                        type="checkbox"
                                        id="wens_{{ $wens->Id }}"
                                        name="wensen[]"
                                        value="{{ $wens->Id }}"
                                        {{ in_array($wens->Id, $activeWensen) ? 'checked' : '' }}
                                        class="h-4 w-4 rounded border-zinc-300 text-blue-600 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-700"
                                    />
                                    <span>{{ $wens->WensNaam }}</span>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-zinc-600 dark:text-zinc-400">Geen wensen beschikbaar.</p>
                    @endif
                </div>

                <div class="rounded-lg border border-zinc-200 bg-zinc-50 p-4 dark:border-zinc-700 dark:bg-zinc-800/50">
                    <h3 class="mb-4 font-medium text-zinc-900 dark:text-zinc-100">Adresgegevens</h3>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label for="straat" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Straat</label>
                            <input type="text" id="straat" name="straat" value="{{ old('straat', $klant->Straat) }}" required maxlength="120" class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100" />
                        </div>
                        <div>
                            <label for="huisnummer" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Huisnummer</label>
                            <input type="text" id="huisnummer" name="huisnummer" value="{{ old('huisnummer', $klant->Huisnummer) }}" required maxlength="10" class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100" />
                        </div>
                        <div>
                            <label for="postcode" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Postcode</label>
                            <input type="text" id="postcode" name="postcode" value="{{ old('postcode', $klant->Postcode) }}" required maxlength="7" class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100" />
                        </div>
                        <div>
                            <label for="plaats" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Plaats</label>
                            <input type="text" id="plaats" name="plaats" value="{{ old('plaats', $klant->Plaats) }}" required maxlength="80" class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100" />
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit" class="inline-flex items-center rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white transition-colors hover:bg-blue-700 dark:hover:bg-blue-500">Wijzigingen opslaan</button>
                    <a href="{{ route('klant.index') }}" class="inline-flex items-center rounded-lg border border-zinc-300 px-6 py-2.5 text-sm font-medium text-zinc-700 transition-colors hover:border-zinc-400 hover:bg-zinc-50 dark:border-zinc-600 dark:text-zinc-200 dark:hover:bg-zinc-800">Annuleren</a>
                </div>
            </form>
        </div>
    </div>
</x-layouts::app>
