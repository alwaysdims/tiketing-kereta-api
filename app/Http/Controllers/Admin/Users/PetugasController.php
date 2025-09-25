<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Petugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PetugasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $petugas = User::where('role', 'petugas')->with('petugas')->paginate(10);
        return view('admin_kai.users.petugas', compact('petugas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:50|unique:users',
            'email' => 'required|email|max:100|unique:users',
            'nik' => 'required|string|max:20|unique:users',
            'password' => 'required|confirmed|min:6',
            'nama_petugas' => 'required|string|max:100',
            'nip' => 'required|string|max:50|unique:petugas',
            'jabatan' => 'required|in:Loket,Boarding,Kondektur,Masinis',
            'shift' => 'nullable|in:pagi,siang,malam',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'jenis_kelamin' => 'nullable|in:L,P',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'nik' => $request->nik,
            'password' => Hash::make($request->password),
            'jenis_kelamin' => $request->jenis_kelamin,
            'role' => 'petugas',
        ]);

        Petugas::create([
            'id_user' => $user->id,
            'nama_petugas' => $request->nama_petugas,
            'nip' => $request->nip,
            'jabatan' => $request->jabatan,
            'shift' => $request->shift,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('admin.users.petugas.index')->with('success', 'Petugas berhasil ditambahkan');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $petugas = Petugas::where('id_user', $id)->firstOrFail();

        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:50|unique:users,username,' . $id,
            'email' => 'required|email|max:100|unique:users,email,' . $id,
            'nik' => 'required|string|max:20|unique:users,nik,' . $id,
            'password' => 'nullable|confirmed|min:6',
            'nama_petugas' => 'required|string|max:100',
            'nip' => 'required|string|max:50|unique:petugas,nip,' . $petugas->id,
            'jabatan' => 'required|in:Loket,Boarding,Kondektur,Masinis',
            'shift' => 'nullable|in:pagi,siang,malam',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'jenis_kelamin' => 'nullable|in:L,P',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user->update([
            'username' => $request->username,
            'email' => $request->email,
            'nik' => $request->nik,
            'jenis_kelamin' => $request->jenis_kelamin,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        $petugas->update([
            'nama_petugas' => $request->nama_petugas,
            'nip' => $request->nip,
            'jabatan' => $request->jabatan,
            'shift' => $request->shift,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('admin.users.petugas.index')->with('success', 'Petugas berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.users.petugas.index')->with('success', 'Petugas berhasil dihapus');
    }
}
