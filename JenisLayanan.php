<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisLayanan extends Model
{
    protected $table = 'jenis_layanan';

    public $timestamps = false;

    protected $fillable = [
        'nama_layanan',
    ];

    public function pesanan()
    {
        return $this->hasMany(PesananLaundry::class, 'jenis_layanan_id');
    }
}
