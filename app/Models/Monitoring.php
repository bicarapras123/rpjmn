<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Monitoring extends Model
{
    use HasFactory;

    protected $fillable = [
        'indikator_id',
        'catatan',
        'tahun',
        'realisasi',
        'keterangan',
    ];

    public function indikator()
    {
        return $this->belongsTo(Indikator::class);
    }
}
