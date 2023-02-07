<?php

namespace App\Http\Controllers;

use App\Http\Requests\Menu\EditMenuRequest;
use App\Http\Requests\Menu\StoreMenuRequest;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Menu::class);
        $model = new Menu();
        $props = [
            'page' => $request->get('page', 0),
            'pageSize' => $request->get('pageSize', 10),
            'cols' => $request->get('cols', []),
            'filter' => $request->get('filter', ''),
            'orderField' => $request->get('orderField', 'id'),
            'orderBy' => $request->get('orderBy', 'desc'),
            'displayedColumns' => $model->getDisplayedColumns(),
            'title' => 'Menu'
        ];
        $model = $model->selectCols($props['cols'])->applyFilter($props['filter'])->sortOrder($props['orderField'], $props['orderBy']);
        $model = $model->getBuilder();
        $props['dataSource'] = $model->paginate($props['pageSize'], ['*'], 'page', $props['page']);
        return Inertia::render('Settings/Menu/Read', $props);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Menu::class);
        $props = [
            'title' => 'Create Menu',
            'parent_menu' => getSelectInputMenu(),
            'token' => csrf_token(),
        ];
        return Inertia::render('Settings/Menu/Create', $props);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\Menu\StoreMenuRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMenuRequest $request)
    {
        $this->authorize('create', Menu::class);
        Menu::create($request->validated());
        return Redirect::back();
    }

    /**
     * Display the specified resource.
     *
     * @param  Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function show(Menu $menu)
    {
        $this->authorize('view', Menu::class);
        $props = [
            'title' => 'Menu Details: #' . $menu->id,
            'menu' => $menu,
        ];
        return Inertia::render('Settings/Menu/Show', $props);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function edit(Menu $menu)
    {
        $this->authorize('update', Menu::class);
        $props = [
            'title' => "Update Menu : #" . $menu->id,
            'menu' => $menu,
            'parent_menu' => getSelectInputMenu(),
            'token' => csrf_token(),
        ];
        return Inertia::render('Settings/Menu/Edit', $props);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Menu\EditMenuRequest  $request
     * @param  Object  $menu
     * @return \Illuminate\Http\Response
     */
    public function update(EditMenuRequest $request, Menu $menu)
    {
        $this->authorize('update', Menu::class);
        $menu->update($request->validated());
        return Redirect::back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $this->authorize('delete', Menu::class);
        $menu = new Menu();
        if ($request->action_type == 'multi-delete') {
            $menu->whereIn('id', $request->ids)->delete();
        }
        if ($request->menu) {
            $menu->find($request->menu)->delete();
        }
        return Redirect::back();
    }
}
