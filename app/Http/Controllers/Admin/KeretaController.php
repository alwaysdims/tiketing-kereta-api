<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kereta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KeretaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $keretas = Kereta::paginate(1000);
        return view('admin_kai.kereta', compact('keretas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin_kai.kereta.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_kereta' => 'required|unique:kereta,kode_kereta',
            'nama_kereta' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('showAddModal', true);
        }

        Kereta::create([
            'kode_kereta' => $request->kode_kereta,
            'nama_kereta' => $request->nama_kereta,
        ]);

        return redirect()->route('admin.kereta.index')
            ->with('success', 'Kereta berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $kereta = Kereta::findOrFail($id);
        return response()->json($kereta);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kereta = Kereta::findOrFail($id);
        return view('admin_kai.kereta.edit', compact('kereta'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $kereta = Kereta::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'kode_kereta' => 'required|unique:kereta,kode_kereta,' . $id,
            'nama_kereta' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('showEditModal', true)
                ->with('editId', $id);
        }

        $kereta->update([
            'kode_kereta' => $request->kode_kereta,
            'nama_kereta' => $request->nama_kereta,
        ]);

        return redirect()->route('admin.kereta.index')
            ->with('success', 'Kereta berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kereta = Kereta::findOrFail($id);
        $kereta->delete();

        return redirect()->route('admin.kereta.index')
            ->with('success', 'Kereta berhasil dihapus');
    }
}
