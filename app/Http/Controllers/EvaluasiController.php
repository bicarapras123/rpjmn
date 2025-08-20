<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EvaluasiController extends Controller
{
    public function index()
    {
        // Pie chart: direktorat
        $direktoratLabels = collect([
            'Kemdikbud',
            'DJP',
            'Sekretariat Jenderal',
            'Kementerian Luar Negeri',
            'Badan Pengembangan SDM',
            'Staf Ahli Bidang Aparatur dan Pelayanan Publik',
            'PEPPS'
        ]);

        // Data seimbang (karena di gambar semua potongan sama besar)
        $direktoratCounts = collect([
            1, 1, 1, 1, 1, 1, 1
        ]);

        // Bar chart: total per tahun (sesuai gambar)
        $tahun = collect([
            2019 => 2200,
            2020 => 1300,
            2021 => 2500,
            2022 => 3800
        ]);

        return view('evaluasi', compact('direktoratLabels', 'direktoratCounts', 'tahun'));
    }
}