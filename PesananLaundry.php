<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PesananLaundry extends Model
{
    protected $table = 'pesanan_laundry';

    protected $fillable = [
        'kode_pesanan',
        'nama_pelanggan',
        'nomor_telepon',
        'berat_kg',
        'jenis_layanan_id',
    ];

    public function jenisLayanan()
    {
        return $this->belongsTo(JenisLayanan::class, 'jenis_layanan_id');
    }

    public function statusBerat()
    {
        return $this->berat_kg >= 10 ? 'Besar' : 'Reguler';
    }
}
