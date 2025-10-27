<?php

namespace App\Livewire;

use App\Models\ShortUrl;
use Livewire\Component;
use Livewire\WithPagination;

class ShowShortUrls extends Component
{
    use WithPagination;

    public string $query = '';

    public function search()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = ShortUrl::query();
        if (!empty($this->query)) {
            $query->whereLike('original_url', '%' . $this->query . '%');
        }
        return view('livewire.show-short-urls', [
            'shortUrls' => $query->paginate(10),
        ]);
    }
}
