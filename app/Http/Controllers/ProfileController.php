<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'profile_photo' => 'nullable|mimes:jpeg,png,jpg,svg'
        ]);

        $user = Auth::user();

        // Siapkan data awal
        $data = [
            'name' => $request->name,
            'email' => $request->email
        ];

        /*
        |--------------------------------------------------------------------------
        | UPLOAD FOTO PROFIL
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('profile_photo')) {

            // Hapus foto lama jika ada
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            // Upload foto baru
            $file = $request->file('profile_photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('profile_photos', $filename, 'public');

            $data['profile_photo'] = $path;
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE USER
        |--------------------------------------------------------------------------
        */
        $user->update($data);

        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Show the form for changing password.
     */
    public function editPassword()
    {
        return view('profile.change-password');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!password_verify($request->old_password, $user->password)) {
            return back()->withErrors([
                'old_password' => 'Password lama tidak sesuai.'
            ]);
        }

        $user->update([
            'password' => bcrypt($request->new_password)
        ]);

        return back()->with('success', 'Password berhasil diperbarui!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
