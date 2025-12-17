@extends('layouts.master')
@section('content')
  <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
      <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
          <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">Edit Sumber Dana</h1>
          <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
            <li class="breadcrumb-item text-muted">
              <a href="{{ url('/home') }}" class="text-muted text-hover-primary">Home</a>
            </li>
            <li class="breadcrumb-item">
              <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">Referensi</li>
            <li class="breadcrumb-item">
              <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">
              <a href="{{ route('referensi.sumber-dana.index') }}" class="text-muted text-hover-primary">Sumber Dana</a>
            </li>
            <li class="breadcrumb-item">
              <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">Edit</li>
          </ul>
        </div>
        <div class="app-toolbar-wrapper d-flex align-items-center gap-2">
          <a href="{{ route('referensi.sumber-dana.index') }}" class="btn btn-secondary">
            <i class="ki-outline ki-black-left fs-3"></i>Kembali
          </a>
        </div>
      </div>
    </div>
  </div>

  <div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-fluid">
      <div class="row">
        <div class="col-lg-8">
          <div class="card">
            <div class="card-header">
              <div class="card-title">
                <h3 class="fw-bold text-gray-800">Form Edit Sumber Dana</h3>
              </div>
            </div>
            <div class="card-body">
              <form id="kt_edit_sumberdana_form" action="{{ route('referensi.sumber-dana.update', $sumberDana->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-7">
                  <label class="col-lg-3 fw-semibold text-muted required">Kode Dana</label>
                  <div class="col-lg-9 fv-row">
                    <input type="text" name="kode_dana"
                      class="form-control form-control-lg form-control-solid @error('kode_dana') is-invalid @enderror"
                      value="{{ old('kode_dana', $sumberDana->kode_dana) }}" placeholder="Masukkan Kode Dana" />
                    @error('kode_dana')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>

                <div class="row mb-7">
                  <label class="col-lg-3 fw-semibold text-muted required">Nama Dana</label>
                  <div class="col-lg-9 fv-row">
                    <input type="text" name="nama_dana"
                      class="form-control form-control-lg form-control-solid @error('nama_dana') is-invalid @enderror"
                      value="{{ old('nama_dana', $sumberDana->nama_dana) }}" placeholder="Masukkan Nama Dana" />
                    @error('nama_dana')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>

                <div class="row mb-7">
                  <label class="col-lg-3 fw-semibold text-muted required">Sumber Dana</label>
                  <div class="col-lg-9">
                    <textarea name="sumber_dana" class="form-control form-control-lg form-control-solid" rows="4" placeholder="Masukkan Sumber Dana (opsional)">{{ old('sumber_dana', $sumberDana->sumber_dana) }}</textarea>
                  </div>
                </div>

                @if ($sumberDana->time_stamp)
                  <div class="row mb-7">
                    <label class="col-lg-3 fw-semibold text-muted">Dibuat</label>
                    <div class="col-lg-9">
                      <span class="fw-semibold fs-6 text-gray-800">
                        {{ \Carbon\Carbon::parse($sumberDana->time_stamp)->format('d/m/Y H:i') }}
                      </span>
                    </div>
                  </div>
                @endif

                @if ($sumberDana->updated_at)
                  <div class="row mb-10">
                    <label class="col-lg-3 fw-semibold text-muted">Terakhir Diupdate</label>
                    <div class="col-lg-9">
                      <span class="fw-semibold fs-6 text-gray-800">
                        {{ \Carbon\Carbon::parse($sumberDana->updated_at)->format('d/m/Y H:i') }}
                      </span>
                    </div>
                  </div>
                @endif

                <div class="d-flex justify-content-end">
                  <a href="{{ route('referensi.sumber-dana.index') }}" class="btn btn-light me-3">Batal</a>
                  <button type="submit" class="btn btn-primary" id="kt_edit_sumberdana_submit">
                    <span class="indicator-label">Simpan Perubahan</span>
                    <span class="indicator-progress">Please wait...
                      <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="card">
            <div class="card-header">
              <div class="card-title">
                <h3 class="fw-bold text-gray-800">Informasi</h3>
              </div>
            </div>
            <div class="card-body">
              <div class="notice d-flex bg-light-info rounded border-info border border-dashed flex-column p-6">
                <div class="d-flex flex-stack flex-grow-1 flex-wrap flex-md-nowrap">
                  <div class="mb-3 mb-md-0 fw-semibold">
                    <h4 class="text-gray-900 fw-bold">Petunjuk Edit</h4>
                    <div class="fs-6 text-gray-700 pe-7">
                      <ul class="mb-0">
                        <li>Pastikan Kode Dana unik dan belum digunakan</li>
                        <li>Nama Dana wajib diisi</li>
                        <li>Sumber Dana wajib diisi</li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>

              <div class="separator separator-dashed my-6"></div>

              <div class="mb-6">
                <h5 class="fw-bold text-gray-800 mb-4">Status Data</h5>
                <div class="d-flex align-items-center mb-3">
                  <span class="bullet bullet-vertical h-30px bg-success me-3"></span>
                  <div class="flex-grow-1">
                    <span class="text-gray-800 fw-semibold fs-6">Data Valid</span>
                    <span class="text-muted fs-7 d-block">Semua field terisi dengan benar</span>
                  </div>
                </div>
              </div>

              <div class="mb-0">
                <h5 class="fw-bold text-gray-800 mb-4">Aksi Cepat</h5>
                <div class="d-flex flex-column gap-2">
                  <button type="button" class="btn btn-sm btn-light-primary" onclick="resetForm()">
                    <i class="ki-outline ki-arrows-circle fs-5 me-1"></i>Reset Form
                  </button>
                  <a href="{{ route('referensi.sumber-dana.index') }}" class="btn btn-sm btn-light-secondary">
                    <i class="ki-outline ki-element-11 fs-5 me-1"></i>Lihat Semua Data
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const form = document.getElementById('kt_edit_sumberdana_form');
      const submitButton = document.getElementById('kt_edit_sumberdana_submit');

      // Form validation and submission
      form.addEventListener('submit', function(e) {
        // Show loading state
        submitButton.setAttribute('data-kt-indicator', 'on');
        submitButton.disabled = true;
      });

      // Show session messages
      @if (session('success'))
        Swal.fire({
          title: 'Berhasil!',
          text: "{{ session('success') }}",
          icon: 'success'
        });
      @endif

      @if (session('error'))
        Swal.fire({
          title: 'Error!',
          text: "{{ session('error') }}",
          icon: 'error'
        });
      @endif

      // Auto-hide loading state after form submission attempt
      setTimeout(function() {
        if (submitButton.hasAttribute('data-kt-indicator')) {
          submitButton.removeAttribute('data-kt-indicator');
          submitButton.disabled = false;
        }
      }, 3000);
    });

    // Reset form function
    function resetForm() {
      Swal.fire({
        title: 'Reset Form?',
        text: 'Yakin ingin mereset form ke data asli?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Reset!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          // Reset form to original values
          document.querySelector('input[name="kode_dana"]').value = '{{ $sumberDana->kode_dana }}';
          document.querySelector('input[name="nama_dana"]').value = '{{ $sumberDana->nama_dana }}';
          document.querySelector('textarea[name="sumber_dana"]').value = '{{ $sumberDana->sumber_dana ?? '' }}';

          // Remove validation classes
          document.querySelectorAll('.is-invalid').forEach(function(element) {
            element.classList.remove('is-invalid');
          });

          Swal.fire({
            title: 'Berhasil!',
            text: 'Form telah direset ke data asli',
            icon: 'success',
            timer: 1500,
            showConfirmButton: false
          });
        }
      });
    }

    // Auto-save draft functionality (optional)
    let autoSaveTimeout;
    const formInputs = document.querySelectorAll('#kt_edit_sumberdana_form input, #kt_edit_sumberdana_form textarea');

    formInputs.forEach(input => {
      input.addEventListener('input', function() {
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(() => {
          // You can implement auto-save to localStorage or session here
          console.log('Auto-saving draft...');
        }, 2000);
      });
    });
  </script>
@endsection
