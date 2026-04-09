<x-layouts::app :title="$title">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
            <div class="mb-6">
                <h1 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">{{ $title }}</h1>
                <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">Vul alle gegevens in en klik op Opslaan.</p>
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

            <form method="POST" action="{{ route('leverancier.store') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label for="bedrijfsnaam" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Bedrijfsnaam <span class="text-red-500">*</span></label>
                        <input type="text" id="bedrijfsnaam" name="bedrijfsnaam" value="{{ old('bedrijfsnaam') }}" required maxlength="120" class="mt-1 w-full rounded-lg border {{ $errors->has('bedrijfsnaam') ? 'border-red-500 bg-red-50 focus:border-red-500 focus:ring-red-500 dark:bg-red-900/20' : 'border-zinc-300 bg-white focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800' }} px-3 py-2 text-zinc-900 focus:outline-none focus:ring-1 dark:text-zinc-100" />
                        @error('bedrijfsnaam')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="eerstvolgende_levering" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Eerstvolgende levering <span class="text-red-500">*</span></label>
                        <input type="datetime-local" id="eerstvolgende_levering" name="eerstvolgende_levering" value="{{ old('eerstvolgende_levering', now()->addDay()->format('Y-m-d\TH:i')) }}" required class="mt-1 w-full rounded-lg border {{ $errors->has('eerstvolgende_levering') ? 'border-red-500 bg-red-50 focus:border-red-500 focus:ring-red-500 dark:bg-red-900/20' : 'border-zinc-300 bg-white focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800' }} px-3 py-2 text-zinc-900 focus:outline-none focus:ring-1 dark:text-zinc-100" />
                        @error('eerstvolgende_levering')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div>
                        <label for="contactpersoon_naam" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Contactpersoon naam <span class="text-red-500">*</span></label>
                        <input type="text" id="contactpersoon_naam" name="contactpersoon_naam" value="{{ old('contactpersoon_naam') }}" required maxlength="120" class="mt-1 w-full rounded-lg border {{ $errors->has('contactpersoon_naam') ? 'border-red-500 bg-red-50 focus:border-red-500 focus:ring-red-500 dark:bg-red-900/20' : 'border-zinc-300 bg-white focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800' }} px-3 py-2 text-zinc-900 focus:outline-none focus:ring-1 dark:text-zinc-100" />
                        @error('contactpersoon_naam')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="contactpersoon_email" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Contactpersoon e-mail <span class="text-red-500">*</span></label>
                        <input type="email" id="contactpersoon_email" name="contactpersoon_email" value="{{ old('contactpersoon_email') }}" required maxlength="150" class="mt-1 w-full rounded-lg border {{ $errors->has('contactpersoon_email') ? 'border-red-500 bg-red-50 focus:border-red-500 focus:ring-red-500 dark:bg-red-900/20' : 'border-zinc-300 bg-white focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800' }} px-3 py-2 text-zinc-900 focus:outline-none focus:ring-1 dark:text-zinc-100" />
                        @error('contactpersoon_email')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="telefoon" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Telefoon <span class="text-red-500">*</span></label>
                        <input type="text" id="telefoon" name="telefoon" value="{{ old('telefoon') }}" required maxlength="20" class="mt-1 w-full rounded-lg border {{ $errors->has('telefoon') ? 'border-red-500 bg-red-50 focus:border-red-500 focus:ring-red-500 dark:bg-red-900/20' : 'border-zinc-300 bg-white focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800' }} px-3 py-2 text-zinc-900 focus:outline-none focus:ring-1 dark:text-zinc-100" />
                        @error('telefoon')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="rounded-lg border border-zinc-200 bg-zinc-50 p-4 dark:border-zinc-700 dark:bg-zinc-800/50">
                    <h3 class="mb-4 font-medium text-zinc-900 dark:text-zinc-100">Adresgegevens</h3>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label for="straat" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Straat <span class="text-red-500">*</span></label>
                            <input type="text" id="straat" name="straat" value="{{ old('straat') }}" required maxlength="120" class="mt-1 w-full rounded-lg border {{ $errors->has('straat') ? 'border-red-500 bg-red-50 focus:border-red-500 focus:ring-red-500 dark:bg-red-900/20' : 'border-zinc-300 bg-white focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800' }} px-3 py-2 text-zinc-900 focus:outline-none focus:ring-1 dark:text-zinc-100" />
                            @error('straat')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="huisnummer" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Huisnummer <span class="text-red-500">*</span></label>
                            <input type="text" id="huisnummer" name="huisnummer" value="{{ old('huisnummer') }}" required maxlength="10" class="mt-1 w-full rounded-lg border {{ $errors->has('huisnummer') ? 'border-red-500 bg-red-50 focus:border-red-500 focus:ring-red-500 dark:bg-red-900/20' : 'border-zinc-300 bg-white focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800' }} px-3 py-2 text-zinc-900 focus:outline-none focus:ring-1 dark:text-zinc-100" />
                            @error('huisnummer')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="toevoeging" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Toevoeging</label>
                            <input type="text" id="toevoeging" name="toevoeging" value="{{ old('toevoeging') }}" maxlength="10" class="mt-1 w-full rounded-lg border {{ $errors->has('toevoeging') ? 'border-red-500 bg-red-50 focus:border-red-500 focus:ring-red-500 dark:bg-red-900/20' : 'border-zinc-300 bg-white focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800' }} px-3 py-2 text-zinc-900 focus:outline-none focus:ring-1 dark:text-zinc-100" />
                            @error('toevoeging')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="postcode" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Postcode <span class="text-red-500">*</span></label>
                            <input type="text" id="postcode" name="postcode" value="{{ old('postcode') }}" required maxlength="7" class="mt-1 w-full rounded-lg border {{ $errors->has('postcode') ? 'border-red-500 bg-red-50 focus:border-red-500 focus:ring-red-500 dark:bg-red-900/20' : 'border-zinc-300 bg-white focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800' }} px-3 py-2 text-zinc-900 focus:outline-none focus:ring-1 dark:text-zinc-100" />
                            @error('postcode')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="plaats" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Plaats <span class="text-red-500">*</span></label>
                            <input type="text" id="plaats" name="plaats" value="{{ old('plaats') }}" required maxlength="80" class="mt-1 w-full rounded-lg border {{ $errors->has('plaats') ? 'border-red-500 bg-red-50 focus:border-red-500 focus:ring-red-500 dark:bg-red-900/20' : 'border-zinc-300 bg-white focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800' }} px-3 py-2 text-zinc-900 focus:outline-none focus:ring-1 dark:text-zinc-100" />
                            @error('plaats')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="land" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Land</label>
                            <input type="text" id="land" name="land" value="{{ old('land', 'Nederland') }}" maxlength="80" class="mt-1 w-full rounded-lg border {{ $errors->has('land') ? 'border-red-500 bg-red-50 focus:border-red-500 focus:ring-red-500 dark:bg-red-900/20' : 'border-zinc-300 bg-white focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800' }} px-3 py-2 text-zinc-900 focus:outline-none focus:ring-1 dark:text-zinc-100" />
                            @error('land')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-6 py-2.5 font-medium text-white transition-colors hover:bg-blue-700 dark:hover:bg-blue-500">Opslaan</button>
                    <a href="{{ route('leverancier.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-zinc-300 px-6 py-2.5 font-medium text-zinc-700 transition-colors hover:border-zinc-400 hover:bg-zinc-50 dark:border-zinc-600 dark:text-zinc-200 dark:hover:bg-zinc-800">Annuleren</a>
                </div>
            </form>
        </div>
    </div>
</x-layouts::app>
