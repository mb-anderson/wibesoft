<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles|max:255',
        ]);

        $role = Role::create([
            'name' => $request->name,
        ]);

        return response()->json(['message' => 'Role created successfully', 'role' => $role], 201);
    }
}