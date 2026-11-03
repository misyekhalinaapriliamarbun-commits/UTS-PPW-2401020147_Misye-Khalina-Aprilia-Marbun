
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Pesanan Laundry</title>
</head>
<body>

    <h1>SISTEM PESANAN LAUNDRY</h1>
    <p>Pengelolaan data pesanan laundry pelanggan.</p>

    @if(session('success'))
        <p>{{ session('success') }}</p>
    @endif

    @if(session('error'))
        <p>{{ session('error') }}</p>
    @endif

    <h2>Tambah Pesanan Laundry</h2>

    @if($errors->any())
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="/pesanan-laundry" method="POST">
        @csrf

        <p>
            <label>Kode Pesanan:</label><br>
            <input type="text" name="kode_pesanan"
                   value="{{ old('kode_pesanan') }}" required>
        </p>

        <p>
            <label>Nama Pelanggan:</label><br>
            <input type="text" name="nama_pelanggan"
                   value="{{ old('nama_pelanggan') }}" required>
        </p>

        <p>
            <label>Nomor Telepon:</label><br>
            <input type="text" name="nomor_telepon"
                   value="{{ old('nomor_telepon') }}" required>
        </p>

        <p>
            <label>Berat Laundry (kg):</label><br>
            <input type="number" name="berat_kg"
                   value="{{ old('berat_kg') }}" min="1" max="20" required>
        </p>

        <p>
            <label>Jenis Layanan:</label><br>
            <select name="jenis_layanan_id" required>
                <option value="">-- Pilih Layanan --</option>
                @foreach($jenisLayanan as $jenis)
                    <option value="{{ $jenis->id }}"
                        {{ old('jenis_layanan_id') == $jenis->id ? 'selected' : '' }}>
                        {{ $jenis->nama_layanan }}
                    </option>
                @endforeach
            </select>
        </p>

        <button type="submit">Simpan Pesanan</button>
    </form>

    <hr>

    <h2>Daftar Pesanan Laundry</h2>

    <form action="/pesanan-laundry" method="GET">
        <input type="text" name="search"
               placeholder="Cari kode, nama, atau telepon..."
               value="{{ request('search') }}">

        <select name="jenis_layanan_id">
            <option value="">Semua Jenis Layanan</option>

            @foreach($jenisLayanan as $jenis)
                <option value="{{ $jenis->id }}"
                    {{ request('jenis_layanan_id') == $jenis->id ? 'selected' : '' }}>
                    {{ $jenis->nama_layanan }}
                </option>
            @endforeach
        </select>

        <button type="submit">Cari / Filter</button>
        <a href="/pesanan-laundry">Reset</a>
    </form>

    <br>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>No.</th>
                <th>Kode Pesanan</th>
                <th>Nama Pelanggan</th>
                <th>Nomor Telepon</th>
                <th>Berat (kg)</th>
                <th>Status Berat</th>
                <th>Jenis Layanan</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse($pesanan as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->kode_pesanan }}</td>
                    <td>{{ $item->nama_pelanggan }}</td>
                    <td>{{ $item->nomor_telepon }}</td>
                    <td>{{ $item->berat_kg }}</td>
                    <td>
                        @if($item->berat_kg >= 10)
                            Besar
                        @else
                            Reguler
                        @endif
                    </td>
                    <td>{{ $item->nama_layanan }}</td>
                    <td>
                        <a href="/pesanan-laundry/{{ $item->id }}/edit">
                            Edit
                        </a>

                        <form action="/pesanan-laundry/{{ $item->id }}"
                              method="POST">
                            @csrf
                            @method('DELETE')

                            <button type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">Belum ada data pesanan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
