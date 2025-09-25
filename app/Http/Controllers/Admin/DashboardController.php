<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JadwalKereta;
use App\Models\User;
use App\Models\Pemesanan;
use App\Models\Kereta;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with today's schedules, total users, completed bookings, and total trains.
     */
    public function index()
    {
        // Get today's train schedules
        $todaySchedules = JadwalKereta::whereDate('jam_keberangkatan', Carbon::today())
            ->with(['kereta', 'stasiunAsal', 'stasiunTujuan'])
            ->get();

        // Get total users
        $totalUsers = User::count();

        // Get completed bookings
        $completedBookings = Pemesanan::where('status_bayar', 'completed')->count();

        // Get total trains
        $totalTrains = Kereta::count();

        return view('admin_kai.dashboard', compact(
            'todaySchedules',
            'totalUsers',
            'completedBookings',
            'totalTrains'
        ));
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
