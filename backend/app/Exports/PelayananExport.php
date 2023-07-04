<?php

namespace App\Exports;

use App\Models\Pelayanan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class PelayananExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize
{
  protected $tgl_awal;
  protected $tgl_akhir;
  protected $count = 0;

  function __construct($tgl_awal, $tgl_akhir) {
          $this->tgl_awal = $tgl_awal;
          $this->tgl_akhir = $tgl_akhir;
  }
  /**
  * @return \Illuminate\Support\Collection
  */
  public function collection()
  {
    $awal = $this->tgl_awal;
    $akhir = $this->tgl_akhir;
    return Pelayanan::with(['kunjungan.pengunjung', 'user'])->orderBy('created_at', 'DESC')->whereHas('kunjungan', function($q) use ($awal, $akhir) { $q->where('waktu_kunjungan', '>=', $awal.' 00:00:00')->where('waktu_kunjungan', '<=', $akhir.' 23:59:59'); })->get();
  }

  public function map($row): array
  {
      $this->count++;
      $nama_petugas = "";
      if ($row->petugas_id) {
        $nama_petugas = $row->user->nama;
      }
      return [
          $this->count, // attendance->id
          $row->id,
          $row->kunjungan->waktu_kunjungan, // attendance->created_at
          $nama_petugas,
          $row->kunjungan->pengunjung->nama, // list of offers separated with | base on above logic
          $row->kunjungan->pengunjung->jabatan.' di '.$row->kunjungan->pengunjung->instansi, // list of offers separated with | base on above logic
          // $row->kunjungan->pengunjung->jabatan, // list of offers separated with | base on above logic
          // $row->kunjungan->pengunjung->instansi, // list of offers separated with | base on above logic
          $row->kunjungan->tujuan, // list of offers separated with | base on above logic
          $row->data_diminta,
          url('pelayanan/'.$row->dokumentasi),
          $row->status_layanan,
          // $row->kunjungan->pengunjung->no_hp, // list of offers separated with | base on above logic
          // $row->kunjungan->pengunjung->no_wa, // list of offers separated with | base on above logic
          // $row->kunjungan->kategori->kategori, // list of offers separated with | base on above logic
      ];
  }

  // public function registerEvents(): array
  // {
  //   return [
  //     AfterSheet::class => function(AfterSheet $event) {
  //       $awal = $this->tgl_awal;
  //       $akhir = $this->tgl_akhir;
  //       $kunjungan = Pelayanan::with(['kunjungan.pengunjung', 'user'])->orderBy('created_at', 'DESC')->whereHas('kunjungan', function($q) use ($awal, $akhir) { $q->where('waktu_kunjungan', '>=', $awal.' 00:00:00')->where('waktu_kunjungan', '<=', $akhir.' 23:59:59'); })->get();

  //       foreach ($kunjungan as $index => $k) {
  //         $drawing = new Drawing();
  //         $drawing->setPath(public_path('pelayanan/'.$k->dokumentasi));
  //         $drawing->setHeight(90);
  //         // $drawing->setCoordinates('H'.($index+2));

  //         $event->sheet->getDelegate()->setDrawing($drawing, 'H'.($index+2));
  //       }
  //     }
  //   ];
  // }

  // public function drawings()
  // {
  //   $awal = $this->tgl_awal;
  //   $akhir = $this->tgl_akhir;
  //   $kunjungan = Pelayanan::with(['kunjungan.pengunjung', 'user'])->orderBy('created_at', 'DESC')->whereHas('kunjungan', function($q) use ($awal, $akhir) { $q->where('waktu_kunjungan', '>=', $awal.' 00:00:00')->where('waktu_kunjungan', '<=', $akhir.' 23:59:59'); })->get();
  //   $images = array();
  //   foreach ($kunjungan as $index => $k) {
  //     $drawing = new Drawing();
  //     $drawing->setPath(public_path('pelayanan/'.$k->dokumentasi));
  //     $drawing->setHeight(90);
  //     $drawing->setCoordinates('H'.($index+2));

  //     array_push($images, $drawing);
  //   }

  //   return $images;
  // }

  public function headings(): array
  {
      return [
          'no',
          'id',
          'waktu kunjungan',
          'petugas yang melayani',
          'nama pengunjung',
          'jabatan dan instansi',
          'tujuan',
          'data yang diminta',
          'dokumentasi',
          'status',
          // 'jabatan',
          // 'instansi',
          // 'no_hp',
          // 'no_wa',
          // 'kategori',
      ];
  }
}
