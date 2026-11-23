<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AkunApiController extends Controller
{
    public function index()
    {
        $data = DB::table('akun')->limit(10)->get();

        return response()->json([
            'success' => true,
            'total' => DB::table('akun')->count(),
            'data' => $data,
        ]);
    }
}