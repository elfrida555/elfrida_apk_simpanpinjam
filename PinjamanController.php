<?php

namespace App\Http\Controllers;

use App\Models\Pinjaman;
use App\Models\Anggota;
use Illuminate\Http\Request;

class PinjamanController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        $pinjamans = Pinjaman::with('anggota')->latest()->get();

        return view('admin.pinjaman.index', compact('pinjamans'));
    }

    public function create()
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        $anggotas = Anggota::orderBy('nama')->get();

        return view('admin.pinjaman.create', compact('anggotas'));
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        $data = $request->validate([
            'anggota_id' => 'required|exists:anggotas,id',
            'nomor_transaksi' => 'required|string|max:255|unique:pinjamans,nomor_transaksi',
            'tanggal_pengajuan' => 'required|date',
            'jumlah_pinjaman' => 'required|numeric|min:1',
            'bunga' => 'required|numeric|min:0',
            'tenor' => 'required|integer|min:1',
            'status' => 'required|in:Menunggu,Disetujui,Ditolak,Lunas',
            'keterangan' => 'nullable|string',
        ]);

        Pinjaman::create($data);

        return redirect()->route('admin.pinjaman.index')
            ->with('success', 'Data pinjaman berhasil ditambahkan.');
    }

    public function edit(Pinjaman $pinjaman)
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        $anggotas = Anggota::orderBy('nama')->get();

        return view('admin.pinjaman.edit', compact('pinjaman', 'anggotas'));
    }

    public function update(Request $request, Pinjaman $pinjaman)
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        $data = $request->validate([
            'anggota_id' => 'required|exists:anggotas,id',
            'nomor_transaksi' => 'required|string|max:255|unique:pinjamans,nomor_transaksi,' . $pinjaman->id,
            'tanggal_pengajuan' => 'required|date',
            'jumlah_pinjaman' => 'required|numeric|min:1',
            'bunga' => 'required|numeric|min:0',
            'tenor' => 'required|integer|min:1',
            'status' => 'required|in:Menunggu,Disetujui,Ditolak,Lunas',
            'keterangan' => 'nullable|string',
        ]);

        $pinjaman->update($data);

        return redirect()->route('admin.pinjaman.index')
            ->with('success', 'Data pinjaman berhasil diperbarui.');
    }

    public function destroy(Pinjaman $pinjaman)
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        $pinjaman->delete();

        return redirect()->route('admin.pinjaman.index')
            ->with('success', 'Data pinjaman berhasil dihapus.');
    }
}
