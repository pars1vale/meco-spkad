<?php

namespace App\Http\Controllers\Rkpd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RenjaController extends Controller
{
    public function index()
    {
        
        return view('rkpd.renja.index');
    }
}
