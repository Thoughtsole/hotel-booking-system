<?php
namespace App\Http\Controllers;

use App\Models\User;

class UserService {

    public function __construct() {
        $this->user = new User();
    }

    public function syncRoles($user, $roles) {
        return $user->syncRoles($roles);
    }
}