@extends('layouts.master')

@section('content')
  <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
      <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
          <h1 class="page-heading text-dark fw-bold fs-3 m-0">Edit Sub Tahap</h1>
          <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
            <li class="breadcrumb-item text-muted">
              <a href="{{ route('rkpd.sub-tahap.index') }}" class="text-muted text-hover-primary">Sub Tahap</a>
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
          <form action="{{ route('rkpd.sub-tahap.update', $subTahap->id_sub_tahap) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Tahap -->
            <div class="fv-row mb-7">
              <label class="required fs-6 fw-semibold mb-2">Tahap</label>
              <select class="form-select form-select-solid @error('id_tahap') is-invalid @enderror" name="id_tahap" required>
                <option value="">Pilih Tahap</option>
                @foreach ($listTahap as $tahap)
                  <option value="{{ $tahap->id_tahap }}" {{ old('id_tahap', $subTahap->id_tahap) == $tahap->id_tahap ? 'selected' : '' }}>
                    {{ $tahap->nama_tahap }}
                  </option>
                @endforeach
              </select>
              @error('id_tahap')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <!-- Nama Sub Tahap -->
            <div class="fv-row mb-7">
              <label class="required fs-6 fw-semibold mb-2">Nama Sub Tahap</label>
              <input type="text" class="form-control form-control-solid @error('nama_sub_tahap') is-invalid @enderror"
                name="nama_sub_tahap"
                value="{{ old('nama_sub_tahap', $subTahap->nama_sub_tahap) }}" maxlength="255" required />
              @error('nama_sub_tahap')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <div class="form-text">Maksimal 255 karakter</div>
            </div>

            <!-- Actions -->
            <div class="d-flex justify-content-end">
              <a href="{{ route('rkpd.sub-tahap.index') }}" class="btn btn-light me-3">Batal</a>
              <button type="submit" class="btn btn-primary">Update</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const form = document.querySelector('form');
      const submitButton = form.querySelector('button[type="submit"]');

      form.addEventListener('submit', function(e) {
        const tahap = form.querySelector('select[name="id_tahap"]').value;
        const nama = form.querySelector('input[name="nama_sub_tahap"]').value.trim();

        if (!tahap || !nama) {
          e.preventDefault();
          Swal.fire({
            icon: 'error',
            title: 'Validasi gagal',
            text: 'Semua field wajib diisi!',
            confirmButtonText: 'OK',
            buttonsStyling: false,
            customClass: { confirmButton: "btn btn-primary" }
          });
          return;
        }

        submitButton.innerHTML = 'Mengupdate...';
        submitButton.disabled = true;
      });
    });
  </script>
@endsection
