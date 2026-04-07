<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Hash;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/login';

    public function __construct()
    {
        $this->middleware('guest');
    }

    /** Tampilkan halaman register */
    public function register()
    {
        return view('auth.register');
    }

    /** Simpan user baru */
    public function storeUser(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'nip' => ['required', 'string', 'max:50', 'regex:/^[0-9]+$/', 'unique:users,nip'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'nip.required' => 'NIP wajib diisi.',
            'nip.regex' => 'NIP hanya boleh berisi angka.',
            'nip.unique' => 'NIP sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        try {
            $dt = Carbon::now();

            $user = new User;
            $user->username = $request->username;
            $user->nip = $request->nip;
            $user->name = $request->username; // name ikut username
            $user->password = Hash::make($request->password);
            $user->save();

            Toastr::success('Akun berhasil dibuat, silakan login.', 'Success');

            return redirect('login');
        } catch (\Exception $e) {
            \Log::error($e);
            Toastr::error('Gagal membuat akun.', 'Error');

            return redirect()->back()->withInput();
        }
    }
}
