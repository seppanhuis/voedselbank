<x-layouts::app :title="$title">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
            <div class="mb-6">
                <h1 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">{{ $title }}</h1>
                <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">Voer het product, de categorie en de voorraad in.</p>
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

            <form method="POST" action="{{ route('voorraad.store') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label for="categorie_id" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Categorie <span class="text-red-500">*</span></label>
                        <select id="categorie_id" name="categorie_id" required class="mt-1 w-full rounded-lg border {{ $errors->has('categorie_id') ? 'border-red-500 bg-red-50 focus:border-red-500 focus:ring-red-500 dark:bg-red-900/20' : 'border-zinc-300 bg-white focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800' }} px-3 py-2 text-zinc-900 focus:outline-none focus:ring-1 dark:text-zinc-100">
                            <option value="">Kies een categorie</option>
                            @foreach ($categorieen as $categorie)
                                <option value="{{ $categorie->Id }}" {{ (string) old('categorie_id') === (string) $categorie->Id ? 'selected' : '' }}>{{ $categorie->CategorieNaam }}</option>
                            @endforeach
                        </select>
                        @error('categorie_id')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="productnaam" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Productnaam <span class="text-red-500">*</span></label>
                        <input type="text" id="productnaam" name="productnaam" value="{{ old('productnaam') }}" required maxlength="150" class="mt-1 w-full rounded-lg border {{ $errors->has('productnaam') ? 'border-red-500 bg-red-50 focus:border-red-500 focus:ring-red-500 dark:bg-red-900/20' : 'border-zinc-300 bg-white focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800' }} px-3 py-2 text-zinc-900 focus:outline-none focus:ring-1 dark:text-zinc-100" />
                        @error('productnaam')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div>
                        <label for="ean" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">EAN <span class="text-red-500">*</span></label>
                        <input type="text" id="ean" name="ean" value="{{ old('ean') }}" required maxlength="13" pattern="[0-9]{13}" inputmode="numeric" class="mt-1 w-full rounded-lg border {{ $errors->has('ean') ? 'border-red-500 bg-red-50 focus:border-red-500 focus:ring-red-500 dark:bg-red-900/20' : 'border-zinc-300 bg-white focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800' }} px-3 py-2 text-zinc-900 focus:outline-none focus:ring-1 dark:text-zinc-100" />
                        @error('ean')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="aantal_op_voorraad" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Aantal op voorraad <span class="text-red-500">*</span></label>
                        <input type="number" id="aantal_op_voorraad" name="aantal_op_voorraad" value="{{ old('aantal_op_voorraad', 0) }}" required min="0" class="mt-1 w-full rounded-lg border {{ $errors->has('aantal_op_voorraad') ? 'border-red-500 bg-red-50 focus:border-red-500 focus:ring-red-500 dark:bg-red-900/20' : 'border-zinc-300 bg-white focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800' }} px-3 py-2 text-zinc-900 focus:outline-none focus:ring-1 dark:text-zinc-100" />
                        @error('aantal_op_voorraad')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="eenheid" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Eenheid <span class="text-red-500">*</span></label>
                        <input type="text" id="eenheid" name="eenheid" value="{{ old('eenheid', 'stuks') }}" required maxlength="20" class="mt-1 w-full rounded-lg border {{ $errors->has('eenheid') ? 'border-red-500 bg-red-50 focus:border-red-500 focus:ring-red-500 dark:bg-red-900/20' : 'border-zinc-300 bg-white focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800' }} px-3 py-2 text-zinc-900 focus:outline-none focus:ring-1 dark:text-zinc-100" />
                        @error('eenheid')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label for="houdbaar_tot" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Houdbaar tot</label>
                    <input type="date" id="houdbaar_tot" name="houdbaar_tot" value="{{ old('houdbaar_tot') }}" class="mt-1 w-full rounded-lg border {{ $errors->has('houdbaar_tot') ? 'border-red-500 bg-red-50 focus:border-red-500 focus:ring-red-500 dark:bg-red-900/20' : 'border-zinc-300 bg-white focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800' }} px-3 py-2 text-zinc-900 focus:outline-none focus:ring-1 dark:text-zinc-100" />
                    @error('houdbaar_tot')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-6 py-2.5 font-medium text-white transition-colors hover:bg-blue-700 dark:hover:bg-blue-500">Opslaan</button>
                    <a href="{{ route('voorraad.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-zinc-300 px-6 py-2.5 font-medium text-zinc-700 transition-colors hover:border-zinc-400 hover:bg-zinc-50 dark:border-zinc-600 dark:text-zinc-200 dark:hover:bg-zinc-800">Annuleren</a>
                </div>
            </form>
        </div>
    </div>
</x-layouts::app>
