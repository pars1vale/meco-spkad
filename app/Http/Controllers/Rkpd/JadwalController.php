<?php

namespace App\Http\Controllers\Rkpd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Rkpd\JadwalRkpd;
use App\Models\Rkpd\SubTahapPenjadwalan;
use Illuminate\Support\Str;

class JadwalController extends Controller
{
    public function index()
    {
        $data = JadwalRkpd::with(['subTahap.tahap'])->orderBy('tahun', 'desc')->get();
        $subTahap = SubTahapPenjadwalan::with('tahap')->get();

        return view('rkpd.jadwal-rkpd.index', compact('data', 'subTahap'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_sub_tahap' => 'required|exists:sub_tahap_penjadwalan,id_sub_tahap',
            'waktu_mulai'  => 'required|date',
            'waktu_selesai'=> 'required|date|after_or_equal:waktu_mulai',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        JadwalRkpd::create([
            'id_unik'       => Str::uuid(),
            'tahun'         => date('Y'),
            'id_daerah'     => auth()->user()->id_daerah ?? 1,
            'id_sub_tahap'  => $request->id_sub_tahap,
            'waktu_mulai'   => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
            'created_user'  => auth()->id(),
            'updated_user'  => auth()->id(),
        ]);

        return redirect()->route('rkpd.jadwal-rkpd.index')->with('success', 'Jadwal RKPD berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $jadwal   = JadwalRkpd::with(['subTahap.tahap'])->findOrFail($id);
        $subTahap = SubTahapPenjadwalan::with('tahap')->get();

        return view('rkpd.jadwal-rkpd.edit', compact('jadwal', 'subTahap'));
    }

    public function update(Request $request, $id)
    {
        $jadwal = JadwalRkpd::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'id_sub_tahap' => 'required|exists:sub_tahap_penjadwalan,id_sub_tahap',
            'waktu_mulai'  => 'required|date',
            'waktu_selesai'=> 'required|date|after_or_equal:waktu_mulai',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $jadwal->update([
            'id_sub_tahap'  => $request->id_sub_tahap,
            'waktu_mulai'   => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
            'updated_user'  => auth()->id(),
        ]);

        return redirect()->route('rkpd.jadwal-rkpd.index')->with('success', 'Jadwal RKPD berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $jadwal = JadwalRkpd::findOrFail($id);
        $jadwal->delete();

        return redirect()->route('rkpd.jadwal-rkpd.index')->with('success', 'Jadwal RKPD berhasil dihapus.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return response()->json(['status' => 'error', 'message' => 'Tidak ada data yang dipilih.'], 400);
        }

        JadwalRkpd::whereIn('id_jadwal', $ids)->delete();

        return response()->json(['status' => 'success', 'message' => 'Data Jadwal RKPD berhasil dihapus.']);
    }
}
