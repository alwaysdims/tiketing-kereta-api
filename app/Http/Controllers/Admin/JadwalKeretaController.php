<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalKereta;
use App\Models\Kereta;
use App\Models\Stasiun;
use Illuminate\Http\Request;

class JadwalKeretaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jadwals = JadwalKereta::all();
        $keretas = Kereta::all();
        $stasiuns = Stasiun::all();

        return view('admin_kai.jadwal', compact('jadwals', 'stasiuns', 'keretas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_kereta' => 'required|exists:kereta,id',
            'id_stasiun_asal' => 'required|exists:stasiun,id',
            'id_stasiun_tujuan' => 'required|exists:stasiun,id|different:id_stasiun_asal',
            'jam_keberangkatan' => 'required|date_format:Y-m-d\TH:i',
            'jam_kedatangan' => 'required|date_format:Y-m-d\TH:i|after:jam_keberangkatan',
            'harga' => 'required|numeric|min:0',
        ]);

        // Convert datetime-local format to MySQL datetime format
        $validated['jam_keberangkatan'] = \Carbon\Carbon::parse($validated['jam_keberangkatan'])->format('Y-m-d H:i:s');
        $validated['jam_kedatangan'] = \Carbon\Carbon::parse($validated['jam_kedatangan'])->format('Y-m-d H:i:s');

        JadwalKereta::create($validated);

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal kereta berhasil ditambahkan');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $jadwal = JadwalKereta::findOrFail($id);

        $validated = $request->validate([
            'id_kereta' => 'required|exists:kereta,id',
            'id_stasiun_asal' => 'required|exists:stasiun,id',
            'id_stasiun_tujuan' => 'required|exists:stasiun,id|different:id_stasiun_asal',
            'jam_keberangkatan' => 'required|date_format:Y-m-d\TH:i',
            'jam_kedatangan' => 'required|date_format:Y-m-d\TH:i|after:jam_keberangkatan',
            'harga' => 'required|numeric|min:0',
        ]);

        // Convert datetime-local format to MySQL datetime format
        $validated['jam_keberangkatan'] = \Carbon\Carbon::parse($validated['jam_keberangkatan'])->format('Y-m-d H:i:s');
        $validated['jam_kedatangan'] = \Carbon\Carbon::parse($validated['jam_kedatangan'])->format('Y-m-d H:i:s');

        $jadwal->update($validated);

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal kereta berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $jadwal = JadwalKereta::findOrFail($id);
        $jadwal->delete();

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal kereta berhasil dihapus');
    }
}
