<?php
namespace App\Services;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class UserService
{

    /**
     * @var User
     * @param $user
     */
    protected $user;

    public function __construct()
    {
        $this->user = new User();
    }

    public function store($data)
    {
        $data['password'] = Hash::make($data['password']);
        $user = $this->user->create($data);
        if (!empty($data['roles'])) {
            $user->syncRoles($data['roles']);
        }
        return $user;
    }

    public function update($id, $data)
    {

        $data = Arr::whereNotNull($data);
        if (Arr::has($data, 'password')) {
            $data['password'] = Hash::make($data['password']);
        }
        $user = $this->user->find($id);
        $user->update($data);
        if (!$this->checkHasExactRoles($user, $data['roles'])) {
            $this->userService->syncRoles($user, $data['roles']);
        }
        return $user;
    }

    public function destroy($request)
    {
        if ($request->action_type == 'multi-delete') {
            $this->user->whereIn('id', $request->ids)->delete();
        } else {
            $this->user->find($request->user)->delete();
        }
    }

    public function show($user)
    {
        return $this->user->with('roles.permissions')->find($user);
    }

    protected function checkHasExactRoles($user, $roles = [])
    {
        $roles = Role::where('id', $roles)->get();
        return $user->hasExactRoles($roles);
    }
}
