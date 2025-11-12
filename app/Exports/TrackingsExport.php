<?php

namespace App\Exports;

use App\Models\Tracking;
// Pastikan Anda menambahkan dua 'use' ini
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

// Pastikan class Anda meng-implements FromCollection dan WithHeadings
class TrackingsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Kita tidak lagi menggunakan Tracking::all()
        // Kita menggunakan .map() untuk mengubah data
        return Tracking::all()->map(function ($record) {
            
            // Logika untuk status
            $status = $record->current_stage;
            if ($status == 'completed') {
                $status = 'Selesai';
            } elseif (isset($this->stages[$status])) {
                $status = $this->stages[$status]['label'];
            }

            // Ini adalah data yang akan ditampilkan per baris
            return [
                'vehicle_name'   => $record->vehicle_name,
                'plate_number'   => $record->plate_number,
                'description'    => $record->description,
                
                // Format tanggal: Jika ada, format. Jika tidak, tampilkan '-'
                'security_start' => $record->security_start ? $record->security_start->format('d/m/Y H:i') : '-',
                'security_end'   => $record->security_end ? $record->security_end->format('d/m/Y H:i') : '-',
                
                'loading_start'  => $record->loading_start ? $record->loading_start->format('d/m/Y H:i') : '-',
                'loading_end'    => $record->loading_end ? $record->loading_end->format('d/m/Y H:i') : '-',
                
                'ttb_start'      => $record->ttb_start ? $record->ttb_start->format('d/m/Y H:i') : '-',
                'ttb_end'        => $record->ttb_end ? $record->ttb_end->format('d/m/Y H:i') : '-',
                
                'current_stage'  => $status,
                'created_at'     => $record->created_at->format('d/m/Y H:i'),
            ];
        });
    }

    /**
     * Fungsi ini akan membuat baris Header (Judul Kolom)
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Nama Kendaraan',
            'Plat Nomor',
            'Keterangan',
            'Security - Mulai',
            'Security - Selesai',
            'Bongkar Muat - Mulai',
            'Bongkar Muat - Selesai',
            'Officer TTB - Mulai',
            'Officer TTB - Selesai',
            'Status Terakhir',
            'Tanggal Dibuat'
        ];
    }

    // Properti ini dibutuhkan untuk mengambil data $stages dari komponen Livewire
    // Tapi karena ini class terpisah, kita definisikan saja di sini.
    public $stages = [
        'security' => ['label' => 'Security'],
        'loading'  => ['label' => 'Bongkar Muat'],
        'ttb'      => ['label' => 'Officer TTB'],
    ];
}