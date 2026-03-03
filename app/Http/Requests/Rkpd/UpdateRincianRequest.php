<?php

namespace App\Http\Requests\Rkpd;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRincianRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Hidden fields (read-only dari form)
            'id_rinci_sub_bl' => 'required|integer',
            'tahun_anggaran' => 'required|integer',
            'jenis_bl' => 'required|string',
            'kode_rekening' => 'required|string',
            'nama_rekening' => 'required|string',
            'tipe_paket' => 'required|in:1,2',
            'id_paket_belanja' => 'nullable|integer',
            'kategori_belanja' => 'nullable|string',

            // Editable fields
            'jenis_standar_harga' => 'nullable|in:1,2,3,4',
            'uraian' => 'required|string|max:1000',
            'id_standar_harga' => 'nullable|integer',
            'tkdn' => 'nullable|string|max:50',
            'spesifikasi_komponen' => 'nullable|string|max:2000',

            // Koefisien arrays
            'koefisien' => 'nullable|array|max:4',
            'koefisien.*' => 'nullable|numeric|min:0',
            'satuan_koefisien' => 'nullable|array|max:4',
            'satuan_koefisien.*' => 'nullable|string|max:100',

            // Volume & Harga
            'volume' => 'required|numeric|min:0',
            'satuan' => 'required|string|max:100',
            'harga_satuan' => 'required|numeric|min:0',
        ];
    }

    public function attributes(): array
    {
        return [
            'id_rinci_sub_bl' => 'ID Sub Kegiatan',
            'tahun_anggaran' => 'Tahun Anggaran',
            'jenis_bl' => 'Jenis Belanja',
            'kode_rekening' => 'Kode Rekening',
            'nama_rekening' => 'Nama Rekening',
            'tipe_paket' => 'Tipe Paket',
            'id_paket_belanja' => 'Paket Belanja',
            'kategori_belanja' => 'Kategori Belanja',
            'jenis_standar_harga' => 'Jenis Standar Harga',
            'uraian' => 'Komponen/Uraian',
            'id_standar_harga' => 'ID Standar Harga',
            'tkdn' => 'TKDN',
            'spesifikasi_komponen' => 'Spesifikasi Komponen',
            'koefisien' => 'Koefisien',
            'satuan_koefisien' => 'Satuan Koefisien',
            'volume' => 'Volume',
            'satuan' => 'Satuan',
            'harga_satuan' => 'Harga Satuan',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi',
            'string' => ':attribute harus berupa teks',
            'integer' => ':attribute harus berupa angka',
            'numeric' => ':attribute harus berupa angka',
            'in' => ':attribute tidak valid',
            'max' => ':attribute maksimal :max karakter',
            'min' => ':attribute minimal :min',
            'array' => ':attribute harus berupa array',
            'koefisien.max' => 'Maksimal 4 koefisien',
            'satuan_koefisien.max' => 'Maksimal 4 satuan koefisien',
        ];
    }
}
