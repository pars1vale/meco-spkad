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
    public function findById(int $id): ?Akun
    {
        return Akun::find($id);
    }

    public function findByIdOrFail(int $id): Akun
    {
        return Akun::findOrFail($id);
    }

    public function getAllActive(): Collection
    {
        return Akun::where('active', 1)
            ->orderBy('kode_akun')
            ->get();
    }

    public function getByTahunAnggaran(int $tahunAnggaran): Collection
    {
        return Akun::where('active', 1)
            ->where('tahun_anggaran', $tahunAnggaran)
            ->orderBy('kode_akun')
            ->get();
    }

    public function getByJenisBelanja(string $field, int $tahunAnggaran): Collection
    {
        return Akun::where('tahun_anggaran', $tahunAnggaran)
            ->where('active', 1)
            ->where($field, 1)
            ->where('set_input', 1)
            ->orderBy('kode_akun')
            ->get(['id', 'kode_akun', 'nama_akun', 'level']);
    }

    public function create(array $data): Akun
    {
        return Akun::create($data);
    }

    public function update(Akun $akun, array $data): bool
    {
        return $akun->update($data);
    }

    public function softDelete(Akun $akun): bool
    {
        $akun->active = false;
        return $akun->save();
    }

    public function restore(Akun $akun): bool
    {
        $akun->active = true;
        return $akun->save();
    }

    public function bulkSoftDelete(array $ids): int
    {
        return Akun::whereIn('id', $ids)
            ->where('is_locked', 0)
            ->update(['active' => false]);
    }

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

    public function countActive(): int
    {
        return Akun::where('active', 1)->count();
    }

    /**
     * Get akun berdasarkan daftar kode_akun (untuk hierarki header rekening cetakan)
     */
    public function getByKodeList(array $kodeList, int $tahunAnggaran): Collection
    {
        return Akun::where('tahun_anggaran', $tahunAnggaran)
            ->where('active', 1)
            ->whereIn('kode_akun', $kodeList)
            ->get(['kode_akun', 'nama_akun'])
            ->keyBy('kode_akun');
    }
}
