<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use App\Models\Referensi\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgramController extends Controller
{
    public function index()
    {
        $data = collect();
        try {
            $programs = Program::with(['bidangUrusan.urusan'])
                ->join('bidang_urusan', 'program.id_bidang_urusan', '=', 'bidang_urusan.id')
                ->join('urusan', 'bidang_urusan.id_urusan', '=', 'urusan.id')
                ->select([
                    'program.*',
                    'bidang_urusan.nama_bidang_urusan',
                    'bidang_urusan.kode_bidang_urusan',
                    'urusan.nama_urusan',
                    'urusan.kode_urusan',
                    'urusan.id as id_urusan',
                    'bidang_urusan.id as id_bidang_urusan'
                ])
                ->orderBy('urusan.kode_urusan', 'asc')
                ->orderBy('bidang_urusan.kode_bidang_urusan', 'asc')
                ->orderBy('program.kode_program', 'asc')
                ->get();

            // Group by id_urusan untuk struktur yang sesuai dengan view
            $data = $programs->groupBy('id_urusan')->map(function ($group) {
                // Sort setiap group berdasarkan kode_bidang_urusan dan kode_program
                return $group->sortBy([
                    ['kode_bidang_urusan', 'asc'],
                    ['kode_program', 'asc']
                ]);
            });
        } catch (\Exception $e) {
            \Log::error('Error fetching program data: ' . $e->getMessage());

            // Return empty data dengan error message untuk ditampilkan di view
            $data = collect();
            $stats = [
                'total_program' => 0,
                'total_bidang_urusan' => 0,
                'total_urusan' => 0,
                'duplicate_codes' => collect(),
                'error' => 'Gagal memuat data program: ' . $e->getMessage()
            ];
        }

        return view('referensi.program.index', compact('data'));
    }
}
