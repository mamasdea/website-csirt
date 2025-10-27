<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Models\Menu as ModelMenu;

class MenuComposer
{
    public function compose(View $view)
    {
        $menus = ModelMenu::query()
            ->orderBy('parent_id')
            ->orderBy('order')
            ->get();

        $view->with('menus', $this->buildTree($menus));
    }

    private function buildTree($elements, $parentId = 0)
    {
        $branch = [];

        foreach ($elements as $element) {
            if ($element->parent_id == $parentId) {
                $children = $this->buildTree($elements, $element->id);
                if ($children) {
                    $element->children = $children;
                }
                $branch[] = $element;
            }
        }

        return $branch;
    }
}
