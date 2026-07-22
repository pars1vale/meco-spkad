<?php

namespace App\Http\Requests\Rkpd;

use Illuminate\Foundation\Http\FormRequest;

class StorePaketBelanjaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_rinci_sub_bl' => 'required|integer',
            'tipe_paket' => 'required|in:1,2',
            'uraian_paket' => 'required|string|max:1000',
            'jenis_bl' => 'required|string',
            'id_akun' => 'required|integer|exists:akun,id'
        ];
    }

    public function messages(): array
    {
        return [
            'id_rinci_sub_bl.required' => 'Sub kegiatan harus dipilih',
            'tipe_paket.required' => 'Tipe paket harus dipilih',
            'tipe_paket.in' => 'Tipe paket tidak valid (harus 1 atau 2)',
            'uraian_paket.required' => 'Uraian paket harus diisi',
            'jenis_bl.required' => 'Jenis belanja harus dipilih',
            'id_akun.required' => 'Akun rekening harus dipilih',
            'id_akun.exists' => 'Akun rekening tidak valid'
        ];
    }
}
