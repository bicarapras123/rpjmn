<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EvaluasiController extends Controller
{
    public function index()
    {
        // Pie chart: direktorat (masing-masing seimbang)
        $direktoratLabels = collect([
            'Kemdikbud',
            'DJP',
            'Sekretariat Jenderal',
            'Kementerian Luar Negeri',
            'Badan Pengembangan SDM',
            'Staf Ahli Bidang Aparatur dan Pelayanan Publik',
        ]);

        $direktoratCounts = collect([
            1, 1, 1, 1, 1, 1
        ]);

        // Bar chart: total per tahun
        $tahun = collect([
            2019 => 1600,
            2020 => 1100,
            2021 => 2300,
            2022 => 3600
        ]);

        return view('evaluasi', compact('direktoratLabels', 'direktoratCounts', 'tahun'));
    }
}
