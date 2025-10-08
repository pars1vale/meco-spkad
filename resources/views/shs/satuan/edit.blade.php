@extends('layouts.master')
@section('content')
  <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
      <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
          <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">Edit Satuan</h1>
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
              <a href="{{ route('satuan.index') }}" class="text-muted text-hover-primary">Data Satuan</a>
            </li>
            <li class="breadcrumb-item">
              <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">Edit</li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-fluid">
      <div class="card">
        <div class="card-header">
          <div class="card-title">
            <h2>Edit Satuan: {{ $satuan->nama_satuan }}</h2>
          </div>
          <div class="card-toolbar">
            <a href="{{ route('satuan.index') }}" class="btn btn-secondary">
              <i class="ki-outline ki-arrow-left fs-2"></i>
              Kembali
            </a>
          </div>
        </div>

        <form action="{{ route('satuan.update', $satuan->id) }}" method="POST" class="form" id="edit_satuan_form">
          @csrf
          @method('PUT')

          <div class="card-body">
            @if (session('success'))
              <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
              <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="row">
              <div class="col-md-6">
                <!-- Nama Satuan -->
                <div class="fv-row mb-7">
                  <label class="required fs-6 fw-semibold mb-2">Nama Satuan</label>
                  <input type="text" class="form-control form-control-solid @error('nama_satuan') is-invalid @enderror" name="nama_satuan"
                    value="{{ old('nama_satuan', $satuan->nama_satuan) }}" maxlength="50" required placeholder="Contoh: Buah, Unit, Paket, dll" />
                  @error('nama_satuan')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                  <div class="form-text">Masukkan nama satuan yang akan digunakan</div>
                </div>
              </div>

              <div class="col-md-6">
                @if ($satuan->created_at)
                  <div class="alert alert-success mb-3">
                    <strong>Tanggal Dibuat:</strong> {{ $satuan->created_at->format('d/m/Y H:i:s') }}
                  </div>
                @endif

                @if ($satuan->updated_at)
                  <div class="alert alert-info">
                    <strong>Terakhir Diupdate:</strong> {{ $satuan->updated_at->format('d/m/Y H:i:s') }}
                  </div>
                @endif
              </div>
            </div>
          </div>

          <div class="card-footer">
            <div class="d-flex justify-content-end">
              <a href="{{ route('satuan.index') }}" class="btn btn-light me-3">Batal</a>
              <button type="submit" class="btn btn-primary" id="update_satuan_btn">
                <i class="ki-outline ki-check fs-2"></i>
                Update Satuan
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      // === Form validation ===
      const editForm = document.getElementById('edit_satuan_form');
      const updateButton = document.getElementById('update_satuan_btn');

      if (editForm && updateButton) {
        editForm.addEventListener('submit', function(e) {
          const namaSatuan = editForm.querySelector('input[name="nama_satuan"]').value.trim();

          if (!namaSatuan) {
            e.preventDefault();

            Swal.fire({
              icon: 'error',
              title: 'Validasi gagal',
              text: 'Nama satuan harus diisi!',
              confirmButtonText: 'OK',
              buttonsStyling: false,
              customClass: {
                confirmButton: "btn btn-primary"
              }
            });
            return;
          }

          updateButton.setAttribute('data-kt-indicator', 'on');
          updateButton.disabled = true;
        });
      }

      // === SweetAlert2 Session Messages ===
      @if (session('success'))
        Swal.fire({
          icon: 'success',
          title: 'Berhasil',
          text: '{{ session('success') }}',
          confirmButtonText: 'OK',
          buttonsStyling: false,
          customClass: {
            confirmButton: "btn btn-primary"
          }
        });
      @endif

      @if (session('error'))
        Swal.fire({
          icon: 'error',
          title: 'Gagal',
          text: '{{ session('error') }}',
          confirmButtonText: 'OK',
          buttonsStyling: false,
          customClass: {
            confirmButton: "btn btn-primary"
          }
        });
      @endif
    });
  </script>
@endsection
