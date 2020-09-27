<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Item;

class Cart extends Component
{
    use WithPagination;

    public $perPage = 5;
    public $sortField = 'name';
    public $sortAsc = true;
    public $search = '';

    public $RmodalState = false;
    public $RmodalData;

    public $cart = [];

    protected $rules = [
        'RmodalData.priceId' => 'required|integer',
        'RmodalData.amount' => 'required|integer',
    ];

    public function addItem()
    {
        $this->validate();

        foreach ($this->RmodalData['prices'] as $p) {
            if ($p['id'] == $this->RmodalData['priceId']) {
                $price_name = $p['name'];
                $price = $p['price'];
                break;
            }
        }

        $found = false;
        foreach ($this->cart['items']  as $key => $c) {
            if ($c['id'] == $this->RmodalData['id'] and $c['price']['id'] == $this->RmodalData['priceId']) {
                $found = true;

                $this->cart['items'][$key]['amount'] += $this->RmodalData['amount'];
                $this->cart['items'][$key]['total'] = $c['price']['price'] * $this->cart['items'][$key]['amount'];
            }
        }
        if (!$found) {
            $this->cart['items'][] = [
                'id' => $this->RmodalData['id'],
                'name' => $this->RmodalData['name'],
                'price' => [
                    'id' => $this->RmodalData['priceId'],
                    'name' => $price_name,
                    'price' => $price
                ],
                'amount' => $this->RmodalData['amount'],
                'total' => $price * $this->RmodalData['amount']
            ];
        }

        $this->RmodalState = false;
    }

    public function removeItem($id = 0, $priceId = 0)
    {
        foreach ($this->cart['items'] as $key => $c) {
            if ($c['id'] == $id and $c['price']['id'] == $priceId) {
                unset($this->cart['items'][$key]);
            }
        }
    }

    public function emptyItem()
    {
        $this->cart = [
            'total' => 0,
            'items' => []
        ];
    }

    public function readItem($id = 0)
    {
        $this->RmodalData = Item::with('prices')->firstWhere('id', $id)->toArray();
        $this->RmodalData['priceId'] = $this->RmodalData['prices'][0]['id'] ?? NULL;
        $this->RmodalData['amount'] = NULL;
        if ($this->RmodalData) $this->RmodalState = true;
    }

    public function submit()
    {
        $this->emptyItem();
        session()->flash('message', 'Berhasil menyimpan transaksi.');
    }

    public function mount()
    {
        $this->cart = [
            'total' => 0,
            'items' => []
        ];
    }

    public function render()
    {
        return view('livewire.cart', [
            'items' => Item::search($this->search)
                ->with('prices')
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage)
        ]);
    }
}
