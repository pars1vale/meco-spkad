<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use App\Models\Referensi\Urusan;
use Illuminate\Http\Request;

class UrusanController extends Controller
{
    public function index()
    {
        $data = Urusan::all();
        return view('referensi.urusan.index', compact('data'));
    }
}
