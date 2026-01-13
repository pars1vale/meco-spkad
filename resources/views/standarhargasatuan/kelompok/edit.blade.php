@extends('layouts.master')
@section('content')
  <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
      <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
          <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">Edit Kelompok Satuan Harga
          </h1>
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
              <a href="{{ route('kelompok_satuan_harga.index') }}" class="text-muted text-hover-primary">Kelompok Satuan Harga</a>
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
            <h2>Edit Kelompok: {{ $kelompok->kode_kategori }}</h2>
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
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                  <i class="ki-outline ki-check-circle fs-2 text-success me-3"></i>
                  <div>{{ session('success') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            @endif

            @if (session('error'))
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                  <i class="ki-outline ki-cross-circle fs-2 text-danger me-3"></i>
                  <div>{{ session('error') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            @endif

            <div class="row">
              <div class="col-md-6">
                <!-- Kode Kategori -->
                <div class="fv-row mb-7">
                  <label class="required fs-6 fw-semibold mb-2">Kode Kategori</label>
                  <input type="text" class="form-control form-control-solid @error('kode_kategori') is-invalid @enderror" name="kode_kategori"
                    value="{{ old('kode_kategori', $kelompok->kode_kategori) }}" maxlength="50" required />
                  @error('kode_kategori')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <!-- Tahun Anggaran -->
                <div class="fv-row mb-7">
                  <label class="required fs-6 fw-semibold mb-2">Tahun Anggaran</label>
                  <input type="number" class="form-control form-control-solid @error('tahun_anggaran') is-invalid @enderror" name="tahun_anggaran"
                    value="{{ old('tahun_anggaran', $kelompok->tahun_anggaran) }}" min="2000" max="2100" required />
                  @error('tahun_anggaran')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <!-- Status Active -->
                <div class="fv-row mb-7">
                  <div class="form-check form-switch form-check-custom form-check-solid">
                    <input class="form-check-input" type="checkbox" value="1" id="active_switch_edit" name="active"
                      {{ old('active', $kelompok->active) ? 'checked' : '' }} />
                    <label class="form-check-label fw-bold" for="active_switch_edit">
                      Status Aktif
                    </label>
                  </div>
                  <div class="form-text">Data yang aktif dapat digunakan dalam transaksi</div>
                </div>
              </div>

              <div class="col-md-6">
                <!-- Tipe Kelompok -->
                <div class="fv-row mb-7">
                  <label class="required fs-6 fw-semibold mb-2">Tipe Kelompok</label>
                  <div class="form-text mb-3">Pilih tipe kelompok satuan harga</div>

                  <div class="d-flex flex-column gap-3">
                    <div class="form-check form-check-custom form-check-solid">
                      <input class="form-check-input @error('tipe_kelompok') is-invalid @enderror" type="radio" value="SSH" id="tipe_SSH_edit"
                        name="tipe_kelompok" {{ old('tipe_kelompok', $kelompok->tipe_kelompok) == 'SSH' ? 'checked' : '' }} required />
                      <label class="form-check-label fw-bold" for="tipe_SSH_edit">
                        SSH - Standar Satuan Harga
                      </label>
                    </div>

                    <div class="form-check form-check-custom form-check-solid">
                      <input class="form-check-input @error('tipe_kelompok') is-invalid @enderror" type="radio" value="HSPK" id="tipe_HSPK_edit"
                        name="tipe_kelompok" {{ old('tipe_kelompok', $kelompok->tipe_kelompok) == 'HSPK' ? 'checked' : '' }} />
                      <label class="form-check-label fw-bold" for="tipe_HSPK_edit">
                        HSPK - Harga Satuan Pokok Kegiatan
                      </label>
                    </div>

                    <div class="form-check form-check-custom form-check-solid">
                      <input class="form-check-input @error('tipe_kelompok') is-invalid @enderror" type="radio" value="ASB" id="tipe_ASB_edit"
                        name="tipe_kelompok" {{ old('tipe_kelompok', $kelompok->tipe_kelompok) == 'ASB' ? 'checked' : '' }} />
                      <label class="form-check-label fw-bold" for="tipe_ASB_edit">
                        ASB - Analisa Standar Belanja
                      </label>
                    </div>

                    <div class="form-check form-check-custom form-check-solid">
                      <input class="form-check-input @error('tipe_kelompok') is-invalid @enderror" type="radio" value="SBU" id="tipe_SBU_edit"
                        name="tipe_kelompok" {{ old('tipe_kelompok', $kelompok->tipe_kelompok) == 'SBU' ? 'checked' : '' }} />
                      <label class="form-check-label fw-bold" for="tipe_SBU_edit">
                        SBU - Standar Biaya Umum
                      </label>
                    </div>
                  </div>
                  @error('tipe_kelompok')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror
                </div>

                <!-- Info Card -->
                <div class="alert alert-info">
                  <h6 class="mb-2"><strong>Informasi Saat Ini:</strong></h6>
                  <div class="d-flex flex-column gap-1">
                    <span><strong>Kode:</strong> {{ $kelompok->kode_kategori }}</span>
                    <span><strong>Tipe:</strong> <span class="badge badge-light-primary">{{ $kelompok->tipe_kelompok }}</span></span>
                    <span><strong>Tahun:</strong> {{ $kelompok->tahun_anggaran }}</span>
                    <span><strong>Status:</strong>
                      @if ($kelompok->active)
                        <span class="badge badge-light-success">Aktif</span>
                      @else
                        <span class="badge badge-light-danger">Tidak Aktif</span>
                      @endif
                    </span>
                  </div>
                </div>

                @if ($kelompok->updated_at)
                  <div class="alert alert-secondary">
                    <strong>Terakhir diupdate:</strong> {{ $kelompok->updated_at->format('d/m/Y H:i:s') }}
                  </div>
                @endif
              </div>
            </div>

            <!-- Uraian Kategori -->
            <div class="fv-row mb-7">
              <label class="required fs-6 fw-semibold mb-2">Uraian Kategori</label>
              <textarea class="form-control form-control-solid @error('uraian_kategori') is-invalid @enderror" rows="4" name="uraian_kategori" required>{{ old('uraian_kategori', $kelompok->uraian_kategori) }}</textarea>
              @error('uraian_kategori')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="card-footer">
            <div class="d-flex justify-content-end gap-2">
              <a href="{{ route('kelompok_satuan_harga.index') }}" class="btn btn-light">
                <i class="ki-outline ki-cross fs-2"></i>
                Batal
              </a>
              <button type="submit" class="btn btn-primary" id="update_kelompok_btn">
                <i class="ki-outline ki-check fs-2"></i>
                Update Data
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
          const idKategori = editForm.querySelector('input[name="id_kategori"]').value.trim();
          const kodeKategori = editForm.querySelector('input[name="kode_kategori"]').value.trim();
          const uraianKategori = editForm.querySelector('textarea[name="uraian_kategori"]').value.trim();
          const tipeKelompok = editForm.querySelector('input[name="tipe_kelompok"]:checked');
          const tahunAnggaran = editForm.querySelector('input[name="tahun_anggaran"]').value.trim();

          if (!idKategori || !kodeKategori || !uraianKategori || !tipeKelompok || !tahunAnggaran) {
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
