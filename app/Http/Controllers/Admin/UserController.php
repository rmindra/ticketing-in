<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\Role;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.users.index', [
            'users' => User::with('role', 'department')->paginate(10)
        ]);
    }

    public function create()
    {
        return view('admin.users.create', [
            'roles' => Role::all(),
            'departments' => Department::all()
        ]);
    }

    public function store(UserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($request->password);

        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'User created');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', [
            'user' => $user,
            'roles' => Role::all(),
            'departments' => Department::all()
        ]);
    }

    public function update(UserRequest $request, User $user)
    {
        $data = $request->validated();

        if ($request->password)
            $data['password'] = Hash::make($request->password);

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User updated');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'User deleted');
    }

    /* Profile user biasa */

    public function profile()
    {
        return view('profile.index', [
            'user' => auth()->user()
        ]);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $user = auth()->user();
        $user->update($request->only('name'));

        return back()->with('success', 'Profile updated');
    }
}
