<?php

namespace App\Livewire\Backend;

use App\Models\User;
use Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use App\Models\Article as ModelArticle;
use Illuminate\Support\Facades\Storage;

#[Title('Articles')]
#[Layout('components.layouts-backend.app')]
class Article extends Component
{
    use WithPagination, WithFileUploads;

    // filters
    public $search = '';
    public $paginate = 10;
    public $filterStatus = '';

    // form fields
    public $article_id;
    public $title = '';
    public $slug  = '';
    public $content = '';
    public $image;            // for uploaded file (temporary)
    public $current_image;    // stored path for preview when edit
    public $author_id = '';
    public $category_id = '';
    public $status = 'draft';

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
        $rows = ModelArticle::query()
            ->when($this->search, function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                    ->orWhere('slug', 'like', "%{$this->search}%")
                    ->orWhere('content', 'like', "%{$this->search}%");
            })
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->with(['author:id,name', 'category:id,name'])
            ->orderByDesc('created_at')
            ->paginate($this->paginate);

        $categories = Category::orderBy('name')->get(['id', 'name']);
        $userModelClass = config('auth.providers.users.model');
        $authors = $userModelClass::query()->orderBy('name')->get(['id', 'name']);

        return view('livewire.backend.article', compact('rows', 'categories', 'authors'));
    }

    public function resetInputFields()
    {
        $this->article_id = null;
        $this->title = '';
        $this->slug = '';
        $this->content = '';
        $this->image = null;
        $this->current_image = null;
        $this->author_id = Auth::user()?->id ?? '';
        $this->category_id = '';
        $this->status = 'draft';
        $this->isEdit = false;
        $this->dispatch('clear-editor');
    }

    public function store()
    {
        $data = $this->validate([
            'title'       => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:articles,slug',
            'content'     => 'required|string',
            'image'       => 'nullable|image|max:2048', // 2MB
            'author_id'   => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'status'      => 'required|in:draft,published,archived',
        ]);

        // slug
        $data['slug'] = $data['slug'] ? Str::slug($data['slug']) : Str::slug($this->title);

        // upload image (optional)
        if ($this->image) {
            $data['image'] = $this->image->store('uploads/articles', 'public');
        }

        ModelArticle::create($data);

        $this->resetInputFields();
        $this->js("$('#articleModal').modal('hide')");
        $this->toast('success', 'Artikel disimpan');
    }

    public function edit($id)
    {
        $a = ModelArticle::findOrFail($id);

        $this->article_id  = $a->id;
        $this->title       = $a->title;
        $this->slug        = $a->slug;
        $this->content     = $a->content;
        $this->current_image = $a->image;
        $this->image       = null; // reset image upload
        $this->author_id   = $a->author_id;
        $this->category_id = $a->category_id;
        $this->status      = $a->status;

        $this->isEdit = true;
        $this->dispatch('editor-content-updated', content: $a->content);
        $this->js("$('#articleModal').modal('show')");
    }

    public function update()
    {
        $a = ModelArticle::findOrFail($this->article_id);

        $data = $this->validate([
            'title'       => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:articles,slug,' . $a->id,
            'content'     => 'required|string',
            'image'       => 'nullable|image|max:2048',
            'author_id'   => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'status'      => 'required|in:draft,published,archived',
        ]);

        $data['slug'] = $data['slug'] ? Str::slug($data['slug']) : Str::slug($this->title);

        // Handle image upload
        if ($this->image) {
            // Hapus gambar lama jika ada
            if ($a->image && Storage::disk('public')->exists($a->image)) {
                Storage::disk('public')->delete($a->image);
            }
            // Upload gambar baru
            $data['image'] = $this->image->store('uploads/articles', 'public');
        } else {
            // Jika tidak ada upload baru, hapus 'image' dari array $data
            // Agar tidak mengupdate field image di database
            unset($data['image']);
        }

        $a->update($data);

        $this->resetInputFields();
        $this->toast('success', 'Artikel diperbarui');
        $this->js("$('#articleModal').modal('hide')");
    }

    public function delete_confirmation($id)
    {
        $this->article_id = $id;
        $this->js(<<<'JS'
        Swal.fire({
            title: 'Hapus artikel?',
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
        $a = ModelArticle::find($this->article_id);
        if ($a) {
            if ($a->image && Storage::disk('public')->exists($a->image)) {
                Storage::disk('public')->delete($a->image);
            }
            $a->delete();
        }
        $this->toast('error', 'Artikel dihapus');
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
