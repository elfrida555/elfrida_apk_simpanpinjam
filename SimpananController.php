<?php

namespace App\Http\Controllers;

use App\Models\Simpanan;
use App\Models\Anggota;
use Illuminate\Http\Request;

class SimpananController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        $simpanans = Simpanan::with('anggota')->latest()->get();

        return view('admin.simpanan.index', compact('simpanans'));
    }

    public function create()
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        $anggotas = Anggota::orderBy('nama')->get();

        return view('admin.simpanan.create', compact('anggotas'));
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        $data = $request->validate([
            'anggota_id' => 'required|exists:anggotas,id',
            'nomor_transaksi' => 'required|string|max:255|unique:simpanans,nomor_transaksi',
            'tanggal' => 'required|date',
            'jumlah' => 'required|numeric|min:1',
            'jenis' => 'required|in:Pokok,Wajib,Sukarela',
            'keterangan' => 'nullable|string',
        ]);

        Simpanan::create($data);

        return redirect()->route('admin.simpanan.index')
            ->with('success', 'Transaksi simpanan berhasil ditambahkan.');
    }

    public function edit(Simpanan $simpanan)
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        $anggotas = Anggota::orderBy('nama')->get();

        return view('admin.simpanan.edit', compact('simpanan', 'anggotas'));
    }

    public function update(Request $request, Simpanan $simpanan)
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        $data = $request->validate([
            'anggota_id' => 'required|exists:anggotas,id',
            'nomor_transaksi' => 'required|string|max:255|unique:simpanans,nomor_transaksi,' . $simpanan->id,
            'tanggal' => 'required|date',
            'jumlah' => 'required|numeric|min:1',
            'jenis' => 'required|in:Pokok,Wajib,Sukarela',
            'keterangan' => 'nullable|string',
        ]);

        $simpanan->update($data);

        return redirect()->route('admin.simpanan.index')
            ->with('success', 'Transaksi simpanan berhasil diperbarui.');
    }

    public function destroy(Simpanan $simpanan)
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        $simpanan->delete();

        return redirect()->route('admin.simpanan.index')
            ->with('success', 'Transaksi simpanan berhasil dihapus.');
    }
}
