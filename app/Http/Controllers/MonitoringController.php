<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Indikator;
use App\Models\Monitoring;

class MonitoringController extends Controller
{
    // Menampilkan halaman Monitoring
    public function index()
    {
        $indikators = Indikator::all(); // ini yang ditampilkan di Blade
        return view('monitoring', compact('indikators'));
    }

    // Menampilkan halaman cetak
    public function cetak()
    {
        $indikators = Indikator::all();
        return view('monitoring.cetak', compact('indikators'));
    }

    // Menghapus semua data Monitoring & Indikator
    public function hapusSemua()
    {
        // Hapus data Monitoring terlebih dahulu (karena ada foreign key ke indikator)
        Monitoring::query()->delete();
        
        // Hapus data Indikator
        Indikator::query()->delete();

        return redirect()->route('monitoring.index')->with('success', 'Semua data berhasil dihapus.');
    }

    public function setStatus($id, $status)
{
    // Hanya moderator yang boleh
    if (auth()->user()->role !== 'moderator') {
        abort(403, 'Akses khusus Moderator');
    }

    $indikator = \App\Models\Indikator::findOrFail($id);

    // Validasi status
    if (!in_array($status, ['approved', 'rejected'])) {
        return redirect()->back()->with('error', 'Status tidak valid');
    }

    $indikator->status = $status;
    $indikator->save();

    return redirect()->back()->with('success', "Data berhasil divalidasi ($status)");
}
}
