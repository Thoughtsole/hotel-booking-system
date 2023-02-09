<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission as SpatiePermission;
use App\Traits\BaseModelTrait;
use App\Models\Menu;

class Permission extends SpatiePermission
{
    use HasFactory, BaseModelTrait;

    protected $guarded = [];
    protected $perPage = 10;

    protected $relations = ['menu'];
    protected $with = ['menu'];

    public function getDisplayedColumns(){
        return ([
            ['ref' => 'select', 'name' => 'Select'],
            ['ref' => 'id', 'name' => 'ID'],
            ['ref' => 'name', 'name' => 'Name'],
            ['ref' => 'guard_name', 'name' => 'Guard Name'],
            ['ref' => 'menu.title', 'name' => 'Menu'],
            ['ref' => 'created_at', 'name' => 'Created At', 'data_type' => 'date'],
            ['ref' => 'action', 'name' => 'Action', 'actions'=> 
            [
                ['name' => 'edit', 'route_name' => 'permission.edit', 'type' => 'link', 'icon' => 'M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z'],
                ['name' => 'delete', 'route_name' => 'permission.destroy', 'type' => 'link', 'icon' => 'M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z'],
                ['name' => 'show', 'route_name' => 'permission.show', 'type' => 'link', 'icon' => 'M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z']
            ]
            ]
        ]);
    }

    public function getAllColumns()
    {
        return ([
            'id' => 'int',
            'name' => 'text',
            'guard_name' => 'text',
            'menu_id' => 'int',
            'created_at' => 'date',
            'updated_at' => 'date',
        ]);
    }

    public function getDefaultColumns()
    {
        return [
            'id',
            'name',
            'guard_name',
            'menu_id',
            'created_at',
            'updated_at',
        ];
    }

    public function searchableColumns(){
        return [
            'name',
            'guard_name'
        ];
    }

    public function menu(){
        return $this->belongsTo(Menu::class, 'menu_id', 'id');
    }
}
