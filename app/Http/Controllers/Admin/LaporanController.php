<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Pemesanan::with(['penumpang', 'jadwalKereta', 'detail.gerbong']);

        // Filter by start_month and end_month
        if ($request->has('start_month') && $request->has('end_month')) {
            $startMonth = Carbon::parse($request->input('start_month'))->startOfMonth();
            $endMonth = Carbon::parse($request->input('end_month'))->endOfMonth();

            // Ensure start_month is not after end_month
            if ($startMonth->lte($endMonth)) {
                $query->whereBetween('tanggal_pesan', [$startMonth, $endMonth]);
            }
        }

        $pemesanans = $query->get();
        return view('admin_kai.laporan', compact('pemesanans'));
    }

    /**
     * Generate and download PDF report
     */
    public function pdf(Request $request)
    {
        $query = Pemesanan::with(['penumpang', 'jadwalKereta', 'detail.gerbong']);

        // Filter by start_month and end_month
        if ($request->has('start_month') && $request->has('end_month')) {
            $startMonth = Carbon::parse($request->input('start_month'))->startOfMonth();
            $endMonth = Carbon::parse($request->input('end_month'))->endOfMonth();

            if ($startMonth->lte($endMonth)) {
                $query->whereBetween('tanggal_pesan', [$startMonth, $endMonth]);
            }
        }

        $pemesanans = $query->get();

        // Load the view for PDF
        $pdf = Pdf::loadView('admin_kai.laporan_pdf', compact('pemesanans'));

        // Set paper size to A4, landscape
        $pdf->setPaper('A4', 'landscape');

        // Download the PDF
        return $pdf->download('laporan_pemesanan_' . date('Ymd_His') . '.pdf');
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
