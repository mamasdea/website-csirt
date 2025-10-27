<?php

namespace App\Livewire\Backend;

use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Models\Category as ModelCategory;

#[Title('Kategori')]
#[Layout('components.layouts-backend.app')]
class Category extends Component
{
    use WithPagination;

    public $search = '';
    public $paginate = 10;

    public $category_id;
    public $name = '';
    public $slug = '';
    public $isEdit = false;

    protected $listeners = ['delete' => 'delete'];

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
        $rows = ModelCategory::query()
            ->when(
                $this->search,
                fn($q) =>
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('slug', 'like', '%' . $this->search . '%')
            )
            ->orderBy('name')
            ->paginate($this->paginate);

        return view('livewire.backend.category', ['rows' => $rows]);
    }

    public function resetInputFields()
    {
        $this->category_id = null;
        $this->name = '';
        $this->slug = '';
        $this->isEdit = false;
    }

    public function store()
    {
        $data = $this->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
        ]);

        // generate slug jika kosong
        $data['slug'] = $data['slug'] ?: Str::slug($this->name);

        // pastikan unik
        $base = $data['slug'];
        $i = 1;
        while (ModelCategory::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $base . '-' . $i++;
        }

        ModelCategory::create($data);

        $this->resetInputFields();
        $this->js("$('#categoryModal').modal('hide');");
        $this->toast('success', 'Data berhasil disimpan');
    }

    public function edit($id)
    {
        $cat = ModelCategory::findOrFail($id);
        $this->category_id = $cat->id;
        $this->name = $cat->name;
        $this->slug = $cat->slug;
        $this->isEdit = true;

        $this->js("$('#categoryModal').modal('show');");
    }

    public function update()
    {
        $cat = ModelCategory::findOrFail($this->category_id);

        $data = $this->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $cat->id,
        ]);

        $data['slug'] = $data['slug'] ? Str::slug($data['slug']) : Str::slug($this->name);

        // jaga unik selain id ini
        $base = $data['slug'];
        $i = 1;
        while (ModelCategory::where('slug', $data['slug'])->where('id', '!=', $cat->id)->exists()) {
            $data['slug'] = $base . '-' . $i++;
        }

        $cat->update($data);

        $this->resetInputFields();
        $this->toast('success', 'Data berhasil diupdate');
        $this->js("$('#categoryModal').modal('hide');");
    }

    public function delete_confirmation($id)
    {
        $this->category_id = $id;
        $this->js(<<<'JS'
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data ini akan dihapus permanen.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, hapus!"
        }).then((result) => {
            if (result.isConfirmed) {
                $wire.delete()
            }
        });
        JS);
    }

    public function delete()
    {
        if ($this->category_id) {
            ModelCategory::destroy($this->category_id);
            $this->toast('error', 'Data berhasil dihapus');
        }
    }

    private function toast($icon, $title)
    {
        $this->js(<<<JS
        const Toast = Swal.mixin({
          toast:true, position:"top-end", showConfirmButton:false, timer:1800, timerProgressBar:true,
          didOpen:(t)=>{ t.onmouseenter=Swal.stopTimer; t.onmouseleave=Swal.resumeTimer; }
        });
        Toast.fire({icon:"$icon", title:"$title"});
        JS);
    }
}
