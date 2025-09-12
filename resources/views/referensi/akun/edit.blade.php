@extends('layouts.master')
@section('content')
  <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
      <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
          <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">Edit Akun</h1>
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
              <a href="{{ route('referensi.akun.index') }}" class="text-muted text-hover-primary">Akun</a>
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
            <h2>Edit Akun: {{ $akun->kode_akun }}</h2>
          </div>
          <div class="card-toolbar">
            <a href="{{ route('referensi.akun.index') }}" class="btn btn-secondary">
              <i class="ki-outline ki-arrow-left fs-2"></i>
              Kembali
            </a>
          </div>
        </div>

        <form action="{{ route('akun.update', $akun->id) }}" method="POST" class="form" id="edit_akun_form">
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
                <!-- Kode Akun -->
                <div class="fv-row mb-7">
                  <label class="required fs-6 fw-semibold mb-2">Kode Akun</label>
                  <input type="text" class="form-control form-control-solid @error('kode_akun') is-invalid @enderror" name="kode_akun"
                    value="{{ old('kode_akun', $akun->kode_akun) }}" maxlength="255" required />
                  @error('kode_akun')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <!-- Nama Akun -->
                <div class="fv-row mb-7">
                  <label class="required fs-6 fw-semibold mb-2">Nama Akun</label>
                  <textarea class="form-control form-control-solid @error('nama_akun') is-invalid @enderror" rows="3" name="nama_akun" required>{{ old('nama_akun', $akun->nama_akun) }}</textarea>
                  @error('nama_akun')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <!-- Keterangan Akun -->
                <div class="fv-row mb-7">
                  <label class="fs-6 fw-semibold mb-2">Keterangan Akun</label>
                  <textarea class="form-control form-control-solid @error('keterangan_akun') is-invalid @enderror" rows="5" name="keterangan_akun">{{ old('keterangan_akun', $akun->keterangan_akun) }}</textarea>
                  @error('keterangan_akun')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <div class="col-md-6">
                <!-- Tipe Akun -->
                <div class="fv-row mb-7">
                  <label class="required fs-6 fw-semibold mb-2">Tipe Akun</label>
                  <div class="form-text mb-3">Pilih salah satu tipe akun</div>

                  <div class="row">
                    <!-- Pendapatan Switch -->
                    <div class="col-md-4 mb-3">
                      <div class="form-check form-switch form-check-custom form-check-solid">
                        <input class="form-check-input akun-type-switch" type="checkbox" value="1" id="pendapatanSwitchEdit" name="is_pendapatan"
                          {{ old('is_pendapatan', $akun->is_pendapatan) ? 'checked' : '' }} />
                        <label class="form-check-label" for="pendapatanSwitchEdit">
                          Pendapatan
                        </label>
                      </div>
                    </div>

                    <!-- Belanja Switch -->
                    <div class="col-md-4 mb-3">
                      <div class="form-check form-switch form-check-custom form-check-solid">
                        <input class="form-check-input akun-type-switch" type="checkbox" value="1" id="belanjaSwitchEdit" name="is_belanja"
                          {{ old('is_belanja', $akun->is_belanja) ? 'checked' : '' }} />
                        <label class="form-check-label" for="belanjaSwitchEdit">
                          Belanja
                        </label>
                      </div>
                    </div>

                    <!-- Pembiayaan Switch -->
                    <div class="col-md-4 mb-3">
                      <div class="form-check form-switch form-check-custom form-check-solid">
                        <input class="form-check-input akun-type-switch" type="checkbox" value="1" id="pembiayaanSwitchEdit" name="is_pembiayaan"
                          {{ old('is_pembiayaan', $akun->is_pembiayaan) ? 'checked' : '' }} />
                        <label class="form-check-label" for="pembiayaanSwitchEdit">
                          Pembiayaan
                        </label>
                      </div>
                    </div>
                  </div>

                  <div class="invalid-feedback d-none" id="tipe-akun-error-edit">
                    Anda harus memilih salah satu tipe akun
                  </div>
                  @error('tipe_akun')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror
                </div>

                <!-- Info Current Values -->
                <div class="alert alert-info">
                  <h6 class="mb-2"><strong>Status Akun Saat Ini:</strong></h6>
                  <div class="d-flex flex-wrap gap-2">
                    <span class="badge badge-light-{{ $akun->pendapatan == 'ya' ? 'success' : 'secondary' }}">
                      Pendapatan: {{ ucfirst($akun->pendapatan) }}
                    </span>
                    <span class="badge badge-light-{{ $akun->belanja == 'ya' ? 'success' : 'secondary' }}">
                      Belanja: {{ ucfirst($akun->belanja) }}
                    </span>
                    <span class="badge badge-light-{{ $akun->pembiayaan == 'ya' ? 'success' : 'secondary' }}">
                      Pembiayaan: {{ ucfirst($akun->pembiayaan) }}
                    </span>
                  </div>
                </div>

                @if ($akun->updated_at)
                  <div class="alert alert-secondary">
                    <strong>Terakhir diupdate:</strong> {{ $akun->updated_at->format('d/m/Y H:i:s') }}
                  </div>
                @endif
              </div>
            </div>
          </div>

          <div class="card-footer">
            <div class="d-flex justify-content-end">
              <a href="{{ route('referensi.akun.index') }}" class="btn btn-light me-3">Batal</a>
              <button type="submit" class="btn btn-primary" id="update_akun_btn">
                <i class="ki-outline ki-check fs-2"></i>
                Update Akun
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      // === Switch logic untuk form edit ===
      const akunTypeSwitches = document.querySelectorAll('.akun-type-switch');
      akunTypeSwitches.forEach(switchEl => {
        switchEl.addEventListener('change', function() {
          if (this.checked) {
            // Matikan switch lainnya
            akunTypeSwitches.forEach(otherSwitch => {
              if (otherSwitch !== this) {
                otherSwitch.checked = false;
              }
            });
          }
          // Reset error message
          document.getElementById('tipe-akun-error-edit').classList.add('d-none');
        });
      });

      // === Form validation ===
      const editForm = document.getElementById('edit_akun_form');
      const updateButton = document.getElementById('update_akun_btn');

      if (editForm && updateButton) {
        editForm.addEventListener('submit', function(e) {
          const kodeAkun = editForm.querySelector('input[name="kode_akun"]').value.trim();
          const namaAkun = editForm.querySelector('textarea[name="nama_akun"]').value.trim();

          // Check if at least one switch is selected
          const pendapatanChecked = editForm.querySelector('#pendapatanSwitchEdit').checked;
          const belanjaChecked = editForm.querySelector('#belanjaSwitchEdit').checked;
          const pembiayaanChecked = editForm.querySelector('#pembiayaanSwitchEdit').checked;

          const hasTypeSelected = pendapatanChecked || belanjaChecked || pembiayaanChecked;

          if (!kodeAkun || !namaAkun || !hasTypeSelected) {
            e.preventDefault();

            if (!hasTypeSelected) {
              document.getElementById('tipe-akun-error-edit').classList.remove('d-none');
            }

            Swal.fire({
              icon: 'error',
              title: 'Validasi gagal',
              text: !hasTypeSelected ? 'Anda harus memilih salah satu tipe akun!' : 'Semua field wajib diisi!',
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
