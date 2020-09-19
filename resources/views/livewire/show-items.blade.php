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

    <div class="bg-white mx-auto pb-4 px-4 rounded-md w-full shadow-xl">
        <div class="flex justify-between w-full pt-6 ">
            <p class="ml-3">Stok Barang</p>
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-jet-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="relative block rounded-md bg-white p-2 focus:outline-none">
                            <svg width="14" height="4" viewBox="0 0 14 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g opacity="0.4">
                                    <circle cx="2.19796" cy="1.80139" r="1.38611" fill="#222222"/>
                                    <circle cx="11.9013" cy="1.80115" r="1.38611" fill="#222222"/>
                                    <circle cx="7.04991" cy="1.80115" r="1.38611" fill="#222222"/>
                                </g>
                            </svg>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-jet-dropdown-link href="#" wire:click="$toggle('CmodalState')">
                            Tambah Barang
                        </x-jet-dropdown-link>
                    </x-slot>
                </x-jet-dropdown>
            </div>
        </div>
        <div class="w-full flex justify-between px-2 mt-2">
            <div class="flex-auto">
                <div class="inline-block">Per halaman:&nbsp;</div>
                <div class="inline-block">
                    <select wire:model="perPage" class="leading-snug border border-gray-300 block w-full appearance-none bg-gray-100 text-sm text-gray-600 py-1 px-2 rounded-lg text-center align-middle">
                        <option>5</option>
                        <option>10</option>
                        <option>15</option>
                    </select>
                </div>
            </div>
            <div class="inline-block relative">
                <input wire:model="search" type="text" name="" class="leading-snug border border-gray-300 block w-full appearance-none bg-gray-100 text-sm text-gray-600 py-1 px-4 pl-8 rounded-lg" placeholder="Cari" />
                <div class="pointer-events-none absolute pl-3 inset-y-0 left-0 flex items-center px-2 text-gray-300">
                    <svg class="fill-current h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 511.999 511.999">
                        <path d="M508.874 478.708L360.142 329.976c28.21-34.827 45.191-79.103 45.191-127.309C405.333 90.917 314.416 0 202.666 0S0 90.917 0 202.667s90.917 202.667 202.667 202.667c48.206 0 92.482-16.982 127.309-45.191l148.732 148.732c4.167 4.165 10.919 4.165 15.086 0l15.081-15.082c4.165-4.166 4.165-10.92-.001-15.085zM202.667 362.667c-88.229 0-160-71.771-160-160s71.771-160 160-160 160 71.771 160 160-71.771 160-160 160z" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto mt-6">
            <table class="table-auto border-collapse w-full">
                <thead>
                    <tr class="rounded-lg text-sm font-medium text-gray-700 text-left" style="font-size: 0.9674rem">
                        <th class="px-4 py-2 bg-gray-200" style="background-color:#f8f8f8">
                            <a wire:click.prevent="sortBy('barcode')" role="button" href="#">
                                @include('include.sort-icon', ['field' => 'barcode'])Barcode
                            </a>
                        </th>
                        <th class="px-4 py-2" style="background-color:#f8f8f8">
                            <a wire:click.prevent="sortBy('name')" role="button" href="#">
                                @include('include.sort-icon', ['field' => 'name'])Name
                            </a>
                        </th>
                    </tr>
                </thead>
                <tbody class="text-sm font-normal text-gray-700">
                     @foreach ($items as $i)
                        <tr class="hover:bg-gray-100 border-b border-gray-200 py-10" wire:click="readItem({{ $i->id }})">
                            <td class="px-4 py-4">{{ $i->barcode }}</td>
                            <td class="px-4 py-4">{{ $i->name }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div id="pagination" class="w-full justify-between border-t border-gray-100 pt-4 items-center">
            {{ $items->links() }}
        </div>
    </div>

    <x-jet-dialog-modal wire:model="CmodalState">
        <form>
            <x-slot name="title">
                Tambah Barang
            </x-slot>

            <x-slot name="content">
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <x-jet-label for="barcode" value="Barcode" />
                        <x-jet-input id="barcode" type="text" class="mt-1 block w-full" wire:model="item.barcode" />
                        <x-jet-input-error for="item.barcode" class="mt-2" />
                    </div>

                    <div>
                        <x-jet-label for="name" value="Nama" />
                        <x-jet-input id="name" type="text" class="mt-1 block w-full" wire:model="item.name" />
                        <x-jet-input-error for="item.name" class="mt-2" />
                    </div>

                    <div class="overflow-y-auto h-56">
                        <div class="flex">
                            <div class="flex-1 grid grid-cols-3">
                                <div>
                                    <x-jet-label for="prices.0.name" value="Satuan (Biji, Lusin, Gram, dll)" class="text-center" />
                                    <input  id="prices.0.name" type="text" class="mt-1 block w-full form-input shadow-sm rounded-l-md rounded-r-none" wire:model="prices.0.name">
                                    <x-jet-input-error for="prices.0.name" class="mt-2" />
                                </div>
                                <div>
                                    <x-jet-label for="prices.0.price" value="Harga (Rp)" class="text-center" />
                                    <input  id="prices.0.price" type="number" class="mt-1 block w-full form-input shadow-sm rounded-none" wire:model="prices.0.price">
                                    <x-jet-input-error for="prices.0.price" class="mt-2" />
                                </div>
                                <div>
                                    <x-jet-label for="prices.0.stock" value="Stok" class="text-center" />
                                    <input  id="prices.0.stock" type="number" class="mt-1 block w-full form-input shadow-sm rounded-none" wire:model="prices.0.stock">
                                    <x-jet-input-error for="prices.0.stock" class="mt-2" />
                                </div>
                            </div>
                            <div class="inline-flex">
                                <button class="text-gray-800 font-bold py-2 px-4 inline-flex items-center shadow-sm form-input rounded-l-none rounded-r-md" style="margin-top: 1.55rem; height: 2.6rem;" wire:click="addPrice('C')">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                              </button>
                            </div>
                        </div>

                        @for($i = 1; $i < $CnumPrices; $i++)
                        <div class="flex">
                            <div class="flex-1 grid grid-cols-3">
                                <div>
                                    <input  id="prices.{{ $i }}.name" type="text" class="mt-1 block w-full form-input shadow-sm rounded-l-md rounded-r-none" wire:model="prices.{{ $i }}.name" >
                                    <x-jet-input-error for="prices.{{ $i }}.name" class="mt-2" />
                                </div>
                                <div>
                                    <input  id="prices.{{ $i }}.price" type="number" class="mt-1 block w-full form-input shadow-sm rounded-none" wire:model="prices.{{ $i }}.price" >
                                    <x-jet-input-error for="prices.{{ $i }}.price" class="mt-2" />
                                </div>
                                <div>
                                    <input  id="prices.{{ $i }}.stock" type="number" class="mt-1 block w-full form-input shadow-sm rounded-none" wire:model="prices.{{ $i }}.stock" >
                                    <x-jet-input-error for="prices.{{ $i }}.stock" class="mt-2" />
                                </div>
                            </div>
                            <div class="inline-flex">
                                <button class="text-gray-800 font-bold py-2 px-4 inline-flex items-center shadow-sm form-input rounded-l-none rounded-r-md mt-1" style="height: 2.63rem;" wire:click="removePrice('C', {{ $i }})">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                              </button>
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-jet-secondary-button wire:click="$toggle('CmodalState')" wire:loading.attr="disabled">
                    Batalkan
                </x-jet-secondary-button>

                <x-jet-button class="ml-2" wire:click.prevent="addItem" wire:loading.attr="disabled">
                    Tambah
                </x-jet-button>
            </x-slot>
        </form>
    </x-jet-dialog-modal>

    <x-jet-dialog-modal wire:model="RmodalState">
        <x-slot name="title">
            {{ $RmodalData->name ?? '' }}
        </x-slot>

        <x-slot name="content">
            @if($RmodalData)
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <x-jet-label for="RmodalData.barcode" value="Barcode" />
                    <x-jet-input id="RmodalData.barcode" type="text" class="mt-1 block w-full" wire:model="RmodalData.barcode" />
                    <x-jet-input-error for="RmodalData.barcode" class="mt-2" />
                </div>

                <div>
                    <x-jet-label for="RmodalData.name" value="Nama" />
                    <x-jet-input id="RmodalData.name" type="text" class="mt-1 block w-full" wire:model="RmodalData.name" />
                    <x-jet-input-error for="RmodalData.name" class="mt-2" />
                </div>

                <div class="overflow-y-auto h-56">
                    @for($i = 0; $i < $RnumPrices; $i++)
                    @if($i == 0)
                    <div class="flex">
                        <div class="flex-1 grid grid-cols-3">
                            <div>
                                <x-jet-label for="RmodalData.prices.0.name" value="Satuan (Biji, Lusin, Gram, dll)" class="text-center" />
                                <input  id="RmodalData.prices.0.name" type="text" class="mt-1 block w-full form-input shadow-sm rounded-l-md rounded-r-none" wire:model="RmodalData.prices.0.name" >
                                <x-jet-input-error for="RmodalData.prices.0.name" class="mt-2" />
                            </div>
                            <div>
                                <x-jet-label for="RmodalData.prices.0.price" value="Harga (Rp)" class="text-center" />
                                <input  id="RmodalData.prices.0.price" type="number" class="mt-1 block w-full form-input shadow-sm rounded-none" wire:model="RmodalData.prices.0.price" >
                                <x-jet-input-error for="RmodalData.prices.0.price" class="mt-2" />
                            </div>
                            <div>
                                <x-jet-label for="RmodalData.prices.0.stock" value="Stok" class="text-center" />
                                <input  id="RmodalData.prices.0.stock" type="number" class="mt-1 block w-full form-input shadow-sm rounded-none" wire:model="RmodalData.prices.0.stock" >
                                <x-jet-input-error for="RmodalData.prices.0.stock" class="mt-2" />
                            </div>
                        </div>
                        <div class="inline-flex">
                            <button class="text-gray-800 font-bold py-2 px-4 inline-flex items-center shadow-sm form-input rounded-l-none rounded-r-md" style="margin-top: 1.55rem; height: 2.6rem;" wire:click="addPrice('R')">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            </button>
                        </div>
                    </div>
                    @else
                    <div class="flex">
                        <div class="flex-1 grid grid-cols-3">
                            <div>
                                <input  id="RmodalData.prices.{{ $i }}.name" type="text" class="mt-1 block w-full form-input shadow-sm rounded-l-md rounded-r-none" wire:model="RmodalData.prices.{{ $i }}.name" >
                                <x-jet-input-error for="RmodalData.prices.{{ $i }}.name" class="mt-2" />
                            </div>
                            <div>
                                <input  id="RmodalData.prices.{{ $i }}.price" type="number" class="mt-1 block w-full form-input shadow-sm rounded-none" wire:model="RmodalData.prices.{{ $i }}.price" >
                                <x-jet-input-error for="RmodalData.prices.{{ $i }}.price" class="mt-2" />
                            </div>
                            <div>
                                <input  id="RmodalData.prices.{{ $i }}.stock" type="number" class="mt-1 block w-full form-input shadow-sm rounded-none" wire:model="RmodalData.prices.{{ $i }}.stock" >
                                <x-jet-input-error for="RmodalData.prices.{{ $i }}.stock" class="mt-2" />
                            </div>
                        </div>
                        <div class="inline-flex">
                            <button class="text-gray-800 font-bold py-2 px-4 inline-flex items-center shadow-sm form-input rounded-l-none rounded-r-md mt-1" style="height: 2.63rem;" wire:click="removePrice('R', {{ $i }})">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                            </button>
                        </div>
                    </div>
                    @endif
                    @endfor
                </div>
            </div>
            @endif
        </x-slot>

        <x-slot name="footer">
            <div class="flex justify-between">
                <x-jet-danger-button wire:click.prevent="deleteItem({{ $RmodalData['id'] ?? 0 }})" wire:loading.attr="disabled">
                    Hapus
                </x-jet-danger-button>

                <div>
                    <x-jet-secondary-button wire:click="$toggle('RmodalState')" wire:loading.attr="disabled">
                        Batalkan
                    </x-jet-secondary-button>

                    <x-jet-button class="ml-2" wire:click.prevent="updateItem({{ $RmodalData['id'] ?? 0 }})" wire:loading.attr="disabled">
                        Perbarui
                    </x-jet-button>
                </div>
            </div>
        </x-slot>
    </x-jet-dialog-modal>

    <style>
        thead tr th:first-child { border-top-left-radius: 10px; border-bottom-left-radius: 10px;}
        thead tr th:last-child { border-top-right-radius: 10px; border-bottom-right-radius: 10px;}

        tbody tr td:first-child { border-top-left-radius: 5px; border-bottom-left-radius: 0px;}
        tbody tr td:last-child { border-top-right-radius: 5px; border-bottom-right-radius: 0px;}
    </style>
</div>
