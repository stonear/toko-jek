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

    public $CmodalState = false;
    public $RmodalState = false;
    public $RmodalData;

    public $item;

    public $CnumPrices = 1;
    public $RnumPrices = 1;
    public $prices = [];

    protected $rules = [
        'item.barcode' => 'required|string',
        'item.name' => 'required|string',

        'prices.0.name' => 'required|string',
        'prices.0.price' => 'required|integer',
        'prices.0.stock' => 'required|integer',

        'RmodalData.barcode' => 'required|string',
        'RmodalData.name' => 'required|string',

        'RmodalData.prices.0.name' => 'required|string',
        'RmodalData.prices.0.price' => 'required|integer',
        'RmodalData.prices.0.stock' => 'required|integer',
    ];
    public $Crules = [
        'item.barcode' => 'required|string',
        'item.name' => 'required|string',

        'prices.0.name' => 'required|string',
        'prices.0.price' => 'required|integer',
        'prices.0.stock' => 'required|integer',
    ];
    public $Rrules = [
        'RmodalData.barcode' => 'required|string',
        'RmodalData.name' => 'required|string',

        'RmodalData.prices.0.name' => 'required|string',
        'RmodalData.prices.0.price' => 'required|integer',
        'RmodalData.prices.0.stock' => 'required|integer',
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

    public function addItem()
    {
        $this->validate($this->Crules);

        $this->item->save();
        foreach ($this->prices as $price) {
            $p = new Price;
            $p->name = $price['name'];
            $p->price = $price['price'];
            $p->stock = $price['stock'];
            $p->item_id = $this->item->id;
            $p->save();
        }

        $this->CnumPrices = 1;
        $this->prices = [];
        $this->CmodalState = false;
        session()->flash('message', 'Berhasil menambah barang.');
    }

    public function readItem($id = 0)
    {
        $this->RmodalData = Item::with('prices')->firstWhere('id', $id)->toArray();
        $this->RnumPrices = count($this->RmodalData['prices']);
        foreach ( $this->Rrules as $key => $value) {
            if (strpos($key, 'RmodalData.prices.') === 0) {
                unset($this->Rrules[$key]);
            }
        }
        for($i = 0; $i < $this->RnumPrices; $i++) {
            $this->Rrules['RmodalData.prices.' . $i . '.name'] = 'required|string';
            $this->Rrules['RmodalData.prices.' . $i . '.price'] = 'required|integer';
            $this->Rrules['RmodalData.prices.' . $i . '.stock'] = 'required|integer';
        }
        if ($this->RmodalData) $this->RmodalState = true;
    }

    public function updateItem($id = 0)
    {
        $this->validate($this->Rrules);

        Item::where('id', $id)->update([
            'barcode' => $this->RmodalData['barcode'],
            'name' => $this->RmodalData['name']
        ]);
        $usedPriceId = [];
        foreach ($this->RmodalData['prices'] as $price) {
            if (array_key_exists("id", $price)) {
                Price::where('id', $price['id'])->update([
                    'name' => $price['name'],
                    'price' => $price['price'],
                    'stock' => $price['stock']
                ]);
                $usedPriceId[] = $price['id'];
            } else {
                $p = new Price;
                $p->name = $price['name'];
                $p->price = $price['price'];
                $p->stock = $price['stock'];
                $p->item_id = $id;
                $p->save();
                $usedPriceId[] = $p->id;
            }
        }
        Price::where('item_id', $id)->whereNotIn('id', $usedPriceId)->delete();

        $this->RmodalState = false;
        session()->flash('message', 'Berhasil memperbarui barang.');
    }

    public function deleteItem($id = 0)
    {
        $deletedRows = Item::where('id', $id)->delete();
        $deletedRows = Price::where('item_id', $id)->delete();
        $this->RmodalState = false;
        session()->flash('message', 'Berhasil menghapus barang.');
    }

    public function addPrice($action = '')
    {
        if ($action == 'C') {
            $this->Crules['prices.' . $this->CnumPrices . '.name'] = 'required|string';
            $this->Crules['prices.' . $this->CnumPrices . '.price'] = 'required|integer';
            $this->Crules['prices.' . $this->CnumPrices . '.stock'] = 'required|integer';
            $this->CnumPrices++;
        } elseif ($action == 'R') {
            $this->Rrules['RmodalData.prices.' . $this->RnumPrices . '.name'] = 'required|string';
            $this->Rrules['RmodalData.prices.' . $this->RnumPrices . '.price'] = 'required|integer';
            $this->Rrules['RmodalData.prices.' . $this->RnumPrices . '.stock'] = 'required|integer';
            $this->RnumPrices++;
        }
    }

    public function removePrice($action = '', $num = 0)
    {
        if ($action == 'C') {
            array_splice($this->prices, $num, 1);
            $this->CnumPrices--;
            unset($this->Crules['prices.' . $this->CnumPrices . '.name']);
            unset($this->Crules['prices.' . $this->CnumPrices . '.price']);
            unset($this->Crules['prices.' . $this->CnumPrices . '.stock']);
         } elseif ($action == 'R') {
            array_splice($this->RmodalData['prices'], $num, 1);
            $this->RnumPrices--;
            unset($this->Rrules['RmodalData.prices.' . $this->RnumPrices . '.name']);
            unset($this->Rrules['RmodalData.prices.' . $this->RnumPrices . '.price']);
            unset($this->Rrules['RmodalData.prices.' . $this->RnumPrices . '.stock']);
        }
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
