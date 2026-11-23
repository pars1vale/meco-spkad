<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class GenericTableApiController extends Controller
{
    // ⚠️ WHITELIST — cuma tabel yang didaftarkan di sini yang bisa diakses
    private array $allowedTables = [
        'akun',
        'data_bulan',
        'data_daerah',
        'data_kecamatan',
        'data_kelurahan',
        'data_satuan',
        // tambahkan tabel lain yang memang boleh diakses lintas sistem
    ];

    public function index(Request $request, string $table)
    {
        if (!in_array($table, $this->allowedTables)) {
            return response()->json([
                'success' => false,
                'message' => "Tabel '{$table}' tidak diizinkan diakses via API.",
            ], 403);
        }

        if (!Schema::hasTable($table)) {
            return response()->json([
                'success' => false,
                'message' => "Tabel '{$table}' tidak ditemukan.",
            ], 404);
        }

        $perPage = $request->integer('per_page', 50);
        $page = $request->integer('page', 1);

        $query = DB::table($table);

        // Filter dinamis lewat query string, misal ?tahun=2026
        foreach ($request->except(['per_page', 'page']) as $key => $value) {
            if (Schema::hasColumn($table, $key)) {
                $query->where($key, $value);
            }
        }

        $result = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'success' => true,
            'table' => $table,
            'total' => $result->total(),
            'per_page' => $result->perPage(),
            'current_page' => $result->currentPage(),
            'data' => $result->items(),
        ]);
    }
}