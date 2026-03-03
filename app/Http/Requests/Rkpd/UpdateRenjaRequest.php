<?php

namespace App\Http\Requests\Rkpd;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRenjaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_skpd' => 'required|integer',
            'id_sub_kegiatan' => 'required|integer',
            'sumber_dana' => 'required|array|min:1',
            'sumber_dana.*.id_sumber_dana' => 'required|integer',
            'sumber_dana.*.pagu' => 'required|numeric|min:0',
            'indikator' => 'nullable|array',
            'indikator.*.id_indikator' => 'nullable|integer',
            'indikator.*.indikator_text' => 'nullable|string',
            'indikator.*.satuan' => 'nullable|string',
            'indikator.*.target' => 'required_with:indikator.*.indikator_text|string',
            'waktu_awal' => 'nullable|integer|min:1|max:12',
            'waktu_akhir' => 'nullable|integer|min:1|max:12',
            'pagu_n_depan' => 'nullable|numeric|min:0'
        ];
    }

    public function messages(): array
    {
        return [
            'id_skpd.required' => 'SKPD harus dipilih',
            'id_sub_kegiatan.required' => 'Sub Kegiatan harus dipilih',
            'sumber_dana.required' => 'Minimal 1 sumber dana harus ditambahkan',
            'sumber_dana.*.id_sumber_dana.required' => 'Sumber dana harus dipilih',
            'sumber_dana.*.pagu.required' => 'Pagu harus diisi',
            'sumber_dana.*.pagu.numeric' => 'Pagu harus berupa angka',
            'indikator.*.target.required_with' => 'Target indikator harus diisi',
        ];
    }
}
