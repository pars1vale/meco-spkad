<?php

namespace App\Services\Referensi;

use App\Models\Referensi\Akun;
use App\Repositories\Referensi\AkunRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class AkunService
{
    protected AkunRepository $repository;

    public function __construct(AkunRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get datatables data for listing
     */
    public function getDatatablesData(array $request): array
    {
        $columns = [
            0 => 'id',
            1 => 'kode_akun',
            2 => 'nama_akun',
            3 => 'pendapatan',
            4 => 'belanja',
            5 => 'pembiayaan',
        ];

        $totalData = $this->repository->countActive();

        $params = [
            'search' => $request['search']['value'] ?? null,
            'order' => $columns[$request['order'][0]['column']] ?? 'kode_akun',
            'dir' => $request['order'][0]['dir'] ?? 'asc',
            'start' => $request['start'] ?? 0,
            'limit' => $request['length'] ?? 10,
        ];

        $result = $this->repository->getDatatablesData($params);

        // Format data for DataTables
        $data = [];
        foreach ($result['data'] as $akun) {
            $data[] = [
                'id' => $akun->id,
                'kode_akun' => $akun->kode_akun,
                'nama_akun' => $akun->nama_akun,
                'ket_akun' => $akun->ket_akun,
                'is_pendapatan' => $akun->is_pendapatan,
                'is_bl' => $akun->is_bl,
                'is_pembiayaan' => $akun->is_pembiayaan,
                'pendapatan' => $akun->is_pendapatan ? 'Ya' : 'Tidak',
                'belanja' => $akun->is_bl ? 'Ya' : 'Tidak',
                'pembiayaan' => $akun->is_pembiayaan ? 'Ya' : 'Tidak',
            ];
        }

        return [
            'draw' => intval($request['draw']),
            'recordsTotal' => intval($totalData),
            'recordsFiltered' => intval($result['total_filtered']),
            'data' => $data
        ];
    }

    /**
     * Create new akun
     */
    public function create(array $data): Akun
    {
        DB::beginTransaction();
        try {
            // Calculate level from kode_akun
            $level = strlen(str_replace([' ', '.', '-'], '', $data['kode_akun']));

            $akunData = [
                'kode_akun' => $data['kode_akun'],
                'nama_akun' => $data['nama_akun'],
                'ket_akun' => $data['keterangan_akun'] ?? null,
                'level' => $level,
                // Tipe utama
                'is_pendapatan' => $data['is_pendapatan'] ?? false,
                'is_bl' => $data['is_bl'] ?? false,
                'is_pembiayaan' => $data['is_pembiayaan'] ?? false,
                // Kategori khusus
                'is_bos' => $data['is_bos'] ?? false,
                'is_gaji_asn' => $data['is_gaji_asn'] ?? false,
                'is_barjas' => $data['is_barjas'] ?? false,
                'is_btt' => $data['is_btt'] ?? false,
                'is_hibah_uang' => $data['is_hibah_uang'] ?? false,
                'is_hibah_brg' => $data['is_hibah_brg'] ?? false,
                'is_sosial_uang' => $data['is_sosial_uang'] ?? false,
                'is_sosial_brg' => $data['is_sosial_brg'] ?? false,
                'is_subsidi' => $data['is_subsidi'] ?? false,
                'is_bagi_hasil' => $data['is_bagi_hasil'] ?? false,
                'is_bunga' => $data['is_bunga'] ?? false,
                'is_modal_tanah' => $data['is_modal_tanah'] ?? false,
                'is_bankeu_umum' => $data['is_bankeu_umum'] ?? false,
                'is_bankeu_khusus' => $data['is_bankeu_khusus'] ?? false,
                // Default values
                'active' => true,
                'tahun_anggaran' => date('Y'),
            ];

            $akun = $this->repository->create($akunData);

            DB::commit();

            Log::info('Akun created successfully', ['id' => $akun->id, 'kode' => $akun->kode_akun]);

            return $akun;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to create akun', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Update akun
     */
    public function update(int $id, array $data): Akun
    {
        DB::beginTransaction();
        try {
            $akun = $this->repository->findByIdOrFail($id);

            // Check if akun can be edited
            if (!$akun->canEdit()) {
                throw new Exception('Akun ini tidak dapat diedit karena terkunci atau tidak aktif');
            }

            // Calculate level from kode_akun if changed
            $level = strlen(str_replace([' ', '.', '-'], '', $data['kode_akun']));

            $akunData = [
                'kode_akun' => $data['kode_akun'],
                'nama_akun' => $data['nama_akun'],
                'ket_akun' => $data['keterangan_akun'] ?? null,
                'level' => $level,
                // Tipe utama
                'is_pendapatan' => $data['is_pendapatan'] ?? false,
                'is_bl' => $data['is_bl'] ?? false,
                'is_pembiayaan' => $data['is_pembiayaan'] ?? false,
                // Kategori khusus
                'is_bos' => $data['is_bos'] ?? false,
                'is_gaji_asn' => $data['is_gaji_asn'] ?? false,
                'is_barjas' => $data['is_barjas'] ?? false,
                'is_btt' => $data['is_btt'] ?? false,
                'is_hibah_uang' => $data['is_hibah_uang'] ?? false,
                'is_hibah_brg' => $data['is_hibah_brg'] ?? false,
                'is_sosial_uang' => $data['is_sosial_uang'] ?? false,
                'is_sosial_brg' => $data['is_sosial_brg'] ?? false,
                'is_subsidi' => $data['is_subsidi'] ?? false,
                'is_bagi_hasil' => $data['is_bagi_hasil'] ?? false,
                'is_bunga' => $data['is_bunga'] ?? false,
                'is_modal_tanah' => $data['is_modal_tanah'] ?? false,
                'is_bankeu_umum' => $data['is_bankeu_umum'] ?? false,
                'is_bankeu_khusus' => $data['is_bankeu_khusus'] ?? false,
            ];

            $this->repository->update($akun, $akunData);

            DB::commit();

            Log::info('Akun updated successfully', ['id' => $akun->id, 'kode' => $akun->kode_akun]);

            return $akun->fresh();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to update akun', ['id' => $id, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Delete akun (soft delete)
     */
    public function delete(int $id): bool
    {
        DB::beginTransaction();
        try {
            $akun = $this->repository->findByIdOrFail($id);

            // Check if akun can be deleted
            if (!$akun->canDelete()) {
                throw new Exception('Akun ini tidak dapat dihapus karena terkunci atau tidak aktif');
            }

            $result = $this->repository->softDelete($akun);

            DB::commit();

            Log::info('Akun deleted successfully', ['id' => $akun->id, 'kode' => $akun->kode_akun]);

            return $result;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete akun', ['id' => $id, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Bulk delete akun
     */
    public function bulkDelete(array $ids): int
    {
        DB::beginTransaction();
        try {
            $count = $this->repository->bulkSoftDelete($ids);

            DB::commit();

            Log::info('Bulk delete akun successfully', ['count' => $count]);

            return $count;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to bulk delete akun', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Restore deleted akun
     */
    public function restore(int $id): bool
    {
        DB::beginTransaction();
        try {
            $akun = $this->repository->findById($id);

            if (!$akun) {
                throw new Exception('Data akun tidak ditemukan');
            }

            $result = $this->repository->restore($akun);

            DB::commit();

            Log::info('Akun restored successfully', ['id' => $akun->id, 'kode' => $akun->kode_akun]);

            return $result;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to restore akun', ['id' => $id, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Get akun detail
     */
    public function getDetail(int $id): array
    {
        $akun = $this->repository->findByIdOrFail($id);

        return [
            'id' => $akun->id,
            'id_akun' => $akun->id_akun,
            'kode_akun' => $akun->kode_akun,
            'nama_akun' => $akun->nama_akun,
            'ket_akun' => $akun->ket_akun,
            'tahun_anggaran' => $akun->tahun_anggaran,
            'level' => $akun->level,
            'active' => $akun->active,
            'is_locked' => $akun->is_locked,
            // Tipe utama
            'is_pendapatan' => $akun->is_pendapatan,
            'is_bl' => $akun->is_bl,
            'is_pembiayaan' => $akun->is_pembiayaan,
            // Kategori khusus
            'kategori_khusus' => $akun->getKategoriKhusus(),
            // Timestamps
            'created_at' => $akun->created_at ? $akun->created_at->format('d/m/Y H:i:s') : null,
            'updated_at' => $akun->updated_at ? $akun->updated_at->format('d/m/Y H:i:s') : null,
        ];
    }

    /**
     * Get statistics
     */
    public function getStatistics(?int $tahunAnggaran = null): array
    {
        $tahunAnggaran = $tahunAnggaran ?? date('Y');
        return $this->repository->getStatistics($tahunAnggaran);
    }

    /**
     * Get akun for edit form
     */
    public function getForEdit(int $id): Akun
    {
        $akun = $this->repository->findByIdOrFail($id);

        if (!$akun->canEdit()) {
            throw new Exception('Akun ini tidak dapat diedit karena terkunci atau tidak aktif');
        }

        return $akun;
    }
}
