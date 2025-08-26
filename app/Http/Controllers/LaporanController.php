<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laporan;
use Illuminate\Support\Facades\Mail;
use App\Models\Indikator;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $laporans = Laporan::query()
            ->when($request->search, function ($query, $search) {
                $query->where('nama', 'like', "%$search%")
                      ->orWhere('unit', 'like', "%$search%")
                      ->orWhere('email', 'like', "%$search%");
            })
            ->latest()
            ->paginate(10);

        return view('laporan', compact('laporans'));
    }

 public function destroy($id)
{
    $laporan = Laporan::findOrFail($id);

    // Kalau ada file, hapus dari storage
    if ($laporan->file) {
        \Storage::disk('public')->delete($laporan->file);
    }

    $laporan->delete();

    return redirect()->back()->with('success', 'Laporan berhasil dihapus.');
}

    public function kirim(Request $request)
    {
        $validated = $request->validate([
            'nama'  => 'required|string|max:255',
            'unit'  => 'required|string|max:255',
            'email' => 'required|email',
            'isi'   => 'required|string',
            'file'  => 'nullable|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        // Handle upload file
        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('laporans', 'public');
        }

        // Simpan ke database
        $laporan = Laporan::create([
            'nama'  => $validated['nama'],
            'unit'  => $validated['unit'],
            'email' => $validated['email'],
            'isi'   => $validated['isi'],
            'file'  => $filePath,
        ]);

        try {
            // Kirim email menggunakan view
            Mail::send('emails.laporan', array_merge($validated, [
                'file' => $filePath, // ← tambahin file biar bisa dipanggil di blade
            ]), function ($message) use ($validated, $filePath) {
                $message->to($validated['email'])
                        ->subject('Laporan Monitoring dari ' . $validated['nama']);
    
                // Kalau ada file, lampirkan
                if ($filePath) {
                    $message->attach(storage_path('app/public/' . $filePath));
                }
            });
        
            return redirect()->route('laporan.index')->with('success', 'Laporan berhasil dikirim.');
        
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim email: ' . $e->getMessage());
        }
    }
}