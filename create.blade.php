<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Anggota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background:#F7FAFD;">

<nav class="navbar" style="background:#52799F;">
    <div class="container">
        <span class="navbar-brand text-white fw-bold">SIMPAN PINJAM</span>
        <a href="{{ route('anggota.index') }}" class="btn btn-light btn-sm">Kembali</a>
    </div>
</nav>

<div class="container py-5">
    <div class="card border-0 shadow-sm mx-auto" style="max-width:700px;">
        <div class="card-body p-4">

            <h3 class="fw-bold mb-2" style="color:#52799F;">Tambah Anggota</h3>
            <p class="text-secondary mb-4">Masukkan informasi anggota baru.</p>

            <form action="{{ route('anggota.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Akun Customer</label>
                    <select name="user_id" class="form-select" required>
                        <option value="">-- Pilih Akun Customer --</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} - {{ $user->email }}
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Nomor Anggota</label>
                    <input type="text" name="nomor_anggota" class="form-control"
                           value="{{ old('nomor_anggota') }}" placeholder="Contoh: AG001" required>
                    @error('nomor_anggota')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control"
                           value="{{ old('nama') }}" placeholder="Masukkan nama anggota" required>
                    @error('nama')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="3"
                              placeholder="Masukkan alamat anggota" required>{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label">Nomor Telepon</label>
                    <input type="text" name="telepon" class="form-control"
                           value="{{ old('telepon') }}" placeholder="Contoh: 081234567890" required>
                    @error('telepon')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn text-white" style="background:#52799F;">
                        Simpan Anggota
                    </button>
                    <a href="{{ route('anggota.index') }}" class="btn btn-secondary">
                        Batal
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>

</body>
</html>
