<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use Inertia\Inertia;
use App\Http\Requests\Role\StoreRoleRequest;
use Illuminate\Support\Facades\Redirect;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $model = new Role();
        $props = [
            'page' => $request->get('page', 0),
            'pageSize' => $request->get('pageSize', 10),
            'cols' => $request->get('cols', []),
            'filter' => $request->get('filter', ''),
            'orderField' => $request->get('orderField', 'id'),
            'orderBy' => $request->get('orderBy', 'desc'),
            'displayedColumns' => $model->getDisplayedColumns(),
            'title' => 'Role'
        ];
        $model = $model->selectCols($props['cols'])->applyFilter($props['filter'])->sortOrder($props['orderField'], $props['orderBy']);
        $model = $model->getBuilder();
        $props['dataSource'] = $model->paginate($props['pageSize'], ['*'], 'page', $props['page']);
        return Inertia::render('Role/Read', $props);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $props = [
            'title' => 'Create Role',
        ];
        return Inertia::render('Role/Create', $props);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\Role\StoreRoleRequest;  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRoleRequest $request)
    {
        $validated = $request->validated();
        Role::create($validated);
        return Redirect::back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $props = [
            'title' => "Update Role : #". $role->id,
            'role' => $role,
            'token' => csrf_token()
        ];
        return Inertia::render('Role/Edit', $props);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreRoleRequest $request, Role $role)
    {
        $validated = $request->validated();
        $role->update($validated);
        return Redirect::back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $role = new Role();
        if($request->action_type == 'multi-delete'){
            $role->whereIn('id', $request->ids)->delete();
        }
        if($request->role){
            $role->find($request->role)->delete();
        }
        return Redirect::back();
    }
}
