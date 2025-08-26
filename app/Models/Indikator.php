<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Indikator extends Model
{
    use HasFactory;

    protected $fillable = [
        'indikator',
        'direktorat',
        'kl_pelaksana',
        'baseline',
        'tahun_2019',
        'tahun_2020',
        'tahun_2021',
        'tahun_2022',
        'target',
        'status',
        'file',
    ];

    public function monitoring()
    {
        return $this->hasMany(Monitoring::class, 'indikator_id');
    }
}
