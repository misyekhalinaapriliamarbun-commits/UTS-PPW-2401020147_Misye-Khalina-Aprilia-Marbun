
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Pesanan Laundry</title>
</head>
<body>

    <h1>EDIT PESANAN LAUNDRY</h1>
    <p>Perbarui informasi pesanan pelanggan.</p>

    @if(session('error'))
        <p>{{ session('error') }}</p>
    @endif

    @if($errors->any())
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="/pesanan-laundry/{{ $pesanan->id }}" method="POST">
        @csrf
        @method('PUT')

        <p>
            <label>Kode Pesanan:</label><br>
            <input type="text" name="kode_pesanan"
                   value="{{ old('kode_pesanan', $pesanan->kode_pesanan) }}" required>
        </p>

        <p>
            <label>Nama Pelanggan:</label><br>
            <input type="text" name="nama_pelanggan"
                   value="{{ old('nama_pelanggan', $pesanan->nama_pelanggan) }}" required>
        </p>

        <p>
            <label>Nomor Telepon:</label><br>
            <input type="text" name="nomor_telepon"
                   value="{{ old('nomor_telepon', $pesanan->nomor_telepon) }}" required>
        </p>

        <p>
            <label>Berat Laundry (kg):</label><br>
            <input type="number" name="berat_kg"
                   value="{{ old('berat_kg', $pesanan->berat_kg) }}"
                   min="1" max="20" required>
        </p>

        <p>
            <label>Jenis Layanan:</label><br>
            <select name="jenis_layanan_id" required>
                <option value="">-- Pilih Layanan --</option>

                @foreach($jenisLayanan as $jenis)
                    <option value="{{ $jenis->id }}"
                        {{ old('jenis_layanan_id', $pesanan->jenis_layanan_id) == $jenis->id ? 'selected' : '' }}>
                        {{ $jenis->nama_layanan }}
                    </option>
                @endforeach
            </select>
        </p>

        <button type="submit">Simpan Perubahan</button>
        <a href="/pesanan-laundry">Kembali</a>
    </form>

</body>
</html>
