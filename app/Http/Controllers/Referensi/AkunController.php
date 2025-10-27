<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use App\Models\Referensi\Akun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AkunController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Akun::orderBy('kode_akun')->get();
        return view('referensi.akun.index', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = $this->validateAkun($request);

        // Validasi tipe akun
        $types = $this->getAkunTypes($request);
        if (!$this->hasAnyTypeSelected($types)) {
            return $this->handleValidationError(
                $validator,
                'tipe_akun',
                'Minimal satu tipe akun harus dipilih',
                $request
            );
        }

        if ($validator->fails()) {
            return $this->handleValidationError($validator, null, 'Data gagal disimpan. Periksa kembali input Anda.', $request);
        }

        try {
            $akun = $this->createOrUpdateAkun(new Akun(), $request, $types);
            $akun->id = Akun::getNextId();
            $akun->save();

            return $this->successResponse($request, 'Data akun berhasil ditambahkan', $akun);
        } catch (\Exception $e) {
            return $this->errorResponse($request, $e);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $akun = Akun::findOrFail($id);
        return view('referensi.akun.edit', compact('akun'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $akun = Akun::findOrFail($id);
        $validator = $this->validateAkun($request, $id);

        // Validasi tipe akun
        $types = $this->getAkunTypes($request);
        if (!$this->hasAnyTypeSelected($types)) {
            return redirect()->back()
                ->withErrors(['tipe_akun' => 'Minimal satu tipe akun harus dipilih'])
                ->withInput()
                ->with('error', 'Minimal satu tipe akun harus dipilih.');
        }

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Data gagal diperbarui. Periksa kembali input Anda.');
        }

        try {
            $this->createOrUpdateAkun($akun, $request, $types);
            $akun->save();

            return redirect()->route('referensi.akun.index')
                ->with('success', 'Data akun berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $akun = Akun::findOrFail($id);
            $nama_akun = $akun->nama_akun;
            $akun->delete();

            return redirect()->route('referensi.akun.index')
                ->with('success', "Data akun '{$nama_akun}' berhasil dihapus");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Remove multiple resources from storage.
     */
    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array|min:1',
            'ids.*' => 'required|integer|exists:akun,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Data yang dipilih tidak valid');
        }

        try {
            $deletedCount = Akun::whereIn('id', $request->ids)->delete();

            return redirect()->route('referensi.akun.index')
                ->with('success', "{$deletedCount} data akun berhasil dihapus");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Validate akun data
     */
    private function validateAkun(Request $request, $id = null)
    {
        $uniqueRule = $id ? "unique:akun,kode_akun,{$id}" : 'unique:akun,kode_akun';

        return Validator::make($request->all(), [
            'kode_akun' => "required|string|max:255|{$uniqueRule}",
            'nama_akun' => 'required|string',
            'keterangan_akun' => 'nullable|string',
        ], [
            'kode_akun.required' => 'Kode akun wajib diisi',
            'kode_akun.unique' => 'Kode akun sudah ada',
            'nama_akun.required' => 'Nama akun wajib diisi',
        ]);
    }

    /**
     * Get akun types from request
     */
    private function getAkunTypes(Request $request)
    {
        return [
            'is_pendapatan' => $request->has('is_pendapatan') ? 1 : 0,
            'is_belanja' => $request->has('is_belanja') ? 1 : 0,
            'is_pembiayaan' => $request->has('is_pembiayaan') ? 1 : 0,
        ];
    }

    /**
     * Check if any type is selected
     */
    private function hasAnyTypeSelected(array $types)
    {
        return array_sum($types) > 0;
    }

    /**
     * Create or update akun instance
     */
    private function createOrUpdateAkun(Akun $akun, Request $request, array $types)
    {
        $akun->kode_akun = $request->kode_akun;
        $akun->nama_akun = $request->nama_akun;
        $akun->keterangan_akun = $request->keterangan_akun;

        // Set boolean flags
        $akun->is_pendapatan = $types['is_pendapatan'];
        $akun->is_belanja = $types['is_belanja'];
        $akun->is_pembiayaan = $types['is_pembiayaan'];

        // Set text fields
        $akun->pendapatan = $types['is_pendapatan'] ? 'Ya' : 'Tidak';
        $akun->belanja = $types['is_belanja'] ? 'Ya' : 'Tidak';
        $akun->pembiayaan = $types['is_pembiayaan'] ? 'Ya' : 'Tidak';

        return $akun;
    }

    /**
     * Handle validation error response
     */
    private function handleValidationError($validator, $errorKey, $errorMessage, Request $request)
    {
        if ($errorKey) {
            $validator->errors()->add($errorKey, $errorMessage);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        return redirect()->back()
            ->withErrors($validator)
            ->withInput()
            ->with('error', $errorMessage);
    }

    /**
     * Success response handler
     */
    private function successResponse(Request $request, $message, $data = null)
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $data
            ], 201);
        }

        return redirect()->route('referensi.akun.index')->with('success', $message);
    }

    /**
     * Error response handler
     */
    private function errorResponse(Request $request, \Exception $e)
    {
        \Log::error('Error in AkunController: ' . $e->getMessage());

        $message = 'Terjadi kesalahan saat menyimpan data';
        $error = config('app.debug') ? $e->getMessage() : 'Internal server error';

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message,
                'error' => $error
            ], 500);
        }

        return redirect()->back()
            ->withInput()
            ->with('error', "{$message}: {$e->getMessage()}");
    }
}
