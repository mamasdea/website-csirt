<?php

namespace App\Livewire\Backend;

use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Models\Page as ModelPage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

#[Title('Pages')]
#[Layout('components.layouts-backend.app')]
class Pages extends Component
{
    use WithPagination, WithFileUploads;

    // filters (mengikuti pola Article)
    public $search = '';
    public $paginate = 10;
    public $filterStatus = ''; // '' | 'published' | 'draft' (tanpa archived)

    // form fields
    public $page_id;
    public $title = '';
    public $slug  = '';
    public $content = '';
    public $image;           // temporary upload
    public $current_image;   // stored path for preview when edit
    public $file;
    public $current_file;
    public $page_type = '';
    public $is_published = false;
    public $is_file = false;

    public $isEdit = false;

    protected $queryString = ['search', 'filterStatus'];

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingPaginate()
    {
        $this->resetPage();
    }
    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function render()
    {
        $rows = ModelPage::query()
            ->when($this->search, function ($q) {
                $q->where(function ($qq) {
                    $qq->where('title', 'like', "%{$this->search}%")
                        ->orWhere('slug', 'like', "%{$this->search}%")
                        ->orWhere('content', 'like', "%{$this->search}%");
                });
            })
            ->when($this->filterStatus, function ($q) {
                if ($this->filterStatus === 'published') {
                    $q->where('is_published', true);
                } elseif ($this->filterStatus === 'draft') {
                    $q->where('is_published', false);
                }
            })
            ->orderByDesc('created_at')
            ->paginate($this->paginate);

        return view('livewire.backend.pages', compact('rows'));
    }

    public function resetInputFields()
    {
        $this->page_id = null;
        $this->title = '';
        $this->slug = '';
        $this->content = '';
        $this->image = null;
        $this->current_image = null;
        $this->file = null;
        $this->current_file = null;
        $this->page_type = '';
        $this->is_published = false;
        $this->is_file = false;
        $this->isEdit = false;
        $this->dispatch('clear-editor'); // sama seperti Article
    }

    public function store()
    {
        $data = $this->validate([
            'title'        => 'required|string|max:255',
            'slug'         => 'nullable|string|max:255|unique:pages,slug',
            'content'      => 'nullable|string',
            'image'        => 'nullable|image|max:2048', // 2MB
            'file'         => 'nullable|file|mimes:pdf|max:10240', // 10MB
            'page_type'    => 'required|string|max:100',
            'is_published' => 'boolean',
            'is_file'      => 'boolean',
        ]);

        $data['slug'] = $data['slug'] ? Str::slug($data['slug']) : Str::slug($this->title);

        if ($this->image) {
            $data['image'] = $this->image->store('uploads/pages', 'gcs');
        }

        if ($this->file) {
            $data['file'] = $this->file->store('uploads/files', 'public');
        }

        ModelPage::create($data);

        $this->resetInputFields();
        $this->js("$('#pageModal').modal('hide')");
        $this->toast('success', 'Halaman disimpan');
    }

    public function edit($id)
    {
        $p = ModelPage::findOrFail($id);

        $this->page_id       = $p->id;
        $this->title         = $p->title;
        $this->slug          = $p->slug;
        $this->content       = $p->content;
        $this->current_image = $p->image;
        $this->image         = null;
        $this->current_file  = $p->file;
        $this->file          = null;
        $this->page_type     = $p->page_type;
        $this->is_published  = (bool)$p->is_published;
        $this->is_file       = (bool)$p->is_file;

        $this->isEdit = true;
        $this->dispatch('editor-content-updated', content: $p->content); // sama seperti Article
        $this->dispatch('file-updated', fileUrl: $p->file ? asset('storage/' . $p->file) : null);
        $this->js("$('#pageModal').modal('show')");
    }

    public function update()
    {
        $p = ModelPage::findOrFail($this->page_id);

        $data = $this->validate([
            'title'        => 'required|string|max:255',
            'slug'         => 'nullable|string|max:255|unique:pages,slug,' . $p->id,
            'content'      => 'nullable|string',
            'image'        => 'nullable|image|max:2048',
            'file'         => 'nullable|file|mimes:pdf|max:10240',
            'page_type'    => 'required|string|max:100',
            'is_published' => 'boolean',
            'is_file'      => 'boolean',
        ]);

        $data['slug'] = $data['slug'] ? Str::slug($data['slug']) : Str::slug($this->title);

        if ($this->image) {
            if ($p->image && Storage::disk('public')->exists($p->image)) {
                Storage::disk('public')->delete($p->image);
            }
            $data['image'] = $this->image->store('uploads/pages', 'gcs');
        } else {
            unset($data['image']);
        }

        if ($this->file) {
            if ($p->file && Storage::disk('public')->exists($p->file)) {
                Storage::disk('public')->delete($p->file);
            }
            $data['file'] = $this->file->store('uploads/files', 'public');
        } else {
            unset($data['file']);
        }

        $p->update($data);

        $this->resetInputFields();
        $this->toast('success', 'Halaman diperbarui');
        $this->js("$('#pageModal').modal('hide')");
    }

    public function delete_confirmation($id)
    {
        $this->page_id = $id;
        $this->js(<<<'JS'
        Swal.fire({
            title: 'Hapus halaman?',
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
        $p = ModelPage::find($this->page_id);
        if ($p) {
            if ($p->image && Storage::disk('gcs')->exists($p->image)) {
                Storage::disk('gcs')->delete($p->image);
            }
            if ($p->file && Storage::disk('public')->exists($p->file)) {
                Storage::disk('public')->delete($p->file);
            }
            $p->delete();
        }
        $this->toast('error', 'Halaman dihapus');
    }

    public function generateSlug()
    {
        if (!$this->slug && $this->title) {
            $this->slug = Str::slug($this->title);
        }
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
