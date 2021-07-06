<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Role;

class RoleController extends ApiController
{
    public function index()
    {
        return Role::all();
    }
}
