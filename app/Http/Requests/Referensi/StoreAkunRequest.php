<?php

namespace App\Http\Requests\Referensi;

use Illuminate\Foundation\Http\FormRequest;

class StoreAkunRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'kode_akun' => 'required|string|max:50|unique:akun,kode_akun',
            'nama_akun' => 'required|string|max:255',
            'keterangan_akun' => 'nullable|string|max:255',
            'is_pendapatan' => 'nullable|boolean',
            'is_bl' => 'nullable|boolean',
            'is_pembiayaan' => 'nullable|boolean',
            // Kategori khusus (optional)
            'is_bos' => 'nullable|boolean',
            'is_gaji_asn' => 'nullable|boolean',
            'is_barjas' => 'nullable|boolean',
            'is_btt' => 'nullable|boolean',
            'is_hibah_uang' => 'nullable|boolean',
            'is_hibah_brg' => 'nullable|boolean',
            'is_sosial_uang' => 'nullable|boolean',
            'is_sosial_brg' => 'nullable|boolean',
            'is_subsidi' => 'nullable|boolean',
            'is_bagi_hasil' => 'nullable|boolean',
            'is_bunga' => 'nullable|boolean',
            'is_modal_tanah' => 'nullable|boolean',
            'is_bankeu_umum' => 'nullable|boolean',
            'is_bankeu_khusus' => 'nullable|boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'kode_akun.required' => 'Kode Akun harus diisi',
            'kode_akun.unique' => 'Kode Akun sudah digunakan',
            'kode_akun.max' => 'Kode Akun maksimal 50 karakter',
            'nama_akun.required' => 'Nama Akun harus diisi',
            'nama_akun.max' => 'Nama Akun maksimal 255 karakter',
            'keterangan_akun.max' => 'Keterangan maksimal 255 karakter',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert checkbox values to boolean
        $this->merge([
            'is_pendapatan' => $this->boolean('is_pendapatan'),
            'is_bl' => $this->boolean('is_bl'),
            'is_pembiayaan' => $this->boolean('is_pembiayaan'),
            'is_bos' => $this->boolean('is_bos'),
            'is_gaji_asn' => $this->boolean('is_gaji_asn'),
            'is_barjas' => $this->boolean('is_barjas'),
            'is_btt' => $this->boolean('is_btt'),
            'is_hibah_uang' => $this->boolean('is_hibah_uang'),
            'is_hibah_brg' => $this->boolean('is_hibah_brg'),
            'is_sosial_uang' => $this->boolean('is_sosial_uang'),
            'is_sosial_brg' => $this->boolean('is_sosial_brg'),
            'is_subsidi' => $this->boolean('is_subsidi'),
            'is_bagi_hasil' => $this->boolean('is_bagi_hasil'),
            'is_bunga' => $this->boolean('is_bunga'),
            'is_modal_tanah' => $this->boolean('is_modal_tanah'),
            'is_bankeu_umum' => $this->boolean('is_bankeu_umum'),
            'is_bankeu_khusus' => $this->boolean('is_bankeu_khusus'),
        ]);
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Validasi: minimal satu tipe utama harus dipilih
            if (!$this->is_pendapatan && !$this->is_bl && !$this->is_pembiayaan) {
                $validator->errors()->add(
                    'tipe_akun',
                    'Anda harus memilih salah satu tipe akun utama (Pendapatan, Belanja, atau Pembiayaan)'
                );
            }
        });
    }
}
