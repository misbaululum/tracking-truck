<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Tracking;
use Illuminate\Support\Collection;

class LiveUpdateWidget extends Component
{
    public Collection $liveRecords;
    public $liveLimit = 3;
    public $liveTotal = 0;
    public $search = ''; // Variable untuk menampung input search

    // Reset limit ke 3 setiap kali user mengetik search baru
    public function updatingSearch()
    {
        $this->liveLimit = 3;
    }

    public function mount()
    {
        $this->loadDataForLiveUpdate();
    }

    public function loadDataForLiveUpdate()
    {
        $query = Tracking::query();

        // 1. Filter Pencarian (Jika ada input)
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('plate_number', 'like', '%' . $this->search . '%')
                  ->orWhere('vehicle_name', 'like', '%' . $this->search . '%')
                  ->orWhere('driver_name', 'like', '%' . $this->search . '%')
                  ->orWhere('company_name', 'like', '%' . $this->search . '%');
            });
        }

        // 2. Hitung total data yang sesuai pencarian (untuk tombol Load More)
        $this->liveTotal = $query->count();

        // 3. Urutkan (Active dulu, baru Completed, lalu Terbaru) & Limit
        $this->liveRecords = $query->orderByRaw("CASE WHEN current_stage = 'completed' THEN 1 ELSE 0 END")
                                   ->latest()
                                   ->take($this->liveLimit)
                                   ->get();
    }

    public function loadMoreLive()
    {
        $this->liveLimit += 3;
    }

    public function render()
    {
        $this->loadDataForLiveUpdate();
        return view('livewire.live-update-widget');
    }
}