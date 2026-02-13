<?php

namespace App\Http\Requests\Rkpd;

use Illuminate\Foundation\Http\FormRequest;

class StoreRincianRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_rinci_sub_bl' => 'required|integer',
            'jenis_bl' => 'required|string',
            'id_akun' => 'required|integer|exists:akun,id',
            'kode_rekening' => 'required|string',
            'nama_rekening' => 'required|string',
            'tipe_paket' => 'required|integer|in:1,2',
            'id_paket_belanja' => 'required',
            'uraian' => 'required|string|max:1000',
            'volume' => 'required|numeric|min:0',
            'satuan' => 'required|string|max:100',
            'harga_satuan' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'id_rinci_sub_bl.required' => 'Sub kegiatan harus dipilih',
            'jenis_bl.required' => 'Jenis belanja harus dipilih',
            'id_akun.required' => 'Akun rekening harus dipilih',
            'id_akun.exists' => 'Akun rekening tidak valid',
            'tipe_paket.required' => 'Tipe paket harus dipilih',
            'uraian.required' => 'Uraian rincian harus diisi',
            'volume.required' => 'Volume harus diisi',
            'volume.numeric' => 'Volume harus berupa angka',
            'satuan.required' => 'Satuan harus diisi',
            'harga_satuan.required' => 'Harga satuan harus diisi',
            'harga_satuan.numeric' => 'Harga satuan harus berupa angka',
        ];
    }
}
