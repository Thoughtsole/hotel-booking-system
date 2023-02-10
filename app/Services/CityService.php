<?php
namespace App\Services;

use App\Models\City;
use Illuminate\Http\Request;

class CityService
{

    public function index(Request $request)
    {

        $model = new City();
        $props = [
            'page' => $request->get('page', 0),
            'pageSize' => $request->get('pageSize', 10),
            'cols' => $request->get('cols', []),
            'filter' => $request->get('filter', ''),
            'orderField' => $request->get('orderField', 'id'),
            'orderBy' => $request->get('orderBy', 'desc'),
            'with' => $request->get('with', ['country']),
            'displayedColumns' => $model->getDisplayedColumns(),
            'title' => 'City'
        ];
        $model = $model->selectCols($props['cols'])
                    ->applyFilter($props['filter'])
                    ->sortOrder($props['orderField'], $props['orderBy'])
                    ->handleWith($props['with'])
                    ->getBuilder();
        $props['dataSource'] = $model->paginate($props['pageSize'], ['*'], 'page', $props['page']);
        return $props;
    }


    public function store($data)
    {
        return City::create($data);
    }

    public function update($model, $data){
        return $model->update($data);
    }

}
