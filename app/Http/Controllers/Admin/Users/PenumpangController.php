<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Penumpang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PenumpangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $penumpangs = User::where('role', 'penumpang')
            ->with('penumpang')
            ->paginate(10); // Adjust pagination as needed

        return view('admin_kai.users.penumpang', compact('penumpangs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin_kai.users.penumpang_create'); // Optional separate view, or integrate in index if using modals
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:50', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users'],
            'nik' => ['required', 'string', 'max:20', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'jenis_kelamin' => ['nullable', Rule::in(['L', 'P'])],
            'nama_penumpang' => ['nullable', 'string', 'max:100'],
            'tanggal_lahir' => ['nullable', 'date'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
        ]);

        $user = User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'nik' => $validated['nik'],
            'password' => Hash::make($validated['password']),
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'role' => 'penumpang',
        ]);

        Penumpang::create([
            'id_user' => $user->id,
            'nama_penumpang' => $validated['nama_penumpang'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'no_hp' => $validated['no_hp'],
            'alamat' => $validated['alamat'],
        ]);

        return redirect()->route('admin.users.penumpang.index')->with('success', 'Penumpang created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::with('penumpang')->findOrFail($id);
        return view('admin_kai.users.penumpang', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::with('penumpang')->findOrFail($id);
        return view('admin_kai.users.penumpang_edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'username' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:100', Rule::unique('users')->ignore($user->id)],
            'nik' => ['required', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'jenis_kelamin' => 'required', 
            'nama_penumpang' => ['nullable', 'string', 'max:100'],
            'tanggal_lahir' => ['nullable', 'date'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
        ]);

        $user->update([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'nik' => $validated['nik'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'role' => 'penumpang',
        ]);

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
            $user->save();
        }

        $user->penumpang->update([
            'nama_penumpang' => $validated['nama_penumpang'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'no_hp' => $validated['no_hp'],
            'alamat' => $validated['alamat'],
        ]);

        return redirect()->route('admin.users.penumpang.index')->with('success', 'Penumpang updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete(); // Will cascade to penumpang due to onDelete('cascade')

        return redirect()->route('admin.users.penumpang.index')->with('success', 'Penumpang deleted successfully.');
    }
}
