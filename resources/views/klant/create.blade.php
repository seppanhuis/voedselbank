@php
    use Illuminate\Support\Carbon;
@endphp

<x-layouts::app :title="$title">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
            <div class="mb-6">
                <h1 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">{{ $title }}</h1>
                <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">Vul alle gegevens in en klik op Opslaan</p>
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

            <form method="POST" action="{{ route('klant.store') }}" class="space-y-6">
                @csrf

                <!-- Gezinsnaam -->
                <div>
                    <label for="gezinsnaam" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                        Gezinsnaam <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="gezinsnaam"
                        name="gezinsnaam"
                        value="{{ old('gezinsnaam') }}"
                        required
                        maxlength="120"
                        class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-zinc-900 placeholder-zinc-500 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 dark:placeholder-zinc-400"
                        placeholder="Vul gezinsnaam in"
                    />
                </div>

                <!-- Geboortedatum -->
                <div>
                    <label for="geboortedatum" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                        Geboortedatum
                    </label>
                    <input
                        type="date"
                        id="geboortedatum"
                        name="geboortedatum"
                        value="{{ old('geboortedatum') }}"
                        class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100"
                    />
                </div>

                <!-- Telefoon -->
                <div>
                    <label for="telefoon" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                        Telefoon <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="tel"
                        id="telefoon"
                        name="telefoon"
                        value="{{ old('telefoon') }}"
                        required
                        maxlength="20"
                        class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-zinc-900 placeholder-zinc-500 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 dark:placeholder-zinc-400"
                        placeholder="06-12345678"
                    />
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                        E-mail <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        maxlength="150"
                        class="mt-1 w-full rounded-lg border {{ $errors->has('email') ? 'border-red-500 bg-red-50 dark:bg-red-900/20' : 'border-zinc-300 bg-white dark:bg-zinc-800' }} px-3 py-2 text-zinc-900 placeholder-zinc-500 focus:outline-none focus:ring-1 {{ $errors->has('email') ? 'focus:border-red-500 focus:ring-red-500' : 'focus:border-blue-500 focus:ring-blue-500' }} dark:border-zinc-600 dark:text-zinc-100 dark:placeholder-zinc-400"
                        placeholder="naam@voorbeeld.nl"
                    />
                    @if ($errors->has('email'))
                        <p class="mt-2 flex items-center gap-2 text-sm text-red-600 dark:text-red-400">
                            <svg class="h-4 w-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                            <strong>{{ $errors->first('email') }}</strong>
                        </p>
                    @endif
                </div>

                <!-- Samenstelling gezin -->
                <div class="rounded-lg border border-zinc-200 bg-zinc-50 p-4 dark:border-zinc-700 dark:bg-zinc-800/50">
                    <h3 class="mb-4 font-medium text-zinc-900 dark:text-zinc-100">Samenstelling gezin</h3>
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label for="aantal_volwassenen" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                                Volwassenen <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="number"
                                id="aantal_volwassenen"
                                name="aantal_volwassenen"
                                value="{{ old('aantal_volwassenen', 0) }}"
                                required
                                min="0"
                                max="99"
                                class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100"
                            />
                        </div>
                        <div>
                            <label for="aantal_kinderen" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                                Kinderen <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="number"
                                id="aantal_kinderen"
                                name="aantal_kinderen"
                                value="{{ old('aantal_kinderen', 0) }}"
                                required
                                min="0"
                                max="99"
                                class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100"
                            />
                        </div>
                        <div>
                            <label for="aantal_babys" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                                Baby's <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="number"
                                id="aantal_babys"
                                name="aantal_babys"
                                value="{{ old('aantal_babys', 0) }}"
                                required
                                min="0"
                                max="99"
                                class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100"
                            />
                        </div>
                    </div>
                </div>

                <!-- Wensen -->
                <div class="rounded-lg border border-zinc-200 bg-zinc-50 p-4 dark:border-zinc-700 dark:bg-zinc-800/50">
                    <h3 class="mb-4 font-medium text-zinc-900 dark:text-zinc-100">Specifieke wensen</h3>
                    @if (count($wensen) > 0)
                        <div class="grid grid-cols-2 gap-3 md:grid-cols-3">
                            @foreach ($wensen as $wens)
                                <div class="flex items-center">
                                    <input
                                        type="checkbox"
                                        id="wens_{{ $wens->Id }}"
                                        name="wensen[]"
                                        value="{{ $wens->Id }}"
                                        {{ in_array($wens->Id, old('wensen', [])) ? 'checked' : '' }}
                                        class="h-4 w-4 rounded border-zinc-300 text-blue-600 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-700"
                                    />
                                    <label for="wens_{{ $wens->Id }}" class="ml-2 text-sm text-zinc-700 dark:text-zinc-200">
                                        {{ $wens->WensNaam }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-zinc-600 dark:text-zinc-400">Geen wensen beschikbaar</p>
                    @endif
                </div>

                <!-- Adresgegevens -->
                <div class="rounded-lg border border-zinc-200 bg-zinc-50 p-4 dark:border-zinc-700 dark:bg-zinc-800/50">
                    <h3 class="mb-4 font-medium text-zinc-900 dark:text-zinc-100">Adresgegevens</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="straat" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                                Straat <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                id="straat"
                                name="straat"
                                value="{{ old('straat') }}"
                                required
                                maxlength="120"
                                class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-zinc-900 placeholder-zinc-500 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 dark:placeholder-zinc-400"
                                placeholder="Straatnaam"
                            />
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="huisnummer" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                                    Huisnummer <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="huisnummer"
                                    name="huisnummer"
                                    value="{{ old('huisnummer') }}"
                                    required
                                    maxlength="10"
                                    class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-zinc-900 placeholder-zinc-500 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 dark:placeholder-zinc-400"
                                    placeholder="123"
                                />
                            </div>
                        </div>
                        <div>
                            <label for="postcode" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                                Postcode <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                id="postcode"
                                name="postcode"
                                value="{{ old('postcode') }}"
                                required
                                maxlength="7"
                                class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-zinc-900 placeholder-zinc-500 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 dark:placeholder-zinc-400"
                                placeholder="1234AB"
                            />
                        </div>
                        <div>
                            <label for="plaats" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                                Plaats <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                id="plaats"
                                name="plaats"
                                value="{{ old('plaats') }}"
                                required
                                maxlength="80"
                                class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-zinc-900 placeholder-zinc-500 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 dark:placeholder-zinc-400"
                                placeholder="Plaats"
                            />
                        </div>
                    </div>
                </div>

                <!-- Knoppen -->
                <div class="flex gap-3 pt-4">
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-6 py-2.5 font-medium text-white transition-colors hover:bg-blue-700 dark:hover:bg-blue-500"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Opslaan
                    </button>
                    <a
                        href="{{ route('klant.index') }}"
                        class="inline-flex items-center gap-2 rounded-lg border border-zinc-300 px-6 py-2.5 font-medium text-zinc-700 transition-colors hover:border-zinc-400 hover:bg-zinc-50 dark:border-zinc-600 dark:text-zinc-200 dark:hover:bg-zinc-800"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        Annuleren
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts::app>
