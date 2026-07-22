<?php

namespace App\Http\Requests\Rkpd;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request untuk Store RENJA
 */
class StoreRenjaRequest extends FormRequest
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

/**
 * Request untuk Store Paket Belanja
 */
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
            'tipe_paket.in' => 'Tipe paket tidak valid',
            'uraian_paket.required' => 'Uraian paket harus diisi',
            'jenis_bl.required' => 'Jenis belanja harus dipilih',
            'id_akun.required' => 'Akun rekening harus dipilih',
            'id_akun.exists' => 'Akun rekening tidak valid'
        ];
    }
}

/**
 * Request untuk Store Rincian Belanja
 */
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
            'id_paket_belanja' => 'nullable|integer',
            'uraian' => 'required|string|max:1000',
            'volume' => 'required|numeric|min:0',
            'satuan' => 'required|string|max:100',
            'harga_satuan' => 'required|numeric|min:0'
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
            'harga_satuan.numeric' => 'Harga satuan harus berupa angka'
        ];
    }
}
