<?php

namespace App\Http\Controllers;

use App\Models\PesananLaundry;
use App\Models\JenisLayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PDOException;
use Throwable;

class PesananLaundryController extends Controller
{
    // Menampilkan daftar pesanan
    public function index(Request $request)
    {
        try {
            $query = DB::table('pesanan_laundry')
                ->join('jenis_layanan', 'pesanan_laundry.jenis_layanan_id', '=', 'jenis_layanan.id')
                ->select('pesanan_laundry.*', 'jenis_layanan.nama_layanan');

            if ($request->filled('search')) {
                $search = trim(strip_tags($request->search));

                $query->where(function ($q) use ($search) {
                    $q->where('kode_pesanan', 'like', "%{$search}%")
                      ->orWhere('nama_pelanggan', 'like', "%{$search}%")
                      ->orWhere('nomor_telepon', 'like', "%{$search}%");
                });
            }

            if ($request->filled('jenis_layanan_id')) {
                $query->where(
                    'pesanan_laundry.jenis_layanan_id',
                    $request->jenis_layanan_id
                );
            }

            $pesanan = $query->orderBy('pesanan_laundry.id', 'desc')->get();
            $jenisLayanan = DB::table('jenis_layanan')->get();

            return view('pesanan-laundry.index', compact(
                'pesanan',
                'jenisLayanan'
            ));
        } catch (Throwable $e) {
            report($e);
            return response('Terjadi kesalahan saat mengambil data pesanan.', 500);
        }
    }

    // Menampilkan formulir edit
    public function edit($id)
    {
        try {
            $pesanan = DB::table('pesanan_laundry')->where('id', $id)->first();
            $jenisLayanan = DB::table('jenis_layanan')->get();

            if (!$pesanan) {
                return redirect('/pesanan-laundry')
                    ->with('error', 'Pesanan tidak ditemukan.');
            }

            return view('pesanan-laundry.edit', compact(
                'pesanan',
                'jenisLayanan'
            ));
        } catch (Throwable $e) {
            report($e);
            return response('Terjadi kesalahan saat mengambil data.', 500);
        }
    }

    // Menambahkan pesanan
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_pesanan' => ['required', 'regex:/^[A-Za-z0-9]{10}$/', 'unique:pesanan_laundry,kode_pesanan'],
            'nama_pelanggan' => ['required', 'string', 'min:3', 'max:100'],
            'nomor_telepon' => ['required', 'string', 'min:10', 'max:20', 'unique:pesanan_laundry,nomor_telepon'],
            'berat_kg' => ['required', 'integer', 'between:1,20'],
            'jenis_layanan_id' => ['required', 'exists:jenis_layanan,id'],
        ]);

        if ($validator->fails()) {
            return redirect('/pesanan-laundry')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::insert(
                'INSERT INTO pesanan_laundry
                (kode_pesanan, nama_pelanggan, nomor_telepon, berat_kg, jenis_layanan_id, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, ?)',
                [
                    trim(strip_tags($request->kode_pesanan)),
                    trim(strip_tags($request->nama_pelanggan)),
                    trim(strip_tags($request->nomor_telepon)),
                    $request->berat_kg,
                    $request->jenis_layanan_id,
                    now(),
                    now(),
                ]
            );

            return redirect('/pesanan-laundry')
                ->with('success', 'Pesanan berhasil ditambahkan.');
        } catch (Throwable $e) {
            report($e);
            return redirect('/pesanan-laundry')
                ->with('error', 'Pesanan gagal ditambahkan. Periksa kembali data.');
        }
    }

    // Memperbarui pesanan
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kode_pesanan' => ['required', 'regex:/^[A-Za-z0-9]{10}$/', 'unique:pesanan_laundry,kode_pesanan,' . $id],
            'nama_pelanggan' => ['required', 'string', 'min:3', 'max:100'],
            'nomor_telepon' => ['required', 'string', 'min:10', 'max:20', 'unique:pesanan_laundry,nomor_telepon,' . $id],
            'berat_kg' => ['required', 'integer', 'between:1,20'],
            'jenis_layanan_id' => ['required', 'exists:jenis_layanan,id'],
        ]);

        if ($validator->fails()) {
            return redirect('/pesanan-laundry/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $jumlah = DB::update(
                'UPDATE pesanan_laundry
                SET kode_pesanan = ?, nama_pelanggan = ?, nomor_telepon = ?,
                    berat_kg = ?, jenis_layanan_id = ?, updated_at = ?
                WHERE id = ?',
                [
                    trim(strip_tags($request->kode_pesanan)),
                    trim(strip_tags($request->nama_pelanggan)),
                    trim(strip_tags($request->nomor_telepon)),
                    $request->berat_kg,
                    $request->jenis_layanan_id,
                    now(),
                    $id,
                ]
            );

            if (!$jumlah) {
                return redirect('/pesanan-laundry')
                    ->with('error', 'Pesanan tidak ditemukan atau tidak ada perubahan.');
            }

            return redirect('/pesanan-laundry')
                ->with('success', 'Pesanan berhasil diperbarui.');
        } catch (Throwable $e) {
            report($e);
            return redirect('/pesanan-laundry/' . $id . '/edit')
                ->with('error', 'Pesanan gagal diperbarui.');
        }
    }

    // Menghapus pesanan
    public function destroy($id)
    {
        try {
            $jumlah = DB::delete(
                'DELETE FROM pesanan_laundry WHERE id = ?',
                [$id]
            );

            if (!$jumlah) {
                return redirect('/pesanan-laundry')
                    ->with('error', 'Pesanan tidak ditemukan.');
            }

            return redirect('/pesanan-laundry')
                ->with('success', 'Pesanan berhasil dihapus.');
        } catch (Throwable $e) {
            report($e);
            return redirect('/pesanan-laundry')
                ->with('error', 'Pesanan gagal dihapus.');
        }
    }
}
