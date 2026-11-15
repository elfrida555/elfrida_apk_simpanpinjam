<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Simpanan;
use App\Models\Pinjaman;
use FPDF;

class LaporanController extends Controller
{
    // Mengubah teks UTF-8 agar terbaca oleh FPDF
    private function teks($teks)
    {
        return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', (string) $teks);
    }

    // Laporan Data Anggota
    public function anggota()
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        $data = Anggota::with('user')->orderBy('id')->get();

        $pdf = new FPDF('L', 'mm', 'A4');
        $pdf->SetMargins(10, 10, 10);
        $pdf->AddPage();

        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, $this->teks('LAPORAN DATA ANGGOTA'), 0, 1, 'C');

        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 8, $this->teks('Tanggal Cetak: ' . date('d-m-Y')), 0, 1);
        $pdf->Ln(3);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(15, 10, 'No', 1, 0, 'C');
        $pdf->Cell(40, 10, 'Nomor Anggota', 1, 0, 'C');
        $pdf->Cell(65, 10, 'Nama', 1, 0, 'C');
        $pdf->Cell(100, 10, 'Alamat', 1, 0, 'C');
        $pdf->Cell(55, 10, 'Telepon', 1, 1, 'C');

        $pdf->SetFont('Arial', '', 10);

        $no = 1;
        foreach ($data as $anggota) {
            $pdf->Cell(15, 9, $no++, 1, 0, 'C');
            $pdf->Cell(40, 9, $this->teks($anggota->nomor_anggota), 1);
            $pdf->Cell(65, 9, $this->teks($anggota->nama), 1);
            $pdf->Cell(100, 9, $this->teks($anggota->alamat), 1);
            $pdf->Cell(55, 9, $this->teks($anggota->telepon), 1, 1);
        }

        $pdf->Ln(3);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(0, 8, 'Total Data Anggota: ' . $data->count(), 0, 1);

        return response($pdf->Output('S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="laporan-anggota.pdf"');
    }

    // Laporan Transaksi Simpanan
    public function simpanan()
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        $data = Simpanan::with('anggota')->orderBy('id')->get();

        $pdf = new FPDF('L', 'mm', 'A4');
        $pdf->SetMargins(10, 10, 10);
        $pdf->AddPage();

        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, $this->teks('LAPORAN TRANSAKSI SIMPANAN'), 0, 1, 'C');

        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 8, $this->teks('Tanggal Cetak: ' . date('d-m-Y')), 0, 1);
        $pdf->Ln(3);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(12, 10, 'No', 1, 0, 'C');
        $pdf->Cell(38, 10, 'No. Transaksi', 1, 0, 'C');
        $pdf->Cell(55, 10, 'Nama Anggota', 1, 0, 'C');
        $pdf->Cell(30, 10, 'Tanggal', 1, 0, 'C');
        $pdf->Cell(40, 10, 'Jumlah', 1, 0, 'C');
        $pdf->Cell(35, 10, 'Jenis', 1, 0, 'C');
        $pdf->Cell(65, 10, 'Keterangan', 1, 1, 'C');

        $pdf->SetFont('Arial', '', 9);

        $no = 1;
        foreach ($data as $simpanan) {
            $pdf->Cell(12, 9, $no++, 1, 0, 'C');
            $pdf->Cell(38, 9, $this->teks($simpanan->nomor_transaksi), 1);
            $pdf->Cell(55, 9, $this->teks($simpanan->anggota->nama ?? '-'), 1);
            $pdf->Cell(30, 9, $this->teks($simpanan->tanggal), 1);
            $pdf->Cell(40, 9, 'Rp ' . number_format($simpanan->jumlah, 0, ',', '.'), 1);
            $pdf->Cell(35, 9, $this->teks($simpanan->jenis), 1);
            $pdf->Cell(65, 9, $this->teks($simpanan->keterangan ?? '-'), 1, 1);
        }

        $pdf->Ln(3);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(0, 8, 'Total Data Simpanan: ' . $data->count(), 0, 1);

        return response($pdf->Output('S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="laporan-simpanan.pdf"');
    }

    // Laporan Transaksi Pinjaman
    public function pinjaman()
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        $data = Pinjaman::with('anggota')->orderBy('id')->get();

        $pdf = new FPDF('L', 'mm', 'A4');
        $pdf->SetMargins(10, 10, 10);
        $pdf->AddPage();

        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, $this->teks('LAPORAN TRANSAKSI PINJAMAN'), 0, 1, 'C');

        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 8, $this->teks('Tanggal Cetak: ' . date('d-m-Y')), 0, 1);
        $pdf->Ln(3);

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(12, 10, 'No', 1, 0, 'C');
        $pdf->Cell(35, 10, 'No. Transaksi', 1, 0, 'C');
        $pdf->Cell(48, 10, 'Nama Anggota', 1, 0, 'C');
        $pdf->Cell(28, 10, 'Tanggal', 1, 0, 'C');
        $pdf->Cell(38, 10, 'Jumlah Pinjaman', 1, 0, 'C');
        $pdf->Cell(22, 10, 'Bunga (%)', 1, 0, 'C');
        $pdf->Cell(20, 10, 'Tenor', 1, 0, 'C');
        $pdf->Cell(25, 10, 'Status', 1, 0, 'C');
        $pdf->Cell(47, 10, 'Keterangan', 1, 1, 'C');

        $pdf->SetFont('Arial', '', 9);

        $no = 1;
        foreach ($data as $pinjaman) {
            $pdf->Cell(12, 9, $no++, 1, 0, 'C');
            $pdf->Cell(35, 9, $this->teks($pinjaman->nomor_transaksi), 1);
            $pdf->Cell(48, 9, $this->teks($pinjaman->anggota->nama ?? '-'), 1);
            $pdf->Cell(28, 9, $this->teks($pinjaman->tanggal_pengajuan), 1);
            $pdf->Cell(38, 9, 'Rp ' . number_format($pinjaman->jumlah_pinjaman, 0, ',', '.'), 1);
            $pdf->Cell(22, 9, $this->teks($pinjaman->bunga), 1, 0, 'C');
            $pdf->Cell(20, 9, $this->teks($pinjaman->tenor), 1, 0, 'C');
            $pdf->Cell(25, 9, $this->teks($pinjaman->status), 1, 0, 'C');
            $pdf->Cell(47, 9, $this->teks($pinjaman->keterangan ?? '-'), 1, 1);
        }

        $pdf->Ln(3);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(0, 8, 'Total Data Pinjaman: ' . $data->count(), 0, 1);

        return response($pdf->Output('S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="laporan-pinjaman.pdf"');
    }
}
