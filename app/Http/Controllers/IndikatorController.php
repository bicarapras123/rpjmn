<?php

namespace App\Http\Controllers;

use App\Models\Indikator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndikatorController extends Controller
{
    public function index()
    {
        $indikators = Indikator::all();
        return view('monitoring', compact('indikators'));
    }

    public function create()
    {
        return view('indikator.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'indikator' => 'required',
            'direktorat' => 'required',
            'kl_pelaksana' => 'required',
            'baseline' => 'nullable|numeric',
            'tahun_2019' => 'nullable|numeric',
            'tahun_2020' => 'nullable|numeric',
            'tahun_2021' => 'nullable|numeric',
            'tahun_2022' => 'nullable|numeric',
            'target' => 'nullable|numeric',
        ]);

        Indikator::create($request->all());

        return redirect()->route('monitoring.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function update(Request $request, Indikator $indikator)
    {
        $request->validate([
            'indikator' => 'required',
            'direktorat' => 'required',
            'kl_pelaksana' => 'required',
            'baseline' => 'nullable|numeric',
            'tahun_2019' => 'nullable|numeric',
            'tahun_2020' => 'nullable|numeric',
            'tahun_2021' => 'nullable|numeric',
            'tahun_2022' => 'nullable|numeric',
            'target' => 'nullable|numeric',
        ]);

        $indikator->update($request->all());

        return redirect()->route('monitoring.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(Indikator $monitoring)
    {
        $monitoring->delete();
        return redirect()->back()->with('success', 'Data indikator berhasil dihapus.');
    }
    public function edit($id)
    {
        $indikator = Indikator::findOrFail($id);
        return view('indikator.create', compact('indikator'));
    }
    
    public function hapusSemua()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('monitorings')->delete();
        DB::table('indikators')->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        return redirect()->route('monitoring.index')->with('success', 'Semua data berhasil dihapus.');
    }
}
