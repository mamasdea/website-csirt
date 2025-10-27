<?php

namespace App\Livewire\Backend;

use App\Models\Setting;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Storage;

#[Title('Web Settings')]
#[Layout('components.layouts-backend.app')]
class SettingWeb extends Component
{
    use WithFileUploads;

    public $setting_id;
    public $name;
    public $description;
    public $no_telp;
    public $email;
    public $address;
    public $link_aduan;
    public $maps_embed;
    public $logo; // temporary upload
    public $current_logo; // stored path for preview

    public function mount()
    {
        $setting = Setting::first();
        if ($setting) {
            $this->setting_id   = $setting->id;
            $this->name         = $setting->name;
            $this->description  = $setting->description;
            $this->no_telp      = $setting->no_telp;
            $this->email        = $setting->email;
            $this->address      = $setting->address;
            $this->link_aduan   = $setting->link_aduan;
            $this->maps_embed   = $setting->maps_embed;
            $this->current_logo = $setting->logo;
        }
    }

    public function save()
    {
        $rules = [
            'name'        => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'no_telp'     => 'nullable|string|max:255',
            'email'       => 'nullable|email|max:255',
            'address'     => 'nullable|string',
            'link_aduan'  => 'nullable|url|max:255',
            'maps_embed'  => 'nullable|string',
            'logo'        => 'nullable|image|max:2048', // 2MB
        ];

        $data = $this->validate($rules);

        if ($this->logo) {
            if ($this->current_logo && Storage::disk('public')->exists($this->current_logo)) {
                Storage::disk('public')->delete($this->current_logo);
            }
            $data['logo'] = $this->logo->store('uploads/settings', 'public');
        } else {
            // If no new logo is uploaded, retain the current one if it exists
            $data['logo'] = $this->current_logo;
        }

        if ($this->setting_id) {
            // Update existing setting
            $setting = Setting::find($this->setting_id);
            $setting->update($data);
            $this->toast('success', 'Pengaturan diperbarui');
        } else {
            // Create new setting
            Setting::create($data);
            $this->toast('success', 'Pengaturan disimpan');
        }

        // Refresh current_logo after save
        $this->current_logo = $data['logo'] ?? null;
        $this->logo = null; // Clear temporary upload

        $this->dispatch('clear-logo-input'); // Dispatch event to clear file input
    }

    public function render()
    {
        return view('livewire.backend.setting-web');
    }

    private function toast($icon, $title)
    {
        $this->js(<<<JS
        const Toast = Swal.mixin({
            toast:true, position:'top-end', showConfirmButton:false,
            timer:1800, timerProgressBar:true,
            didOpen:(t)=>{ t.onmouseenter=Swal.stopTimer; t.onmouseleave=Swal.resumeTimer; }
        });
        Toast.fire({icon:'$icon', title:'$title'});
        JS);
    }
}
