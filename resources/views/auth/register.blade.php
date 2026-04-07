@extends('layouts.app')
@section('content')
  <div class="d-flex flex-column flex-lg-row flex-column-fluid">
    <!--begin::Body-->
    <div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10 order-2 order-lg-1">
      <div class="d-flex flex-center flex-column flex-lg-row-fluid">
        <div class="w-lg-500px p-10">

          @if ($errors->any())
            <div class="alert alert-danger mb-5">
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form class="form w-100" id="kt_sign_up_form" action="{{ route('storeUser') }}" method="POST">
            @csrf

            <!--begin::Heading-->
            <div class="text-center mb-11">
              <h1 class="text-gray-900 fw-bolder mb-3">REGISTRASI</h1>
            </div>

            <!--begin::Username-->
            <div class="fv-row mb-8">
              <input type="text" placeholder="Username" name="username" value="{{ old('username') }}" autocomplete="off"
                class="form-control bg-transparent @error('username') is-invalid @enderror" />
              @error('username')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <!--begin::NIP-->
            <div class="fv-row mb-8">
              <input type="text" placeholder="NIP (angka saja)" name="nip" value="{{ old('nip') }}" autocomplete="off"
                class="form-control bg-transparent @error('nip') is-invalid @enderror" />
              @error('nip')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <!--begin::Password-->
            <div class="fv-row mb-8" data-kt-password-meter="true">
              <div class="mb-1">
                <div class="position-relative mb-3">
                  <input class="form-control bg-transparent @error('password') is-invalid @enderror" type="password" placeholder="Password"
                    name="password" autocomplete="off" />
                  <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility">
                    <i class="ki-outline ki-eye-slash fs-2"></i>
                    <i class="ki-outline ki-eye fs-2 d-none"></i>
                  </span>
                </div>
                <!--begin::Meter-->
                <div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
                  <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                  <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                  <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                  <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
                </div>
              </div>
              @error('password')
                <div class="text-danger fs-7">{{ $message }}</div>
              @enderror
              <div class="text-muted">Minimal 8 karakter.</div>
            </div>

            <!--begin::Repeat Password-->
            <div class="fv-row mb-8">
              <input placeholder="Ulangi Password" name="password_confirmation" type="password" autocomplete="off"
                class="form-control bg-transparent" />
            </div>

            <!--begin::Submit-->
            <div class="d-grid mb-10">
              <button type="submit" id="kt_sign_up_submit" class="btn btn-primary">
                <span class="indicator-label">Daftar</span>
                <span class="indicator-progress">Please wait...
                  <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
              </button>
            </div>

            <div class="text-gray-500 text-center fw-semibold fs-6">
              Sudah punya akun?
              <a href="{{ route('login') }}" class="link-primary fw-semibold">Login</a>
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
