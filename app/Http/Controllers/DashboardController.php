<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Indikator; // Pastikan model ini sesuai dengan nama model data monitoring kamu

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil semua data dari indikator/monitoring
        $indikator = Indikator::all();

        // Total jumlah nilai per tahun dari semua indikator
        $tahun2019 = $indikator->sum('tahun_2019');
        $tahun2020 = $indikator->sum('tahun_2020');
        $tahun2021 = $indikator->sum('tahun_2021');
        $tahun2022 = $indikator->sum('tahun_2022');

        // Pie chart berdasarkan direktorat (ubah ke 'kl_pelaksana' atau 'target' jika perlu)
        $direktoratLabels = $indikator->pluck('direktorat')->unique()->values();
        $direktoratCounts = $direktoratLabels->map(function ($dir) use ($indikator) {
            return $indikator->where('direktorat', $dir)->count();
        });

        // Kirim data ke dashboard view
        return view('dashboard', [
            'tahun' => [
                '2019' => $tahun2019,
                '2020' => $tahun2020,
                '2021' => $tahun2021,
                '2022' => $tahun2022,
            ],
            'direktoratLabels' => $direktoratLabels,
            'direktoratCounts' => $direktoratCounts,
        ]);
    }
}
