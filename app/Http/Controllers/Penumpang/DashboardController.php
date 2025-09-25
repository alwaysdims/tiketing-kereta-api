<?php

namespace App\Http\Controllers\Penumpang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JadwalKereta;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the homepage with today's train schedules.
     */
    public function index()
    {
        $today = Carbon::today()->toDateString();
        $jadwal = JadwalKereta::whereDate('jam_keberangkatan', $today)
            ->with(['kereta', 'stasiunAsal', 'stasiunTujuan'])
            ->get();

        return view('beranda', compact('jadwal'));
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
