<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laporan;
use Illuminate\Support\Facades\Mail;
use App\Models\Indikator;

class LaporanController extends Controller
{
    public function index(Request $request) // <-- tambahkan ini
{
    $laporans = Laporan::query()
        ->when($request->search, function ($query, $search) {
            $query->where('nama', 'like', "%$search%")
                  ->orWhere('unit', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
        })
        ->latest()
        ->paginate(10); // gunakan paginate agar bisa pakai links() dan currentPage()

    return view('laporan', compact('laporans'));
}

public function destroy(Indikator $monitoring)
{
    $monitoring->delete();
    return redirect()->back()->with('success', 'Data berhasil dihapus.');
}
    public function kirim(Request $request)
    {
        $validated = $request->validate([
            'nama'  => 'required|string|max:255',
            'unit'  => 'required|string|max:255',
            'email' => 'required|email',
            'isi'   => 'required|string',
        ]);

        // Simpan ke database
        $laporan = Laporan::create($validated);

        try {
            // Kirim email menggunakan view
            Mail::send('emails.laporan', $validated, function ($message) use ($validated) {
                $message->to($validated['email'])
                        ->subject('Laporan Monitoring dari ' . $validated['nama']);
            });
        
            return redirect()->route('laporan.index')->with('laporan', $laporan->toArray());
        
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim email: ' . $e->getMessage());
        }
        
    }
}
