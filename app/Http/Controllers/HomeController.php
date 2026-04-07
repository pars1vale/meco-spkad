<?php

namespace App\Http\Controllers;

use App\Models\Pengaturan\Profil\PerangkatDaerah\DataUnit;
use App\Models\Referensi\Akun;
use App\Models\Referensi\Kegiatan;
use App\Models\Referensi\Program;
use App\Models\Referensi\SubKegiatan;
use App\Models\Rkpd\DataRka;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $totalProgram = Program::count();
        $totalKegiatan = Kegiatan::count();
        $totalSubKegiatan = SubKegiatan::count();
        $totalSkpd = DataUnit::where('status', 'SKPD')->count();
        $totalUnitSkpd = DataUnit::where('status', 'Unit SKPD')->count();
        $totalRekPendapatan = Akun::where('is_pendapatan', '1')->count();
        $totalRekBelanja = Akun::where('is_bl', '1')->count();
        $totalRekPembiayaan = Akun::where('is_pembiayaan', '1')->count();
        $totalBelanjaHibah = DataRka::where('jenis_bl', 'HIBAH')->sum('total_harga');
        $totalBelanjaBansos = DataRka::where('jenis_bl', 'BANSOS')->sum('total_harga');
        $totalBelanjaBankeu = DataRka::where('jenis_bl', 'BANKEU')->sum('total_harga');

        return view(
            'dashboard.home',
            compact(
                'totalProgram',
                'totalKegiatan',
                'totalSubKegiatan',
                'totalSkpd',
                'totalUnitSkpd',
                'totalRekPendapatan',
                'totalRekBelanja',
                'totalRekPembiayaan',
                'totalBelanjaHibah',
                'totalBelanjaBansos',
                'totalBelanjaBankeu'
            )
        );
    }
}
