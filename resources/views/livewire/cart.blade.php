<div class="px-12 py-12">
    <!-- Alert -->
    @if (session()->has('message'))
    <div class="text-center -mt-3 mb-8 p-2">
        <div class="inline-flex items-center bg-white leading-none text-green-600 rounded-full p-2 shadow text-sm">
            <span class="inline-flex bg-green-600 text-white rounded-full h-6 px-3 justify-center items-center text-">Sukses</span>
            <span class="inline-flex px-2">{{ session('message') }}</span>
            <span class="hover:text-red-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" wire:click="$refresh" class="fill-current"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </span>
        </div>
    </div>
    @endif

    <div class="flex flex-wrap space-x-4">
        <div class="flex-1 bg-white mx-auto pb-4 px-4 rounded-md w-full shadow-xl">
            <div class="flex justify-between w-full py-6">
                <p class="ml-3 font-black text-xl">Pilih Barang</p>
            </div>
            <div class="block relative">
                <input wire:model="search" type="text" name="" class="leading-snug border border-gray-300 block w-full appearance-none bg-gray-100 text-sm text-gray-600 py-1 px-4 pl-8 rounded-lg" placeholder="Cari" />
                <div class="pointer-events-none absolute pl-3 inset-y-0 left-0 flex items-center px-2 text-gray-300">
                    <svg class="fill-current h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 511.999 511.999">
                        <path d="M508.874 478.708L360.142 329.976c28.21-34.827 45.191-79.103 45.191-127.309C405.333 90.917 314.416 0 202.666 0S0 90.917 0 202.667s90.917 202.667 202.667 202.667c48.206 0 92.482-16.982 127.309-45.191l148.732 148.732c4.167 4.165 10.919 4.165 15.086 0l15.081-15.082c4.165-4.166 4.165-10.92-.001-15.085zM202.667 362.667c-88.229 0-160-71.771-160-160s71.771-160 160-160 160 71.771 160 160-71.771 160-160 160z" />
                    </svg>
                </div>
            </div>
            <div class="py-5 divide-y divide-gray-300">
                @foreach ($items as $i)
                    <div class="flex justify-between px-2 py-2" wire:click="readItem({{ $i->id }})">
                        <p class="flex text-gray-700">
                            {{ $i->name }}.
                        </p>
                        @php
                            $prices = [];
                            foreach ($i->prices as $p) {
                                $prices[] = $p->name;
                            }
                            $prices = implode(', ', $prices);
                        @endphp
                        <p class="text-gray-500 font-thin">({{ $prices }})</p>
                    </div>
                @endforeach
            </div>
            <div id="pagination" class="w-full justify-between border-t border-gray-100 pt-4 items-center">
                {{ $items->links() }}
            </div>
        </div>

        <div class="flex-1 bg-white mx-auto pb-4 px-4 rounded-md w-full shadow-xl">
            <div class="flex justify-between w-full py-6">
                <p class="ml-3 font-black text-xl">Keranjang</p>
            </div>
            <div class="pb-5 divide-y divide-gray-300">
                @if($cart['items'])
                <table class="pb-5 table-auto border-collapse w-full divide-y divide-gray-300">
                    <thead>
                        <tr>
                            <th class="pb-4 text-gray-700 text-left">Nama</th>
                            <th class="pb-4 text-gray-700 text-right">@</th>
                            <th class="pb-4 text-gray-700 text-right">Jumlah</th>
                            <th class="pb-4 text-gray-700 text-right">Total</th>
                            <th class="w-1"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-300">
                        @php $total = 0 @endphp
                        @foreach ($cart['items'] as $c)
                        <tr>
                            <td class="py-2 text-gray-700 text-left">{{ $c['name'] }} ({{ $c['price']['name'] }}).</td>
                            <td class="py-2 text-gray-700 text-right">@rupiah($c['price']['price'])</td>
                            <td class="py-2 text-gray-700 text-right">{{ $c['amount'] }}</td>
                            <td class="py-2 text-gray-700 text-right">@rupiah($c['total'])</td>
                            <td class="py-2 w-1">
                                <button class="text-gray-800 font-bold flex item-center" wire:click="removeItem({{ $c['id'] }}, {{ $c['price']['id'] }})">
                                    <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </td>
                        </tr>
                        @php $total += $c['total'] @endphp
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4" class="py-2 text-gray-700 text-right">@rupiah($total)</th>
                        </tr>
                    </tfoot>
                </table>
                @else
                <div class="w-full text-center text-orange-500 pt-20">
                    <svg class="w-full text-center w-24 h-24 pb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Keranjang Kosong
                </div>
                @endif
            </div>
        </div>
    </div>

    <x-jet-dialog-modal wire:model="RmodalState">
        <x-slot name="title">
            {{ $RmodalData->name ?? '' }}
        </x-slot>

        <x-slot name="content">
            @if($RmodalData)
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <x-jet-label for="RmodalData.name" value="Nama" />
                    <x-jet-input id="RmodalData.name" type="text" class="mt-1 block w-full" wire:model="RmodalData.name" :disabled="true"/>
                    <x-jet-input-error for="RmodalData.name" class="mt-2" />
                </div>
                <div class="grid grid-cols-2">
                    <div>
                        <x-jet-label for="RmodalData.priceId" value="Satuan" />
                        <select wire:model="RmodalData.priceId" class="mt-1 block w-full form-input shadow-sm rounded-l-md rounded-r-none">
                            @foreach($RmodalData['prices'] as $p)
                            <option value="{{ $p['id'] }}">{{ $p['name'] }} (@ @rupiah($p['price']))</option>
                            @endforeach
                        </select>
                        <x-jet-input-error for="RmodalData.priceId" class="mt-2" />
                    </div>
                    <div>
                        <x-jet-label for="RmodalData.amount" value="Jumlah" />
                        <input  id="RmodalData.amount" type="number" class="mt-1 block w-full form-input shadow-sm rounded-l-none rounded-r-md" wire:model="RmodalData.amount">
                        <x-jet-input-error for="RmodalData.amount" class="mt-2" />
                    </div>
                </div>
            </div>
            @endif
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('RmodalState')" wire:loading.attr="disabled">
                Batalkan
            </x-jet-secondary-button>

            <x-jet-button class="ml-2" wire:click.prevent="addItem" wire:loading.attr="disabled">
                Masukkan Keranjang
            </x-jet-button>
        </x-slot>
    </x-jet-dialog-modal>
</div>
