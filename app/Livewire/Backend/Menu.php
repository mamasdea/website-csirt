<?php

namespace App\Livewire\Backend;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Models\Menu as ModelMenu;
use Illuminate\Support\Facades\Route;
use App\Models\Page as ModelPage; // ⬅️ tambah ini

#[Title('Menu Manage')]
#[Layout('components.layouts-backend.app')]
class Menu extends Component
{
    public string $search = '';

    // DB fields
    public ?int $menu_id = null;
    public string $name = '';
    public ?string $link = null;
    public bool $is_parent = false;
    public ?int $parent_id = null;
    public int $order = 0;
    public int $type = 0;

    public bool $isEdit = false;

    public array $typeOptions = [
        0 => 'Default',
        1 => 'Route',
        2 => 'Page Slug',
    ];

    // ⬇️ properti sementara (TIDAK disimpan ke DB)
    public ?string $route_name = null;
    public ?string $page_slug  = null;

    public array $rows = [];
    public $parentOptions;
    public $pageOptions = []; // slug => title
    public array $routeOptions = []; // name => name (uri)

    public function render()
    {
        $all = ModelMenu::query()
            ->when($this->search, function ($q) {
                $q->where(function ($qq) {
                    $qq->where('name', 'like', "%{$this->search}%")
                        ->orWhere('link', 'like', "%{$this->search}%");
                });
            })
            ->orderBy('parent_id')->orderBy('order')
            ->get();

        $roots = $all->filter(fn($m) => is_null($m->parent_id) || (int)$m->parent_id === 0)
            ->sortBy('order');

        $this->rows = [];
        foreach ($roots as $node) {
            $this->flatten($node, 0, $all);
        }

        $this->parentOptions = ModelMenu::orderBy('parent_id')->orderBy('order')->get(['id', 'name', 'parent_id']);

        // ⬇️ ambil daftar page: slug => title (untuk type=2)
        $this->pageOptions = ModelPage::orderBy('title')->pluck('title', 'slug')->toArray();

        // ⬇️ ambil daftar route name (untuk type=1)
        $allRoutes = Route::getRoutes();
        $namedRoutes = [];
        foreach ($allRoutes as $route) {
            if ($route->getName() && in_array('GET', $route->methods())) {
                // Abaikan route-route internal/backend jika memungkinkan
                if (str_starts_with($route->getName(), 'livewire.') || str_starts_with($route->getName(), 'ignition.')) {
                    continue;
                }
                $namedRoutes[$route->getName()] = $route->getName() . ' (' . $route->uri() . ')';
            }
        }
        $this->routeOptions = $namedRoutes;

        return view('livewire.backend.menu');
    }

    private function flatten(ModelMenu $node, int $depth, $all): void
    {
        $this->rows[] = [
            'id'         => $node->id,
            'name'       => $node->name,
            'link'       => $node->link,
            'is_parent'  => (bool)$node->is_parent,
            'parent_id'  => $node->parent_id,
            'order'      => (int)$node->order,
            'type'       => (int)$node->type,
            'depth'      => $depth,
            'updated_at' => $node->updated_at,
        ];

        foreach ($all->where('parent_id', $node->id)->sortBy('order') as $child) {
            $this->flatten($child, $depth + 1, $all);
        }
    }

    public function resetInputFields()
    {
        $this->menu_id   = null;
        $this->name      = '';
        $this->link      = null;
        $this->is_parent = false;
        $this->parent_id = null;
        $this->order     = 0;
        $this->type      = 0;
        $this->route_name = null;
        $this->page_slug  = null;
        $this->isEdit    = false;
    }

    public function store()
    {
        $data = $this->validate([
            'name'       => ['required', 'string', 'max:255'],
            'link'       => ['nullable', 'string', 'max:255'],
            'is_parent'  => ['boolean'],
            'parent_id'  => ['nullable', 'integer', 'exists:menus,id'],
            'order'      => ['nullable', 'integer', 'min:0'],
            'type'       => ['required', 'integer', 'between:0,255'],
            // validasi tambahan utk input sementara
            'route_name' => ['nullable', 'string', 'max:190'],
            'page_slug'  => ['nullable', 'string', 'max:255'],
        ]);

        // ⬇️ tentukan link sesuai type
        $data['link'] = $this->computeLinkByType($data);

        // default order
        if (empty($data['order'])) {
            $max = ModelMenu::where('parent_id', $data['parent_id'])->max('order');
            $data['order'] = is_null($max) ? 1 : $max + 1;
        }

        // simpan ke DB (hapus kunci non-DB)
        unset($data['route_name'], $data['page_slug']);
        ModelMenu::create($data);

        $this->resetInputFields();
        $this->js("$('#menuModal').modal('hide')");
        $this->toast('success', 'Menu disimpan');
    }

