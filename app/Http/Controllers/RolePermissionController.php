<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends Controller
{
    // عرض جميع الرولز مع صلاحياتهم
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        return view('Pages.Roles.index', compact('roles', 'permissions'));
    }

    // إضافة صلاحيات لرول
    public function update(Request $request, Role $role)
    {
        $role->syncPermissions($request->permissions);

        return redirect()->back()->with('success', 'تم تحديث صلاحيات الدور بنجاح.');
    }
    // عرض فورم إضافة دور
    public function create()
    {
        $roles = Role::all();

        $permissions = Permission::all();
        return view('Pages.Roles..create', compact('permissions','roles'));
    }

// حفظ الدور الجديد
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'array',
        ]);

        $role = Role::create(['name' => $request->name]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('roles.index')->with('success', 'تم إنشاء الدور بنجاح.');
    }

}
