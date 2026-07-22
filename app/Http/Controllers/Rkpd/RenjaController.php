<?php

namespace App\Http\Controllers\Rkpd;

use App\Http\Controllers\Controller;
use App\Http\Requests\Rkpd\StoreRenjaRequest;
use App\Http\Requests\Rkpd\UpdateRenjaRequest;
use App\Services\Rkpd\RenjaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RenjaController extends Controller
{
    protected $renjaService;

    public function __construct(RenjaService $renjaService)
    {
        $this->renjaService = $renjaService;
    }

    public function index()
    {
        try {
            $viewData = $this->renjaService->getIndexData();

            return view('rkpd.renja.index', $viewData);
        } catch (\Exception $e) {
            Log::error('Error loading RENJA index: '.$e->getMessage());

            return redirect()->back()->with('error', 'Gagal memuat halaman RENJA');
        }
    }

    public function store(StoreRenjaRequest $request)
    {
        try {
            $result = $this->renjaService->createRenja($request->validated());

            return redirect()->route('rkpd.renja.index')
                ->with('success', $result['message']);
        } catch (\Exception $e) {
            Log::error('Error storing renja: '.$e->getMessage());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: '.$e->getMessage())
                ->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $editData = $this->renjaService->getEditData($id);

            if (! $editData['subKegiatan']) {
                return redirect()->route('rkpd.renja.index')
                    ->with('error', 'Sub kegiatan tidak ditemukan');
            }

            return view('rkpd.renja.edit', $editData);
        } catch (\Exception $e) {
            Log::error('Error loading edit page: '.$e->getMessage());

            return redirect()->route('rkpd.renja.index')
                ->with('error', 'Gagal memuat halaman edit');
        }
    }

    public function update(UpdateRenjaRequest $request, $id)
    {
        try {
            $result = $this->renjaService->updateRenja($id, $request->validated());

            return redirect()->route('rkpd.renja.index')
                ->with('success', $result['message']);
        } catch (\Exception $e) {
            Log::error('Error updating RENJA: '.$e->getMessage());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: '.$e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $result = $this->renjaService->deleteRenja($id);

            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('Error deleting RENJA: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function getSubKegiatanBySkpd(Request $request)
    {
        try {
            $idSkpd = $request->input('id_skpd');
            $tahunAnggaran = $request->input('tahun_anggaran', 2025);

            $result = $this->renjaService->getSubKegiatanBySkpd($idSkpd, $tahunAnggaran);

            return response()->json([
                'success' => true,
                'data' => $result['data'],
                'count' => $result['count'],
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting sub kegiatan: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getData(Request $request)
    {
        try {
            $result = $this->renjaService->getDataTableData($request->all());

            return response()->json([
                'draw' => intval($request->draw ?? 1),
                'recordsTotal' => $result['recordsTotal'],
                'recordsFiltered' => $result['recordsFiltered'],
                'data' => $result['data'],
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting data: '.$e->getMessage());

            return response()->json([
                'draw' => intval($request->draw ?? 1),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => $e->getMessage(),
            ]);
        }
    }
}
