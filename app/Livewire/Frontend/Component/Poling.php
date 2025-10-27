<?php

namespace App\Livewire\Frontend\Component;

use Livewire\Component;
use App\Models\Poll;
use App\Models\PollVoter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class Poling extends Component
{
    // Properti untuk menyimpan data polling
    public $poll;

    // Properti status voting
    public $hasVoted = false;
    public $selectedOptionId = null;
    public $optionIdToVote; // Untuk menampung pilihan radio button dari form

    // Daftar IP yang sudah vote (simulasi tabel voters terpisah)
    // Dalam aplikasi nyata, ini harus disimpan dalam tabel database (e.g., 'poll_voters')
    protected $voters = [];

    public function mount()
    {
        $this->poll = Poll::where('is_active', true)->first();

        if ($this->poll) {
            $ipAddress = Request::ip();
            $this->hasVoted = PollVoter::where('poll_id', $this->poll->id)
                ->where('ip_address', $ipAddress)
                ->exists();
        }
    }

    // Properti untuk validasi Livewire
    protected $rules = [
        'optionIdToVote' => 'required|integer',
    ];

    /**
     * Memproses vote yang dikirim pengguna.
     */
    public function submitVote()
    {
        // 1. Validasi
        $this->validate();

        if (!$this->poll || $this->hasVoted) {
            session()->flash('error', 'Polling tidak ditemukan atau Anda sudah memberikan suara.');
            return;
        }

        $ipAddress = Request::ip();

        if (PollVoter::where('poll_id', $this->poll->id)->where('ip_address', $ipAddress)->exists()) {
            $this->hasVoted = true;
            session()->flash('error', 'Anda hanya bisa memberikan suara satu kali.');
            return;
        }

        $options = $this->poll->options_with_votes;

        $optionFound = false;
        foreach ($options as $key => $option) {
            if ($option['id'] == $this->optionIdToVote) {
                // Tambahkan 1 suara
                $options[$key]['votes']++;
                $optionFound = true;
                break;
            }
        }

        if ($optionFound) {
            // 4. Simpan kembali array yang diubah ke database
            $this->poll->options_with_votes = $options;
            $this->poll->save();

            PollVoter::create([
                'poll_id' => $this->poll->id,
                'ip_address' => $ipAddress,
            ]);

            Log::info('Poll vote submitted', [
                'poll_id' => $this->poll->id,
                'option_id' => $this->optionIdToVote,
                'ip_address' => $ipAddress,
            ]);

            $this->selectedOptionId = $this->optionIdToVote;
            $this->hasVoted = true;
            $this->poll->refresh();
            session()->flash('success', 'Terima kasih atas partisipasi Anda!');
        } else {
            session()->flash('error', 'Opsi tidak valid.');
        }
    }

    public function render()
    {
        return view('livewire.frontend.component.poling');
    }
}
