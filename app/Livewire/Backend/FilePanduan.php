<?php

namespace App\Livewire\Backend;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Storage;
use App\Models\FilePanduan as ModelFilePanduan;

#[Title('File Panduan')]
#[Layout('components.layouts-backend.app')]
class FilePanduan extends Component
{
    use WithPagination, WithFileUploads;

    // filters
    public $search = '';
    public $paginate = 10;

    // form fields
    public $file_panduan_id;
    public $title = '';
    public $file;             // for uploaded file (temporary)
    public $current_file;     // stored path for preview when edit

    public $isEdit = false;

    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingPaginate()
    {
        $this->resetPage();
    }

    public function render()
    {
        $rows = ModelFilePanduan::query()
            ->when($this->search, function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                    ->orWhere('file', 'like', "%{$this->search}%");
            })
            ->orderByDesc('created_at')
            ->paginate($this->paginate);

        return view('livewire.backend.file-panduan', compact('rows'));
    }

    public function resetInputFields()
    {
        $this->file_panduan_id = null;
        $this->title = '';
        $this->file = null;
        $this->current_file = null;
        $this->isEdit = false;
    }

    public function store()
    {
        $data = $this->validate([
            'title' => 'required|string|max:255',
            'file'  => 'required|file|max:10240', // 10MB
        ]);

        // upload file
        if ($this->file) {
            $data['file'] = $this->file->store('uploads/file_panduans', 'public');
        }

        ModelFilePanduan::create($data);

        $this->resetInputFields();
        $this->js("$('#filePanduanModal').modal('hide')");
        $this->toast('success', 'File panduan disimpan');
    }

    public function edit($id)
    {
        $filePanduan = ModelFilePanduan::findOrFail($id);

        $this->file_panduan_id = $filePanduan->id;
        $this->title = $filePanduan->title;
        $this->current_file = $filePanduan->file;
        $this->file = null; // reset file upload

        $this->isEdit = true;
        $this->js("$('#filePanduanModal').modal('show')");
    }

    public function update()
    {
        $filePanduan = ModelFilePanduan::findOrFail($this->file_panduan_id);

        $data = $this->validate([
            'title' => 'required|string|max:255',
            'file'  => 'nullable|file|max:10240', // 10MB
        ]);

        // Handle file upload
        if ($this->file) {
            // Hapus file lama jika ada
            if ($filePanduan->file && Storage::disk('public')->exists($filePanduan->file)) {
                Storage::disk('public')->delete($filePanduan->file);
            }
            // Upload file baru
            $data['file'] = $this->file->store('uploads/file_panduans', 'public');
        } else {
            // Jika tidak ada upload baru, hapus 'file' dari array $data
            // Agar tidak mengupdate field file di database
            unset($data['file']);
        }

        $filePanduan->update($data);

        $this->resetInputFields();
        $this->toast('success', 'File panduan diperbarui');
        $this->js("$('#filePanduanModal').modal('hide')");
    }

    public function delete_confirmation($id)
    {
        $this->file_panduan_id = $id;
        $this->js(<<<'JS'
        Swal.fire({
            title: 'Hapus file panduan?',
            text: "Tindakan ini tidak dapat dibatalkan.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, hapus!"
        }).then((res)=>{ if(res.isConfirmed){ $wire.delete() }});
        JS);
    }

    public function delete()
    {
        $filePanduan = ModelFilePanduan::find($this->file_panduan_id);
        if ($filePanduan) {
            if ($filePanduan->file && Storage::disk('public')->exists($filePanduan->file)) {
                Storage::disk('public')->delete($filePanduan->file);
            }
            $filePanduan->delete();
        }
        $this->toast('error', 'File panduan dihapus');
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
