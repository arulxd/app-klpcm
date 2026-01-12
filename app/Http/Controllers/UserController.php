<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    // 1. LIST DATA
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('search') && $request->search != null) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        $users = $query->latest()->paginate(10)->appends(['search' => $request->search]);

        return view('users.index', compact('users'));
    }

    // 2. FORM TAMBAH
    public function create()
    {
        return view('users.create');
    }

    // 3. SIMPAN DATA (Create)
  public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string'], // Pastikan baris ini ada
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role, // Pastikan ini juga ada
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
    }

    // 4. FORM EDIT
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    // 5. UPDATE DATA (Edit)
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role' => ['required', 'string'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()], // Password boleh kosong
        ]);

        // Siapkan data yang mau diupdate
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        // LOGIKA PENTING: Hanya update password jika kolom diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Data User berhasil diperbarui!');
    }

    // 6. HAPUS
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Mencegah hapus diri sendiri agar tidak terkunci
        if (auth()->id() == $user->id) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
    }
}