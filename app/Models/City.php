<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class City extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    protected $relations = ['country'];
    protected $with = [];

    public function getDisplayedColumns()
    {
        return ([
            ['ref' => 'select', 'name' => 'Select'],
            ['ref' => 'id', 'name' => 'ID'],
            ['ref' => 'name', 'name' => 'Name'],
            ['ref' => 'country.name', 'name' => 'Country'],
            ['ref' => 'created_at', 'name' => 'Created At', 'data_type' => 'date'],
            ['ref' => 'action', 'name' => 'Action', 'actions' =>
                [
                    ['name' => 'edit', 'route_name' => 'city.edit', 'type' => 'link', 'icon' => 'M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z'],
                    ['name' => 'delete', 'route_name' => 'city.destroy', 'type' => 'link', 'icon' => 'M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z'],
                ],
            ],
        ]);
    }

    public function getAllColumns()
    {
        return ([
            'id' => 'int',
            'name' => 'text',
            'country_id' => 'int',
            'created_at' => 'date',
            'updated_at' => 'date',
        ]);
    }

    public function getDefaultColumns()
    {
        return [
            'id',
            'name',
            'country_id',
            'created_at',
        ];
    }

    public function searchableColumns()
    {
        return [
            'id',
            'name',
        ];
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function hotels()
    {
        return $this->belongsToMany(Hotel::class);
    }
}
