<?php

namespace App\Http\Controllers;

use App\Models\Indikator;
use Illuminate\Http\Request;

class EvaluasiController extends Controller
{
    public function index()
    {
        // Ambil semua data indikator
        $indikators = Indikator::all();

        // Hitung jumlah per direktorat
        $direktoratLabels = $indikators->pluck('direktorat')->unique()->values();
        $direktoratCounts = $direktoratLabels->map(function ($dir) use ($indikators) {
            return $indikators->where('direktorat', $dir)->count();
        });

        // Hitung jumlah per tahun
        $tahun = collect([
            2019 => $indikators->sum('tahun_2019'),
            2020 => $indikators->sum('tahun_2020'),
            2021 => $indikators->sum('tahun_2021'),
            2022 => $indikators->sum('tahun_2022'),
        ]);

        return view('evaluasi', compact('indikators', 'direktoratLabels', 'direktoratCounts', 'tahun'));
    }
    public function edit($id)
{
    $indikator = Indikator::findOrFail($id);
    return view('indikator.edit', compact('indikator'));
}

public function update(Request $request, $id)
{
    $indikator = Indikator::findOrFail($id);
    $indikator->update($request->all());

    return redirect()->route('indikator.index')->with('success', 'Data berhasil diperbarui');
}

}
