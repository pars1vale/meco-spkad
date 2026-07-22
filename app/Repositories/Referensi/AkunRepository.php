<?php

namespace App\Repositories\Referensi;

use App\Models\Referensi\Akun;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Repository untuk Akun Rekening
 */
class AkunRepository
{
    /**
     * Find akun by ID
     */
    public function findById(int $id): ?Akun
    {
        return Akun::find($id);
    }

    /**
     * Find akun by ID or fail
     */
    public function findByIdOrFail(int $id): Akun
    {
        return Akun::findOrFail($id);
    }

    /**
     * Get all active akun
     */
    public function getAllActive(): Collection
    {
        return Akun::where('active', 1)
            ->orderBy('kode_akun')
            ->get();
    }

    /**
     * Get akun by tahun anggaran
     */
    public function getByTahunAnggaran(int $tahunAnggaran): Collection
    {
        return Akun::where('active', 1)
            ->where('tahun_anggaran', $tahunAnggaran)
            ->orderBy('kode_akun')
            ->get();
    }

    /**
     * Get akun by jenis belanja
     */
    public function getByJenisBelanja(string $field, int $tahunAnggaran): Collection
    {
        return Akun::where('tahun_anggaran', $tahunAnggaran)
            ->where('active', 1)
            ->where($field, 1)
            ->where('set_input', 1)
            ->orderBy('kode_akun')
            ->get(['id', 'kode_akun', 'nama_akun', 'level']);
    }

    /**
     * Create new akun
     */
    public function create(array $data): Akun
    {
        return Akun::create($data);
    }

    /**
     * Update akun
     */
    public function update(Akun $akun, array $data): bool
    {
        return $akun->update($data);
    }

    /**
     * Soft delete akun (set active = 0)
     */
    public function softDelete(Akun $akun): bool
    {
        $akun->active = false;
        return $akun->save();
    }

    /**
     * Restore akun (set active = 1)
     */
    public function restore(Akun $akun): bool
    {
        $akun->active = true;
        return $akun->save();
    }

    /**
     * Bulk soft delete
     */
    public function bulkSoftDelete(array $ids): int
    {
        return Akun::whereIn('id', $ids)
            ->where('is_locked', 0)
            ->update(['active' => false]);
    }

    /**
     * Get datatables data
     */
    public function getDatatablesData(array $params)
    {
        $query = Akun::where('active', 1);

        // Search
        if (!empty($params['search'])) {
            $search = $params['search'];
            $query->where(function ($q) use ($search) {
                $q->where('kode_akun', 'LIKE', "%{$search}%")
                    ->orWhere('nama_akun', 'LIKE', "%{$search}%")
                    ->orWhere('ket_akun', 'LIKE', "%{$search}%");
            });
        }

        // Total filtered
        $totalFiltered = $query->count();

        // Sorting
        if (!empty($params['order']) && !empty($params['dir'])) {
            $query->orderBy($params['order'], $params['dir']);
        }

        // Pagination
        if (isset($params['start']) && isset($params['limit'])) {
            $query->offset($params['start'])->limit($params['limit']);
        }

        return [
            'data' => $query->get(),
            'total_filtered' => $totalFiltered
        ];
    }

    /**
     * Get statistics
     */
    public function getStatistics(int $tahunAnggaran): array
    {
        return [
            'total' => Akun::active()->byTahun($tahunAnggaran)->count(),
            'pendapatan' => Akun::active()->byTahun($tahunAnggaran)->pendapatan()->count(),
            'belanja' => Akun::active()->byTahun($tahunAnggaran)->belanja()->count(),
            'pembiayaan' => Akun::active()->byTahun($tahunAnggaran)->pembiayaan()->count(),
            'bos' => Akun::active()->byTahun($tahunAnggaran)->bos()->count(),
            'gaji_asn' => Akun::active()->byTahun($tahunAnggaran)->gajiAsn()->count(),
            'hibah' => Akun::active()->byTahun($tahunAnggaran)->hibah()->count(),
            'bansos' => Akun::active()->byTahun($tahunAnggaran)->bansos()->count(),
        ];
    }

    /**
     * Check if kode_akun exists (for validation)
     */
    public function kodeAkunExists(string $kodeAkun, ?int $exceptId = null): bool
    {
        $query = Akun::where('kode_akun', $kodeAkun);

        if ($exceptId) {
            $query->where('id', '!=', $exceptId);
        }

        return $query->exists();
    }

    /**
     * Count active akun
     */
    public function countActive(): int
    {
        return Akun::where('active', 1)->count();
    }
}
