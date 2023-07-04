<?php

namespace App\Exports;

use App\Models\Kunjungan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KunjunganExport implements FromCollection, WithMapping, WithHeadings
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
      return Kunjungan::with(['pengunjung', 'kategori'])->orderBy('waktu_kunjungan', 'DESC')->where('waktu_kunjungan', '>=', $this->tgl_awal.' 00:00:00')->where('waktu_kunjungan', '<=', $this->tgl_akhir.' 23:59:59')->get();
  }

  public function map($row): array
  {
      $this->count++;
      return [
          $this->count, // attendance->id
          $row->id,
          $row->waktu_kunjungan, // attendance->created_at
          $row->pengunjung->nama, // list of offers separated with | base on above logic
          $row->pengunjung->jabatan.' di '.$row->pengunjung->instansi, // list of offers separated with | base on above logic
          // $row->pengunjung->jabatan, // list of offers separated with | base on above logic
          // $row->pengunjung->instansi, // list of offers separated with | base on above logic
          $row->pengunjung->no_hp, // list of offers separated with | base on above logic
          $row->pengunjung->no_wa, // list of offers separated with | base on above logic
          $row->kategori->kategori, // list of offers separated with | base on above logic
          $row->tujuan, // list of offers separated with | base on above logic
      ];
  }

  public function headings(): array
    {
        return [
            'no',
            'id',
            'waktu kunjungan',
            'nama pengunjung',
            'jabatan dan instansi',
            // 'jabatan',
            // 'instansi',
            'no_hp',
            'no_wa',
            'kategori',
            'tujuan',
        ];
    }
}
