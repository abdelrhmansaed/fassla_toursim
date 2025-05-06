<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;



class UserController extends Controller
{
    // عرض صفحة إنشاء المستخدم
    public function create()
    {
        $roles = Role::all(); // جلب جميع الأدوار
        return view('Pages.Users.create', compact('roles'));
    }

    // حفظ المستخدم الجديد
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|exists:roles,name',
        ]);

        // إنشاء المستخدم
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),

        ]);

        // ربط المستخدم بالدور
        $user->assignRole($request->role);

        return redirect()->route('users.index')->with('success', 'تم إنشاء المستخدم بنجاح.');
    }
}
