<?php

namespace App\Livewire\Backend;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title('Dashboard')]
#[Layout('components.layouts-backend.app')]
class Dashboard extends Component
{
    // Properti ini akan menampung data ringkasan,
    // yang nanti Anda isi dari query database (misalnya: Incident::where('status_id', 1)->count()).
    public $summaryData = [
        'new_incidents' => 3,
        'in_progress' => 12,
        'published_articles' => 45,
        'incident_trend' => [ /* data untuk grafik */],
    ];

    public function render()
    {
        // Menggunakan layout yang sudah Anda buat
        return view('livewire.backend.dashboard');
    }
}
