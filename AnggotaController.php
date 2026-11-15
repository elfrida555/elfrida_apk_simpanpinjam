<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AnggotaController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        $anggotas = Anggota::with('user')->latest()->get();

        return view('admin.anggota.index', compact('anggotas'));
    }

    public function create()
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        $users = User::where('role', 'customer')
            ->whereDoesntHave('anggota')
            ->get();

        return view('admin.anggota.create', compact('users'));
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        $data = $request->validate([
            'user_id' => 'required|exists:users,id|unique:anggotas,user_id',
            'nomor_anggota' => 'required|string|max:255|unique:anggotas,nomor_anggota',
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'telepon' => 'required|string|max:20',
        ]);

        Anggota::create($data);

        return redirect()->route('anggota.index')
            ->with('success', 'Data anggota berhasil ditambahkan.');
    }

    public function edit(Anggota $anggota)
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        $users = User::where('role', 'customer')->get();

        return view('admin.anggota.edit', compact('anggota', 'users'));
    }

    public function update(Request $request, Anggota $anggota)
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        $data = $request->validate([
            'user_id' => [
                'required',
                'exists:users,id',
                Rule::unique('anggotas', 'user_id')->ignore($anggota->id),
            ],
            'nomor_anggota' => [
                'required',
                'string',
                'max:255',
                Rule::unique('anggotas', 'nomor_anggota')->ignore($anggota->id),
            ],
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'telepon' => 'required|string|max:20',
        ]);

        $anggota->update($data);

        return redirect()->route('anggota.index')
            ->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function destroy(Anggota $anggota)
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        $anggota->delete();

        return redirect()->route('anggota.index')
            ->with('success', 'Data anggota berhasil dihapus.');
    }
}