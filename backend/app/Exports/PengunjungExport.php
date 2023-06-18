<?php

namespace App\Exports;

use App\Models\Pengunjung;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PengunjungExport implements FromCollection, WithMapping, WithHeadings
{
  protected $nama;
  protected $jabatan;
  protected $instansi;
  protected $count = 0;

  function __construct($nama, $instansi, $jabatan) {
    $this->nama = $nama;
    $this->instansi = $instansi;
    $this->jabatan = $jabatan;
  }

  /**
  * @return \Illuminate\Support\Collection
  */
  public function collection()
  {
      return Pengunjung::where('nama', 'LIKE', '%'.$this->nama.'%')->where('instansi', 'LIKE', '%'.$this->instansi.'%')->where('jabatan', 'LIKE', '%'.$this->jabatan.'%')->get();
  }

  public function map($row): array
  {
    $this->count++;
    return [
        $this->count, // attendance->id
        $row->id,
        $row->nama, // list of offers separated with | base on above logic
        $row->email, // list of offers separated with | base on above logic
        // $row->jabatan.' di '.$row->pengunjung->instansi, // list of offers separated with | base on above logic
        $row->jabatan, // list of offers separated with | base on above logic
        $row->instansi, // list of offers separated with | base on above logic
        $row->no_hp, // list of offers separated with | base on above logic
        $row->no_wa, // list of offers separated with | base on above logic
    ];
  }

  public function headings(): array
  {
      return [
          'no',
          'id',
          'nama',
          'email',
          // 'jabatan dan instansi',
          'jabatan',
          'instansi',
          'no_hp',
          'no_wa'
      ];
  }
}
