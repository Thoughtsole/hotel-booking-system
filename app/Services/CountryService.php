<?php
namespace App\Services;

use App\Models\Country;
use Illuminate\Http\Request;

class CountryService
{

    public function index(Request $request)
    {
        $model = new Country();
        $props = [
            'page' => $request->get('page', 0),
            'pageSize' => $request->get('pageSize', 10),
            'cols' => $request->get('cols', []),
            'filter' => $request->get('filter', ''),
            'orderField' => $request->get('orderField', 'id'),
            'orderBy' => $request->get('orderBy', 'desc'),
            'displayedColumns' => $model->getDisplayedColumns(),
            'title' => 'Country'
        ];
        $model = $model->selectCols($props['cols'])->applyFilter($props['filter'])->sortOrder($props['orderField'], $props['orderBy']);
        $model = $model->getBuilder();
        $props['dataSource'] = $model->paginate($props['pageSize'], ['*'], 'page', $props['page']);
        return $props;
    }

    public function store($data)
    {
        return Country::create($data);
    }

    public function update($model, $data){
        return $model->update($data);
    }

}
