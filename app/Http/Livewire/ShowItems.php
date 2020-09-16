<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Item;

class ShowItems extends Component
{
    use WithPagination;

    public $perPage = 5;
    public $sortField = 'name';
    public $sortAsc = true;
    public $search = '';

    public $modalState = false;

    public Item $item;

    protected $rules = [
        'item.barcode' => 'required|string',
        'item.name' => 'required|string',
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
        $this->validate();

        $this->item->save();

        session()->flash('message', 'Berhasil menambah barang.');
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
