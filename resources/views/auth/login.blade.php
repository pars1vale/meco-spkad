@extends('layouts.app')
@section('content')
  <div class="d-flex flex-column flex-lg-row flex-column-fluid">
    <!--begin::Body-->
    <div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10 order-2 order-lg-1">
      <div class="d-flex flex-center flex-column flex-lg-row-fluid">
        <div class="w-lg-500px p-10">

          {{-- Flash error dari Toastr sudah handle, tapi tampilkan juga inline --}}
          @if ($errors->any())
            <div class="alert alert-danger mb-5">
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form class="form w-100" id="kt_sign_in_form" action="{{ route('authenticate') }}" method="POST">
            @csrf

            <!--begin::Heading-->
            <div class="text-center mb-11">
              <h1 class="text-gray-900 fw-bolder mb-3">LOGIN</h1>
            </div>

            <!--begin::Username / NIP-->
            <div class="fv-row mb-8">
              <input type="text" placeholder="Username atau NIP" name="username" value="{{ old('username') }}" autocomplete="off"
                class="form-control bg-transparent @error('username') is-invalid @enderror" />
              @error('username')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <!--begin::Password-->
            <div class="fv-row mb-3">
              <input type="password" placeholder="Password" name="password" autocomplete="off"
                class="form-control bg-transparent @error('password') is-invalid @enderror" />
              @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <!--begin::Submit-->
            <div class="d-grid mb-10 mt-6">
              <button type="submit" class="btn btn-primary">
                <span class="indicator-label">Sign In</span>
                <span class="indicator-progress">Please wait...
                  <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
              </button>
            </div>

            <div class="text-gray-500 text-center fw-semibold fs-6">
              Belum punya akun?
              <a href="{{ route('register') }}" class="link-primary">Daftar</a>
            </div>
          </form>

        </div>
      </div>
    </div>
    <!--end::Body-->

    <!--begin::Aside-->
    <div class="d-flex flex-lg-row-fluid w-lg-50 bgi-size-cover bgi-position-center order-1 order-lg-2"
      style="background-image: url(assets/media/misc/auth-bg.png)">
      <div class="d-flex flex-column flex-center py-7 py-lg-15 px-5 px-md-15 w-100">
        <img class="d-none d-lg-block mx-auto w-275px w-md-50 w-xl-500px mb-10 mb-lg-20" src="assets/media/misc/auth-screens.png" alt="" />
      </div>
    </div>
    <!--end::Aside-->
  </div>
@endsection

@push('scripts')
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      var typed = new Typed("#kt_typedjs_banner", {
        strings: [
          "Dashboard Analisis Anggaran.",
          "Monitoring Pendapatan Daerah.",
          "Transparansi Belanja Publik.",
          "Data Kemiskinan Ekstrem.",
          "Data Anggaran SPM.",
          "Jumlah Pendapatan.",
          "Jumlah Belanja.",
          "Jumlah Pembiayaan."
        ],
        typeSpeed: 45,
        backSpeed: 15,
        loop: true
      });
    });
  </script>
@endpush
