@extends('layouts.master')

@section('content')
  <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
      <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
          <h1 class="page-heading text-dark fw-bold fs-3 m-0">Edit Perangkat Daerah</h1>
          <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
            <li class="breadcrumb-item text-muted">
              <a href="{{ url('/home') }}" class="text-muted text-hover-primary">Home</a>
            </li>
            <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
            <li class="breadcrumb-item text-muted">Pengaturan</li>
            <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
            <li class="breadcrumb-item text-muted">
              <a href="{{ route('pengaturan.perangkat-daerah.index') }}" class="text-muted text-hover-primary">
                Perangkat Daerah
              </a>
            </li>
            <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
            <li class="breadcrumb-item text-muted">Edit</li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-fluid">

      @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          {{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif

      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Edit Data {{ $data->status }}</h3>
        </div>

        <form action="{{ route('perangkat-daerah.update', $data->id) }}" method="POST">
          @csrf
          @method('PUT')

          <div class="card-body">

            {{-- Info Kode SKPD (Read Only) --}}
            <div class="fv-row mb-7">
              <label class="fs-6 fw-semibold mb-2">Kode SKPD</label>
              <input type="text" class="form-control form-control-solid" value="{{ $data->kode_skpd }}" readonly />
            </div>

            {{-- Info Status (Read Only) --}}
            <div class="fv-row mb-7">
              <label class="fs-6 fw-semibold mb-2">Status</label>
              <input type="text" class="form-control form-control-solid" value="{{ $data->status }}" readonly />
            </div>

            {{-- Nama SKPD --}}
            <div class="fv-row mb-7">
              <label class="required fs-6 fw-semibold mb-2">
                Nama {{ $data->status }}
              </label>
              <input type="text" class="form-control form-control-solid @error('nama_skpd') is-invalid @enderror" placeholder="Masukkan Nama"
                name="nama_skpd" value="{{ old('nama_skpd', $data->nama_skpd) }}" maxlength="255" required />
              @error('nama_skpd')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- NIP Kepala --}}
            <div class="fv-row mb-7">
              <label class="fs-6 fw-semibold mb-2">NIP Kepala</label>
              <input type="text" class="form-control form-control-solid @error('nipkepala') is-invalid @enderror" placeholder="Masukkan NIP Kepala"
                name="nipkepala" value="{{ old('nipkepala', $data->nipkepala) }}" maxlength="255" />
              @error('nipkepala')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- Nama Kepala --}}
            <div class="fv-row mb-7">
              <label class="fs-6 fw-semibold mb-2">Nama Kepala</label>
              <input type="text" class="form-control form-control-solid @error('namakepala') is-invalid @enderror" placeholder="Masukkan Nama Kepala"
                name="namakepala" value="{{ old('namakepala', $data->namakepala) }}" maxlength="255" />
              @error('namakepala')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- Pangkat Kepala --}}
            <div class="fv-row mb-7">
              <label class="fs-6 fw-semibold mb-2">Pangkat Kepala</label>
              <select data-control="select2" class="form-select form-select-solid @error('pangkatkepala') is-invalid @enderror" name="pangkatkepala">
                <option value="">Pilih Pangkat</option>
                @foreach ($pangkat as $p)
                  <option value="{{ $p->id }}" {{ old('pangkatkepala', $data->pangkatkepala) == $p->id ? 'selected' : '' }}>
                    {{ $p->nama }}
                  </option>
                @endforeach
              </select>
              @error('pangkatkepala')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- Status Kepala --}}
            <div class="fv-row mb-7">
              <label class="fs-6 fw-semibold mb-2">Status Kepala</label>
              <select data-control="select2" class="form-select form-select-solid @error('statuskepala') is-invalid @enderror" name="statuskepala">
                <option value="">Pilih Status</option>
                <option value="PA" {{ old('statuskepala', $data->statuskepala) == 'PA' ? 'selected' : '' }}>
                  Pengguna Anggaran
                </option>
                <option value="KPA" {{ old('statuskepala', $data->statuskepala) == 'KPA' ? 'selected' : '' }}>
                  Kuasa Pengguna Anggaran
                </option>
                <option value="PLT" {{ old('statuskepala', $data->statuskepala) == 'PLT' ? 'selected' : '' }}>
                  Pelaksana Tugas
                </option>
                <option value="PLH" {{ old('statuskepala', $data->statuskepala) == 'PLH' ? 'selected' : '' }}>
                  Pelaksana Harian
                </option>
              </select>
              @error('statuskepala')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

          </div>

          <div class="card-footer d-flex justify-content-end gap-2">
            <a href="{{ route('pengaturan.perangkat-daerah.index') }}" class="btn btn-light">
              Batal
            </a>
            <button type="submit" class="btn btn-primary">
              <i class="ki-outline ki-check fs-2"></i>
              Simpan Perubahan
            </button>
          </div>
        </form>
      </div>

    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
@endsection
