<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Item;
use App\Models\Price;

class ShowItems extends Component
{
    use WithPagination;

    public $perPage = 5;
    public $sortField = 'name';
    public $sortAsc = true;
    public $search = '';

    public $modalState = false;

    public Item $item;

    public $numPrices = 1;
    public $prices = [];

    public $rules = [
        'item.barcode' => 'required|string',
        'item.name' => 'required|string',

        'prices.0.name' => 'required|string',
        'prices.0.price' => 'required|integer',
        'prices.0.stock' => 'required|integer',
    ];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortAsc = ! $this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }

    public function addUser()
    {
        $this->validate($this->rules);

        $this->item->save();
        foreach ($this->prices as $price) {
            $p = new Price;
            $p->name = $price['name'];
            $p->price = $price['price'];
            $p->stock = $price['stock'];
            $p->item_id = $this->item->id;
            $p->save();
        }

        $this->numPrices = 1;
        $this->prices = [];
        $this->modalState = false;
        session()->flash('message', 'Berhasil menambah barang.');
    }

    public function addPrice()
    {
        $this->rules['prices.' . $this->numPrices . '.name'] = 'required|string';
        $this->rules['prices.' . $this->numPrices . '.price'] = 'required|integer';
        $this->rules['prices.' . $this->numPrices . '.stock'] = 'required|integer';
        $this->numPrices++;
    }

    public function removePrice($num = 0)
    {
        array_splice($this->prices, $num, 1);
        $this->numPrices--;
        unset($this->rules['prices.' . $this->numPrices . '.name']);
        unset($this->rules['prices.' . $this->numPrices . '.price']);
        unset($this->rules['prices.' . $this->numPrices . '.stock']);
    }

    public function mount()
    {
        $this->item = new Item;
    }

    public function render()
    {
        return view('livewire.show-items', [
            'items' => Item::search($this->search)
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage)
        ]);
    }
}
