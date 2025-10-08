@extends('layouts.master')
@section('content')
  <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
      <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
          <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">Edit Kelompok Standar Harga</h1>
          <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
            <li class="breadcrumb-item text-muted">
              <a href="{{ url('/home') }}" class="text-muted text-hover-primary">Home</a>
            </li>
            <li class="breadcrumb-item">
              <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">Standar Harga</li>
            <li class="breadcrumb-item">
              <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">
              <a href="{{ route('kelompok_satuan_harga.index') }}" class="text-muted text-hover-primary">Kelompok</a>
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
            <h2>Edit Kelompok: {{ $kelompok->kode_kelompok_standar_harga }}</h2>
          </div>
          <div class="card-toolbar">
            <a href="{{ route('kelompok_satuan_harga.index') }}" class="btn btn-secondary">
              <i class="ki-outline ki-arrow-left fs-2"></i>
              Kembali
            </a>
          </div>
        </div>

        <form action="{{ route('kelompok_satuan_harga.update', $kelompok->id) }}" method="POST" class="form" id="edit_kelompok_form">
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
                <!-- Kode Kelompok -->
                <div class="fv-row mb-7">
                  <label class="required fs-6 fw-semibold mb-2">Kode Kelompok</label>
                  <input type="text" class="form-control form-control-solid @error('kode_kelompok_standar_harga') is-invalid @enderror"
                    name="kode_kelompok_standar_harga" value="{{ old('kode_kelompok_standar_harga', $kelompok->kode_kelompok_standar_harga) }}"
                    maxlength="30" required />
                  @error('kode_kelompok_standar_harga')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <!-- Tipe Kelompok -->
                <div class="fv-row mb-7">
                  <label class="required fs-6 fw-semibold mb-2">Tipe Kelompok</label>
                  <div class="form-text mb-3">Pilih tipe kelompok standar harga</div>

                  <div class="d-flex flex-wrap gap-3">
                    <div class="form-check form-check-custom form-check-solid">
                      <input class="form-check-input @error('tipe_kelompok') is-invalid @enderror" type="radio" value="SSH" id="tipe_SSH_edit"
                        name="tipe_kelompok" {{ old('tipe_kelompok', $kelompok->tipe_kelompok) == 'SSH' ? 'checked' : '' }} required />
                      <label class="form-check-label fw-bold" for="tipe_SSH_edit">SSH</label>
                    </div>

                    <div class="form-check form-check-custom form-check-solid">
                      <input class="form-check-input @error('tipe_kelompok') is-invalid @enderror" type="radio" value="HSPK" id="tipe_HSPK_edit"
                        name="tipe_kelompok" {{ old('tipe_kelompok', $kelompok->tipe_kelompok) == 'HSPK' ? 'checked' : '' }} />
                      <label class="form-check-label fw-bold" for="tipe_HSPK_edit">HSPK</label>
                    </div>

                    <div class="form-check form-check-custom form-check-solid">
                      <input class="form-check-input @error('tipe_kelompok') is-invalid @enderror" type="radio" value="ASB" id="tipe_ASB_edit"
                        name="tipe_kelompok" {{ old('tipe_kelompok', $kelompok->tipe_kelompok) == 'ASB' ? 'checked' : '' }} />
                      <label class="form-check-label fw-bold" for="tipe_ASB_edit">ASB</label>
                    </div>

                    <div class="form-check form-check-custom form-check-solid">
                      <input class="form-check-input @error('tipe_kelompok') is-invalid @enderror" type="radio" value="SBU" id="tipe_SBU_edit"
                        name="tipe_kelompok" {{ old('tipe_kelompok', $kelompok->tipe_kelompok) == 'SBU' ? 'checked' : '' }} />
                      <label class="form-check-label fw-bold" for="tipe_SBU_edit">SBU</label>
                    </div>
                  </div>
                  @error('tipe_kelompok')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror
                </div>

                <!-- Nama Kelompok -->
                <div class="fv-row mb-7">
                  <label class="required fs-6 fw-semibold mb-2">Nama Kelompok</label>
                  <textarea class="form-control form-control-solid @error('nama_kelompok_standar_harga') is-invalid @enderror" rows="4"
                    name="nama_kelompok_standar_harga" required>{{ old('nama_kelompok_standar_harga', $kelompok->nama_kelompok_standar_harga) }}</textarea>
                  @error('nama_kelompok_standar_harga')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <div class="col-md-6">
                <!-- Info -->
                <div class="alert alert-success">
                  <h6 class="mb-2"><strong>Informasi Saat Ini:</strong></h6>
                  <div class="d-flex flex-column gap-1">
                    <span><strong>Kode:</strong> {{ $kelompok->kode_kelompok_standar_harga }}</span>
                    <span><strong>Tipe:</strong> <span class="badge badge-light-primary">{{ $kelompok->tipe_kelompok }}</span></span>
                    <span><strong>Nama:</strong> {{ $kelompok->nama_kelompok_standar_harga }}</span>
                  </div>
                </div>

                @if ($kelompok->updated_at)
                  <div class="alert alert-info">
                    <strong>Terakhir diupdate:</strong> {{ $kelompok->updated_at->format('d/m/Y H:i:s') }}
                  </div>
                @endif

                @if ($kelompok->standarHarga()->count() > 0)
                  <div class="alert alert-warning">
                    <h6 class="mb-2"><strong>Perhatian!</strong></h6>
                    <span>Kelompok ini memiliki <strong>{{ $kelompok->standarHarga()->count() }}</strong> standar harga terkait.</span>
                  </div>
                @endif
              </div>
            </div>
          </div>

          <div class="card-footer">
            <div class="d-flex justify-content-end">
              <a href="{{ route('kelompok_satuan_harga.index') }}" class="btn btn-light me-3">Batal</a>
              <button type="submit" class="btn btn-primary" id="update_kelompok_btn">
                <i class="ki-outline ki-check fs-2"></i>
                Update
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const editForm = document.getElementById('edit_kelompok_form');
      const updateButton = document.getElementById('update_kelompok_btn');

      if (editForm && updateButton) {
        editForm.addEventListener('submit', function(e) {
          const kodeKelompok = editForm.querySelector('input[name="kode_kelompok_standar_harga"]').value.trim();
          const namaKelompok = editForm.querySelector('textarea[name="nama_kelompok_standar_harga"]').value.trim();
          const tipeKelompok = editForm.querySelector('input[name="tipe_kelompok"]:checked');

          if (!kodeKelompok || !namaKelompok || !tipeKelompok) {
            e.preventDefault();

            Swal.fire({
              icon: 'error',
              title: 'Validasi gagal',
              text: 'Semua field wajib diisi!',
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
