<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gerbong;
use App\Models\Kereta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GerbongController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $gerbongs = Gerbong::with('kereta')->paginate(1000); // 10 data per halaman
        $keretas = Kereta::all();
        return view('admin_kai.gerbong', compact('gerbongs','keretas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $keretas = Kereta::all();
        return view('admin_kai.gerbong_create', compact('keretas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_kereta' => 'required|exists:kereta,id',
            'kode_gerbong' => 'required|string|max:10',
            'no_gerbong' => 'required|integer|min:1',
            'jumlah_kursi' => 'required|integer|min:1',
            'kelas_gerbong' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('showAddModal', true);
        }

        Gerbong::create($request->all());

        return redirect()->route('admin.gerbong.index')
            ->with('success', 'Gerbong berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $gerbong = Gerbong::with('kereta')->findOrFail($id);
        return response()->json($gerbong);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $gerbong = Gerbong::findOrFail($id);
        $keretas = Kereta::all();
        return view('admin_kai.gerbong_edit', compact('gerbong', 'keretas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $gerbong = Gerbong::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'id_kereta' => 'required|exists:kereta,id',
            'kode_gerbong' => 'required|string|max:10' . $id,
            'no_gerbong' => 'required|integer|min:1',
            'jumlah_kursi' => 'required|integer|min:1',
            'kelas_gerbong' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('showEditModal', true)
                ->with('editGerbong', $gerbong);
        }

        $gerbong->update($request->all());

        return redirect()->route('admin.gerbong.index')
            ->with('success', 'Gerbong berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $gerbong = Gerbong::findOrFail($id);
        $gerbong->delete();

        return redirect()->route('admin.gerbong.index')
            ->with('success', 'Gerbong berhasil dihapus');
    }
}
