<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stasiun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StasiunController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stasiuns = Stasiun::paginate(1000);
        return view('admin_kai.stasiun', compact('stasiuns'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin_kai.stasiun.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_stasiun' => 'required|unique:stasiun,kode_stasiun|max:10',
            'nama_stasiun' => 'required|max:255',
            'kota' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.stasiun.index')
                ->withErrors($validator)
                ->with('showAddModal', true)
                ->withInput();
        }

        Stasiun::create($request->only(['kode_stasiun', 'nama_stasiun', 'kota']));

        return redirect()->route('admin.stasiun.index')
            ->with('success', 'Stasiun berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $stasiun = Stasiun::findOrFail($id);
        return response()->json($stasiun);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $stasiun = Stasiun::findOrFail($id);
        return view('admin_kai.stasiun.edit', compact('stasiun'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $stasiun = Stasiun::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'kode_stasiun' => 'required|max:10|unique:stasiun,kode_stasiun,' . $id,
            'nama_stasiun' => 'required|max:255',
            'kota' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.stasiun.index')
                ->withErrors($validator)
                ->with('showEditModal', true)
                ->with('editStasiun', $stasiun)
                ->withInput();
        }

        $stasiun->update($request->only(['kode_stasiun', 'nama_stasiun', 'kota']));

        return redirect()->route('admin.stasiun.index')
            ->with('success', 'Stasiun berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $stasiun = Stasiun::findOrFail($id);
        $stasiun->delete();

        return redirect()->route('admin.stasiun.index')
            ->with('success', 'Stasiun berhasil dihapus');
    }
}
