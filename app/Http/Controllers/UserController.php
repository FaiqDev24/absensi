<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('admin.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $roles = ['admin', 'teacher', 'student'];
        $user = User::findOrFail($id);
        return view('admin.user.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email:dns|unique:users,email,' . $id,
            'password' => 'nullable|min:8',
            'role' => 'required|in:admin,teacher,student',
        ], [
            'name.required' => 'Nama harus diisi.',
            'name.min' => 'Nama harus terdiri dari minimal 3 karakter.',
            'password.min' => 'Password harus terdiri dari minimal 8 karakter.',
            'role.in' => 'Role harus salah satu dari: admin, teacher, student.',
        ]);

        $user = User::where('id', $id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'Berhasil memperbarui data pengguna!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus!');
    }

    public function trash()
    {
        $users = User::onlyTrashed()->get();
        return view('admin.user.trash', compact('users'));
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->route('admin.users.trash')->with('success', 'Data pengguna berhasil dipulihkan!');
    }

    public function forceDelete($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->forceDelete();

        return redirect()->route('admin.users.trash')->with('success', 'Data pengguna berhasil dihapus permanen!');
    }
}
