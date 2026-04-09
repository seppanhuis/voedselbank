@php
    use Illuminate\Support\Carbon;

    $pakketRegels = old('pakket_regels');

    if (!is_array($pakketRegels) || count($pakketRegels) === 0) {
        $pakketRegels = [];

        foreach ($regels as $regel) {
            $pakketRegels[] = [
                'product_id' => $regel->ProductId,
                'aantal' => $regel->Aantal,
            ];
        }

        if (count($pakketRegels) === 0) {
            $pakketRegels = [['product_id' => '', 'aantal' => 1]];
        }
    }
@endphp

<x-layouts::app :title="$title">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
            <div class="mb-6 flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">{{ $title }}</h1>
                    <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">Pas de klant, datum en productregels van het pakket aan.</p>
                </div>
                <a href="{{ route('voedselpakket.show', $pakket->Id) }}" class="inline-flex items-center gap-2 rounded-lg border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 transition-colors hover:border-zinc-400 hover:bg-zinc-50 dark:border-zinc-600 dark:text-zinc-200 dark:hover:bg-zinc-800">Terug</a>
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

            <form method="POST" action="{{ route('voedselpakket.update', $pakket->Id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div>
                        <label for="klant_id" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Klant <span class="text-red-500">*</span></label>
                        <select id="klant_id" name="klant_id" required class="mt-1 w-full rounded-lg border {{ $errors->has('klant_id') ? 'border-red-500 bg-red-50 focus:border-red-500 focus:ring-red-500 dark:bg-red-900/20' : 'border-zinc-300 bg-white focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800' }} px-3 py-2 text-zinc-900 focus:outline-none focus:ring-1 dark:text-zinc-100">
                            <option value="">Kies een klant</option>
                            @foreach ($klanten as $klant)
                                <option value="{{ $klant->Id }}" {{ (string) old('klant_id', $pakket->KlantId) === (string) $klant->Id ? 'selected' : '' }}>{{ $klant->GezinsNaam }} - {{ $klant->Postcode }} {{ $klant->Plaats }}</option>
                            @endforeach
                        </select>
                        @error('klant_id')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="datum_samengesteld" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Datum samengesteld <span class="text-red-500">*</span></label>
                        <input type="date" id="datum_samengesteld" name="datum_samengesteld" value="{{ old('datum_samengesteld', $pakket->DatumSamengesteld ? Carbon::parse($pakket->DatumSamengesteld)->format('Y-m-d') : now()->format('Y-m-d')) }}" required class="mt-1 w-full rounded-lg border {{ $errors->has('datum_samengesteld') ? 'border-red-500 bg-red-50 focus:border-red-500 focus:ring-red-500 dark:bg-red-900/20' : 'border-zinc-300 bg-white focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800' }} px-3 py-2 text-zinc-900 focus:outline-none focus:ring-1 dark:text-zinc-100" />
                        @error('datum_samengesteld')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="datum_uitgifte" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Datum uitgifte</label>
                        <input type="date" id="datum_uitgifte" name="datum_uitgifte" value="{{ old('datum_uitgifte', $pakket->DatumUitgifte ? Carbon::parse($pakket->DatumUitgifte)->format('Y-m-d') : '') }}" class="mt-1 w-full rounded-lg border {{ $errors->has('datum_uitgifte') ? 'border-red-500 bg-red-50 focus:border-red-500 focus:ring-red-500 dark:bg-red-900/20' : 'border-zinc-300 bg-white focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800' }} px-3 py-2 text-zinc-900 focus:outline-none focus:ring-1 dark:text-zinc-100" />
                        @error('datum_uitgifte')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="rounded-lg border border-zinc-200 bg-zinc-50 p-4 dark:border-zinc-700 dark:bg-zinc-800/50">
                    <div class="mb-4 flex items-center justify-between gap-4">
                        <h3 class="font-medium text-zinc-900 dark:text-zinc-100">Pakketregels</h3>
                        <button type="button" id="addRegelButton" class="inline-flex items-center rounded-lg bg-zinc-900 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-zinc-800 dark:bg-zinc-100 dark:text-zinc-900 dark:hover:bg-white">Regel toevoegen</button>
                    </div>

                    <div id="pakketRegelsContainer" class="space-y-3">
                        @foreach ($pakketRegels as $index => $regel)
                            <div class="grid gap-3 rounded-lg border border-zinc-200 bg-white p-3 md:grid-cols-[1fr_140px_auto] dark:border-zinc-700 dark:bg-zinc-900" data-regel-row>
                                <div>
                                    <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-300">Product</label>
                                    <select name="pakket_regels[{{ $index }}][product_id]" required class="mt-1 w-full rounded-lg border {{ $errors->has('pakket_regels.' . $index . '.product_id') ? 'border-red-500 bg-red-50 focus:border-red-500 focus:ring-red-500 dark:bg-red-900/20' : 'border-zinc-300 bg-white focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800' }} px-3 py-2 text-zinc-900 focus:outline-none focus:ring-1 dark:text-zinc-100">
                                        <option value="">Kies een product</option>
                                        @foreach ($producten as $product)
                                            @if ((int) $product->AantalOpVoorraad > 0 || (string) ($regel['product_id'] ?? '') === (string) $product->Id)
                                                <option value="{{ $product->Id }}" {{ (string) ($regel['product_id'] ?? '') === (string) $product->Id ? 'selected' : '' }}>{{ $product->ProductNaam }} ({{ $product->EAN }}) - voorraad: {{ $product->AantalOpVoorraad }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('pakket_regels.' . $index . '.product_id')<p class="mt-2 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-300">Aantal</label>
                                    <input type="number" name="pakket_regels[{{ $index }}][aantal]" value="{{ $regel['aantal'] ?? 1 }}" min="1" required class="mt-1 w-full rounded-lg border {{ $errors->has('pakket_regels.' . $index . '.aantal') ? 'border-red-500 bg-red-50 focus:border-red-500 focus:ring-red-500 dark:bg-red-900/20' : 'border-zinc-300 bg-white focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800' }} px-3 py-2 text-zinc-900 focus:outline-none focus:ring-1 dark:text-zinc-100" />
                                    @error('pakket_regels.' . $index . '.aantal')<p class="mt-2 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                                </div>
                                <div class="flex items-end">
                                    <button type="button" class="removeRegelButton inline-flex items-center rounded-lg border border-zinc-300 px-3 py-2 text-sm font-medium text-zinc-700 transition-colors hover:border-red-400 hover:bg-red-50 hover:text-red-700 dark:border-zinc-600 dark:text-zinc-200 dark:hover:bg-red-900/20" {{ count($pakketRegels) === 1 ? 'disabled' : '' }}>Verwijderen</button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <template id="pakketRegelTemplate">
                        <div class="grid gap-3 rounded-lg border border-zinc-200 bg-white p-3 md:grid-cols-[1fr_140px_auto] dark:border-zinc-700 dark:bg-zinc-900" data-regel-row>
                            <div>
                                <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-300">Product</label>
                                <select required class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100" data-product-select>
                                    <option value="">Kies een product</option>
                                    @foreach ($producten as $product)
                                        @if ((int) $product->AantalOpVoorraad > 0)
                                            <option value="{{ $product->Id }}">{{ $product->ProductNaam }} ({{ $product->EAN }}) - voorraad: {{ $product->AantalOpVoorraad }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-300">Aantal</label>
                                <input type="number" min="1" required value="1" class="mt-1 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100" data-aantal-input />
                            </div>
                            <div class="flex items-end">
                                <button type="button" class="removeRegelButton inline-flex items-center rounded-lg border border-zinc-300 px-3 py-2 text-sm font-medium text-zinc-700 transition-colors hover:border-red-400 hover:bg-red-50 hover:text-red-700 dark:border-zinc-600 dark:text-zinc-200 dark:hover:bg-red-900/20">Verwijderen</button>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-6 py-2.5 font-medium text-white transition-colors hover:bg-blue-700 dark:hover:bg-blue-500">Wijzigingen opslaan</button>
                    <a href="{{ route('voedselpakket.show', $pakket->Id) }}" class="inline-flex items-center gap-2 rounded-lg border border-zinc-300 px-6 py-2.5 font-medium text-zinc-700 transition-colors hover:border-zinc-400 hover:bg-zinc-50 dark:border-zinc-600 dark:text-zinc-200 dark:hover:bg-zinc-800">Annuleren</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        (function () {
            const container = document.getElementById('pakketRegelsContainer');
            const template = document.getElementById('pakketRegelTemplate');
            const addButton = document.getElementById('addRegelButton');

            function updateNames() {
                const rows = container.querySelectorAll('[data-regel-row]');

                rows.forEach((row, index) => {
                    const productSelect = row.querySelector('select');
                    const amountInput = row.querySelector('input[type="number"]');

                    productSelect.name = `pakket_regels[${index}][product_id]`;
                    amountInput.name = `pakket_regels[${index}][aantal]`;

                    const removeButton = row.querySelector('.removeRegelButton');
                    removeButton.disabled = rows.length === 1;
                });
            }

            function addRow() {
                const fragment = template.content.cloneNode(true);
                container.appendChild(fragment);
                updateNames();
            }

            addButton.addEventListener('click', addRow);

            container.addEventListener('click', function (event) {
                const button = event.target.closest('.removeRegelButton');

                if (!button) {
                    return;
                }

                const rows = container.querySelectorAll('[data-regel-row]');

                if (rows.length === 1) {
                    return;
                }

                button.closest('[data-regel-row]').remove();
                updateNames();
            });

            updateNames();
        })();
    </script>
</x-layouts::app>
