<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Session;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    // Setelah login → wajib pilih tahun anggaran dulu
    protected $redirectTo = '/tahun-anggaran/pilih';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /** Tampilkan halaman login */
    public function login()
    {
        return view('auth.login');
    }

    /** Proses login dengan username ATAU NIP */
    public function authenticate(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username atau NIP wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        try {
            $login = $request->username;
            $password = $request->password;

            // Cari user berdasarkan username ATAU nip
            $user = User::where('username', $login)
                ->orWhere('nip', $login)
                ->first();

            if (! $user) {
                Toastr::error('Username / NIP tidak ditemukan.', 'Error');

                return redirect()->back()->withInput();
            }

            // Coba auth menggunakan username yang ditemukan
            if (Auth::attempt(['username' => $user->username, 'password' => $password])) {
                $dt = Carbon::now();
                $authUser = Auth::user();

                // Simpan session user
                Session::put('user_id', $authUser->id);
                Session::put('username', $authUser->username);
                Session::put('nip', $authUser->nip);

                // Update last login
                User::where('id', $authUser->id)->update([
                    'last_login' => $dt->toDayDateTimeString(),
                ]);

                Toastr::success('Login berhasil! Silakan pilih tahun anggaran.', 'Success');

                return redirect()->intended('/tahun-anggaran/pilih');
            } else {
                Toastr::error('Password salah.', 'Error');

                return redirect()->back()->withInput();
            }
        } catch (\Exception $e) {
            \Log::error($e);
            Toastr::error('Terjadi kesalahan saat login.', 'Error');

            return redirect()->back();
        }
    }

    /** Logout dan hapus semua session */
    public function logout(Request $request)
    {
        Session::flush();
        Auth::logout();
        Toastr::success('Logout berhasil.', 'Success');

        return redirect('login');
    }
}
