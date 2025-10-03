<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\AdminKai;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        $admins = AdminKai::with('user')->paginate(1000);
        return view('admin_kai.users.admin', compact('admins'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_admin' => 'required|string|max:100',
            'nip' => 'required|string|max:50|unique:admin_kai,nip',
            'jabatan' => 'required|string|max:100',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'shift' => 'nullable|in:pagi,siang,malam',
            'username' => 'required|string|max:50|unique:users,username',
            'email' => 'required|email|max:100|unique:users,email',
            'nik' => 'required|string|max:20|unique:users,nik',
            'password' => 'required|string|min:8|confirmed',
            'jenis_kelamin' => 'nullable',
        ]);

        $user = User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'nik' => $validated['nik'],
            'password' => Hash::make($validated['password']),
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'role' => 'admin',
        ]);

        AdminKai::create([
            'id_user' => $user->id,
            'nama_admin' => $validated['nama_admin'],
            'nip' => $validated['nip'],
            'jabatan' => $validated['jabatan'],
            'no_hp' => $validated['no_hp'],
            'alamat' => $validated['alamat'],
            'shift' => $validated['shift'],
        ]);

        return redirect()->route('admin.users.admin.index')->with('success', 'Admin berhasil ditambahkan.');
    }

    public function show($id)
    {
        $admin = AdminKai::with('user')->findOrFail($id);
        return response()->json($admin);
    }

    public function edit($id)
    {
        $admin = AdminKai::with('user')->findOrFail($id);
        return view('admin_kai.users.admin', compact('admin'));
    }

    public function update(Request $request, $id)
    {
        $admin = AdminKai::findOrFail($id);
        $user = $admin->user;

        $validated = $request->validate([
            'nama_admin' => 'required|string|max:100',
            'nip' => 'required|string|max:50|unique:admin_kai,nip,' . $admin->id,
            'jabatan' => 'required|string|max:100',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'shift' => 'nullable|in:pagi,siang,malam',
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
            'email' => 'required|email|max:100|unique:users,email,' . $user->id,
            'nik' => 'required|string|max:20|unique:users,nik,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'jenis_kelamin' => 'nullable|in:L,P',
        ]);

        $user->update([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'nik' => $validated['nik'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'role' => 'admin',
            ...(isset($validated['password']) ? ['password' => Hash::make($validated['password'])] : []),
        ]);

        $admin->update([
            'nama_admin' => $validated['nama_admin'],
            'nip' => $validated['nip'],
            'jabatan' => $validated['jabatan'],
            'no_hp' => $validated['no_hp'],
            'alamat' => $validated['alamat'],
            'shift' => $validated['shift'],
        ]);

        return redirect()->route('admin.users.admin.index')->with('success', 'Admin berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $admin = AdminKai::findOrFail($id);
        $user = $admin->user;

        $admin->delete();
        $user->delete();

        return redirect()->route('admin.users.admin.index')->with('success', 'Admin berhasil dihapus.');
    }
}
