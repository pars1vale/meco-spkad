@extends('layouts.master')

@section('content')
  <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
      <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
          <h1 class="page-heading text-dark fw-bold fs-3 m-0">Edit Program</h1>
          <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
            <li class="breadcrumb-item text-muted">
              <a href="{{ route('referensi.program.index') }}" class="text-muted text-hover-primary">Program</a>
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
        <div class="card-body">
          <form action="{{ route('program.update', $program->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Bidang Urusan -->
            <div class="fv-row mb-7">
              <label class="required fs-6 fw-semibold mb-2">Bidang Urusan</label>
              <select class="form-select form-select-solid @error('id_bidang_urusan') is-invalid @enderror" name="id_bidang_urusan" required>
                <option value="">Pilih Bidang Urusan</option>
                @foreach ($listBidangUrusan->groupBy('nama_urusan') as $namaUrusan => $bidangUrusanGroup)
                  <optgroup label="{{ $namaUrusan }}">
                    @foreach ($bidangUrusanGroup as $bidangUrusan)
                      <option value="{{ $bidangUrusan->id }}"
                        {{ old('id_bidang_urusan', $program->id_bidang_urusan) == $bidangUrusan->id ? 'selected' : '' }}>
                        {{ $bidangUrusan->kode_bidang_urusan }} - {{ $bidangUrusan->nama_bidang_urusan }}
                      </option>
                    @endforeach
                  </optgroup>
                @endforeach
              </select>
              @error('id_bidang_urusan')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <!-- Kode Program -->
            <div class="fv-row mb-7">
              <label class="required fs-6 fw-semibold mb-2">Kode Program</label>
              <input type="text" class="form-control form-control-solid @error('kode_program') is-invalid @enderror" name="kode_program"
                value="{{ old('kode_program', $program->kode_program) }}" maxlength="20" required />
              @error('kode_program')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <div class="form-text">Maksimal 20 karakter</div>
            </div>

            <!-- Nama Program -->
            <div class="fv-row mb-7">
              <label class="required fs-6 fw-semibold mb-2">Nama Program</label>
              <input type="text" class="form-control form-control-solid @error('nama_program') is-invalid @enderror" name="nama_program"
                value="{{ old('nama_program', $program->nama_program) }}" maxlength="255" required />
              @error('nama_program')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <div class="form-text">Maksimal 255 karakter</div>
            </div>

            <!-- Actions -->
            <div class="d-flex justify-content-end">
              <a href="{{ route('referensi.program.index') }}" class="btn btn-light me-3">Batal</a>
              <button type="submit" class="btn btn-primary">Update</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      // Form validation
      const form = document.querySelector('form');
      const submitButton = document.querySelector('button[type="submit"]');

      if (form && submitButton) {
        form.addEventListener('submit', function(e) {
          const idBidangUrusan = form.querySelector('select[name="id_bidang_urusan"]').value;
          const kodeProgram = form.querySelector('input[name="kode_program"]').value.trim();
          const namaProgram = form.querySelector('input[name="nama_program"]').value.trim();

          if (!idBidangUrusan || !kodeProgram || !namaProgram) {
            e.preventDefault();

            if (typeof Swal !== 'undefined') {
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
            } else {
              alert('Semua field wajib diisi!');
            }
            return;
          }

          // Show loading state
          submitButton.innerHTML = 'Mengupdate...';
          submitButton.disabled = true;
        });
      }
    });
  </script>
@endsection
