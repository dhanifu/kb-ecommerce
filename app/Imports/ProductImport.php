<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ProductImport implements WithStartRow, WithChunkReading
{
    /**
    * @param Collection $collection
    */
    use Importable;

    // BATASi DATA YANG AKAN DIGUNAKAN MULAI DARI BARIS KEDUA, KARENA BARIS PERTAMA DIGUNAKAN UNTUK HEADING
    public function startRow(): int
    {
        return 2;
    }

    // GUNAKAN chunkSize UNTUK MENGONTROL PENGGUNAAN MEMORY DENGAN MEMBATASI LOAD DATA DALAM SEKALI PROSES
    public function chunkSize(): int
    {
        return 100;
    }
}