    public function edit(int $id)
    {
        $m = ModelMenu::findOrFail($id);
        $this->menu_id   = $m->id;
        $this->name      = $m->name;
        $this->link      = $m->link;
        $this->is_parent = (bool)$m->is_parent;
        $this->parent_id = $m->parent_id;
        $this->order     = (int)$m->order;
        $this->type      = (int)$m->type;

        // isi hint otomatis saat edit (opsional)
        $this->route_name = null;
        $this->page_slug  = null;
        if ($this->type === 1) {
            $this->route_name = $m->link;
        } elseif ($this->type === 2) {
            $this->page_slug = $m->link;
        }

        $this->isEdit = true;
        $this->js("$('#menuModal').modal('show')");
    }

    public function update()
    {
        $m = ModelMenu::findOrFail($this->menu_id);
        $data = $this->validate([
            'name'       => ['required', 'string', 'max:255'],
            'link'       => ['nullable', 'string', 'max:255'],
            'is_parent'  => ['boolean'],
            'parent_id'  => ['nullable', 'integer', 'exists:menus,id'],
            'order'      => ['nullable', 'integer', 'min:0'],
            'type'       => ['required', 'integer', 'between:0,255'],
            'route_name' => ['nullable', 'string', 'max:190'],
            'page_slug'  => ['nullable', 'string', 'max:255'],
        ]);

        $data['link'] = $this->computeLinkByType($data);

        unset($data['route_name'], $data['page_slug']);
        $m->update($data);

        $this->resetInputFields();
        $this->toast('success', 'Menu diperbarui');
        $this->js("$('#menuModal').modal('hide')");
    }

    private function computeLinkByType(array $data): ?string
    {
        // type 1: store route name directly
        if ((int)$data['type'] === 1 && !empty($this->route_name)) {
            return $this->route_name;
        }

        // type 2: store page slug directly
        if ((int)$data['type'] === 2 && !empty($this->page_slug)) {
            return $this->page_slug;
        }

        // selain itu: pakai apa adanya di field link (manual)
        return $data['link'] ?? null;
    }

    public function delete_confirmation(int $id)
    {
        $this->menu_id = $id;
        $this->js(<<<'JS'
        Swal.fire({
            title: 'Hapus menu?',
            text: "Submenu (parent_id) mungkin terpengaruh.",
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
        $m = ModelMenu::find($this->menu_id);
        if ($m) {
            ModelMenu::where('parent_id', $m->id)->update(['parent_id' => $m->parent_id]);
            $m->delete();
        }
        $this->toast('error', 'Menu dihapus');
    }

    public function moveUp(int $id)
    {
        $m = ModelMenu::findOrFail($id);
        $prev = ModelMenu::where('parent_id', $m->parent_id)
            ->where('order', '<', $m->order)
            ->orderByDesc('order')
            ->first();
        if ($prev) {
            [$m->order, $prev->order] = [$prev->order, $m->order];
            $m->save();
            $prev->save();
        }
    }

    public function moveDown(int $id)
    {
        $m = ModelMenu::findOrFail($id);
        $next = ModelMenu::where('parent_id', $m->parent_id)
            ->where('order', '>', $m->order)
            ->orderBy('order')
            ->first();
        if ($next) {
            [$m->order, $next->order] = [$next->order, $m->order];
            $m->save();
            $next->save();
        }
    }

    private function toast($icon, $title)
    {
        $this->js(<<<JS
        const Toast = Swal.mixin({ toast:true, position:'top-end', showConfirmButton:false, timer:1800, timerProgressBar:true,
            didOpen:(t)=>{ t.onmouseenter=Swal.stopTimer; t.onmouseleave=Swal.resumeTimer; }});
        Toast.fire({icon:'$icon', title:'$title'});
        JS);
    }
}
