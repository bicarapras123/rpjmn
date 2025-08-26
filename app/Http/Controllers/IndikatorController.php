<?php

namespace App\Http\Controllers;

use App\Models\Indikator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class IndikatorController extends Controller
{
    public function index()
    {
        $indikators = Indikator::all();
        return view('indikator.index', compact('indikators')); 
    }

    public function create()
    {
        return view('indikator.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'indikator'    => 'required',
            'direktorat'   => 'required',
            'kl_pelaksana' => 'required',
            'baseline'     => 'nullable|numeric',
            'tahun_2019'   => 'nullable|numeric',
            'tahun_2020'   => 'nullable|numeric',
            'tahun_2021'   => 'nullable|numeric',
            'tahun_2022'   => 'nullable|numeric',
            'target'       => 'nullable|numeric',
            'file'         => 'nullable|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        $data = $request->all();
        $data['status'] = null;

        // Handle file upload
        if ($request->hasFile('file')) {
            $data['file'] = $request->file('file')->store('indikators', 'public');
        }

        Indikator::create($data);

        return redirect()->route('indikator.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $indikator = Indikator::findOrFail($id);
        return view('indikator.edit', compact('indikator'));
    }

    public function update(Request $request, Indikator $indikator)
    {
        $request->validate([
            'indikator'    => 'required',
            'direktorat'   => 'required',
            'kl_pelaksana' => 'required',
            'baseline'     => 'nullable|numeric',
            'tahun_2019'   => 'nullable|numeric',
            'tahun_2020'   => 'nullable|numeric',
            'tahun_2021'   => 'nullable|numeric',
            'tahun_2022'   => 'nullable|numeric',
            'target'       => 'nullable|numeric',
            'file'         => 'nullable|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        $data = $request->all();

        // Handle file baru
        if ($request->hasFile('file')) {
            // hapus file lama
            if ($indikator->file) {
                Storage::disk('public')->delete($indikator->file);
            }
            $data['file'] = $request->file('file')->store('indikators', 'public');
        }

        $indikator->update($data);

        return redirect()->route('indikator.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(Indikator $indikator)
    {
        // hapus file dari storage
        if ($indikator->file) {
            Storage::disk('public')->delete($indikator->file);
        }

        $indikator->delete();
        return redirect()->back()->with('success', 'Data indikator berhasil dihapus.');
    }

    public function hapusSemua()
    {
        // hapus semua file juga
        $files = Indikator::pluck('file')->filter();
        foreach ($files as $file) {
            Storage::disk('public')->delete($file);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('indikators')->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        return redirect()->route('indikator.index')->with('success', 'Semua data berhasil dihapus.');
    }

    public function setStatus($id, $status)
    {
        $indikator = Indikator::findOrFail($id);

        if (!in_array($status, ['approved', 'rejected'])) {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }

        $indikator->status = $status;
        $indikator->save();

        return redirect()->route('indikator.index')->with('success', "Indikator berhasil di-{$status}.");
    }
}
