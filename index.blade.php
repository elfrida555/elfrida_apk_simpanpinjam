<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Anggota - Simpan Pinjam</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body style="background:#F7FAFD;">

<nav class="navbar navbar-expand-lg" style="background:#52799F;">
    <div class="container">
        <a class="navbar-brand text-white fw-bold" href="{{ route('admin.dashboard') }}">
            SIMPAN PINJAM
        </a>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-light btn-sm">
            Kembali ke Dashboard
        </a>
    </div>
</nav>

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h3 class="fw-bold" style="color:#52799F;">Data Anggota</h3>
            <p class="text-secondary mb-0">Kelola data anggota koperasi.</p>
        </div>

        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.laporan.anggota') }}"
               target="_blank" class="btn btn-danger">
                <i class="bi bi-file-earmark-pdf"></i> Cetak PDF
            </a>

            <a href="{{ route('anggota.create') }}" class="btn text-white"
               style="background:#52799F;">
                + Tambah Anggota
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead style="background:#DCEAF7;">
                        <tr>
                            <th>No</th>
                            <th>Nomor Anggota</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Telepon</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($anggotas as $anggota)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $anggota->nomor_anggota }}</td>
                                <td>{{ $anggota->nama }}</td>
                                <td>{{ $anggota->alamat }}</td>
                                <td>{{ $anggota->telepon }}</td>
                                <td>
                                    <a href="{{ route('anggota.edit', ['anggota' => $anggota->id]) }}"
                                       class="btn btn-sm btn-warning">
                                        Edit
                                    </a>

                                    <form action="{{ route('anggota.destroy', $anggota->id) }}"
                                          method="POST" class="d-inline form-hapus">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-secondary py-4">
                                    Belum ada data anggota.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.form-hapus').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Hapus anggota?',
                text: 'Data anggota akan dihapus secara permanen.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#52799F',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: @json(session('success')),
            confirmButtonColor: '#52799F'
        });
    @endif

    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: @json($errors->first()),
            confirmButtonColor: '#52799F'
        });
    @endif
</script>

</body>
</html>