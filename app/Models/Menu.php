<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['name', 'link', 'is_parent', 'parent_id', 'order', 'type'];

    protected $casts = [
        'is_parent' => 'boolean',
        'parent_id' => 'integer',
        'order'     => 'integer',
        'type'      => 'integer',
    ];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('order');
    }
}
