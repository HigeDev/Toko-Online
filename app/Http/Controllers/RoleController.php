<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RoleController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('permission:view roles', only: ['index']),
            new Middleware('permission:edit roles', only: ['edit']),
            new Middleware('permission:create roles', only: ['create']),
            new Middleware('permission:delete roles', only: ['destroy']),
        ];
    }
    public function index()
    {
        $roles = Role::orderBy('name', 'ASC')->paginate(10);
        return view('login.roles.list', [
            'roles' => $roles
        ]);
    }
    public function create()
    {
        $permissions = Permission::orderBy('name', 'ASC')->get();
        return view('login.roles.create', ['permissions' => $permissions]);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles|min:3'
        ]);
        if ($validator->passes()) {
            $role = Role::create(['name' => $request->name]);
            if (!empty($request->permission)) {
                foreach ($request->permission as $name) {
                    $role->givePermissionTo($name);
                }
            }
            return redirect()->route('roles.index')->with('success', 'Roles added successfully!');
        } else {
            return redirect()->route('roles.create')->withInput()->withErrors($validator);
        }
    }
    public function edit($id)
    {
        $roles = Role::findOrFail($id);
        $hasPermissions = $roles->permissions->pluck('name');
        $permissions = Permission::orderBy('name', 'ASC')->get();
        return view('login.roles.edit', [
            'permissions' => $permissions,
            'hasPermissions' => $hasPermissions,
            'roles' => $roles
        ]);
    }
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name, ' . $id . ',id'
        ]);
        if ($validator->passes()) {
            $role->name = $request->name;
            $role->save();
            if (!empty($request->permission)) {
                $role->syncPermissions($request->permission);
            } else {
                $role->syncPermissions($request->permission);
            }
            return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
        } else {
            return redirect()->route('roles.create', $id)->withInput()->withErrors($validator);
        }
        return redirect()->route('roles.create', $id)->withInput()->withErrors($validator);
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        $role = Role::find($id);
        if ($role == null) {
            session()->flash('error', 'Role not found!');
            return response()->json([
                'status' => false
            ]);
        }
        $role->delete();
        session()->flash('success', 'Role deleted successfully!');
        return response()->json([
            'status' => true
        ]);
    }
}
