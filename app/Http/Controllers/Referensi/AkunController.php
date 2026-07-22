<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use App\Http\Requests\Referensi\StoreAkunRequest;
use App\Http\Requests\Referensi\UpdateAkunRequest;
use App\Services\Referensi\AkunService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Exception;

class AkunController extends Controller
{
    protected AkunService $akunService;

    public function __construct(AkunService $akunService)
    {
        $this->akunService = $akunService;
    }

    /**
     * Display listing page
     */
    public function index(): View
    {
        return view('referensi.akun.index');
    }

    /**
     * Get data for DataTables
     */
    public function getData(Request $request): JsonResponse
    {
        try {
            $data = $this->akunService->getDatatablesData($request->all());
            return response()->json($data);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Gagal memuat data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store new akun
     */
    public function store(StoreAkunRequest $request): JsonResponse
    {
        try {
            $akun = $this->akunService->create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Data akun berhasil ditambahkan',
                'data' => $akun
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan data akun: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show akun detail (encrypted ID)
     */
    public function show(string $id): View|RedirectResponse
    {
        try {
            $decryptedId = decrypt($id);
            $akun = $this->akunService->getForEdit($decryptedId);
            $akun->load('standarHarga');

            return view('referensi.akun.show', compact('akun'));
        } catch (Exception $e) {
            return redirect()
                ->route('referensi.akun.index')
                ->with('error', 'Data akun tidak ditemukan');
        }
    }

    /**
     * Get akun detail for modal (AJAX)
     */
    public function detail(int $id): JsonResponse
    {
        try {
            $data = $this->akunService->getDetail($id);

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data akun tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Show edit form
     */
    public function edit(int $id): View|RedirectResponse
    {
        try {
            $akun = $this->akunService->getForEdit($id);
            return view('referensi.akun.edit', compact('akun'));
        } catch (Exception $e) {
            return redirect()
                ->route('referensi.akun.index')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Update akun
     */
    public function update(UpdateAkunRequest $request, int $id): RedirectResponse
    {
        try {
            $this->akunService->update($id, $request->validated());

            return redirect()
                ->route('referensi.akun.index')
                ->with('success', 'Data akun berhasil diperbarui');
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Delete akun (soft delete)
     */
    public function destroy(int $id): RedirectResponse
    {
        try {
            $this->akunService->delete($id);

            return redirect()
                ->route('referensi.akun.index')
                ->with('success', 'Data akun berhasil dihapus');
        } catch (Exception $e) {
            return redirect()
                ->route('referensi.akun.index')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Bulk delete akun
     */
    public function bulkDelete(Request $request): RedirectResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:akun,id',
        ]);

        try {
            $count = $this->akunService->bulkDelete($request->ids);

            return redirect()
                ->route('referensi.akun.index')
                ->with('success', "Berhasil menghapus {$count} data akun");
        } catch (Exception $e) {
            return redirect()
                ->route('referensi.akun.index')
                ->with('error', 'Gagal menghapus data akun: ' . $e->getMessage());
        }
    }

    /**
     * Restore deleted akun
     */
    public function restore(int $id): RedirectResponse
    {
        try {
            $this->akunService->restore($id);

            return redirect()
                ->route('referensi.akun.index')
                ->with('success', 'Data akun berhasil dipulihkan');
        } catch (Exception $e) {
            return redirect()
                ->route('referensi.akun.index')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Get statistics
     */
    public function statistics(Request $request): JsonResponse
    {
        try {
            $tahunAnggaran = $request->get('tahun_anggaran');
            $stats = $this->akunService->getStatistics($tahunAnggaran);

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat statistik: ' . $e->getMessage()
            ], 500);
        }
    }
}
