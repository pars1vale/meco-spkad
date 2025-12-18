@extends('layouts.master')

@section('content')
  <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
      <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
          <h1 class="page-heading text-dark fw-bold fs-3 m-0">Edit Urusan</h1>
          <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
            <li class="breadcrumb-item text-muted">
              <a href="{{ route('referensi.urusan.index') }}" class="text-muted text-hover-primary">Urusan</a>
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
          <form action="{{ route('referensi.urusan.update', $urusan->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Kode Urusan -->
            <div class="fv-row mb-7">
              <label class="required fs-6 fw-semibold mb-2">Kode Urusan</label>
              <input type="text" class="form-control form-control-solid @error('kode_urusan') is-invalid @enderror" name="kode_urusan"
                value="{{ old('kode_urusan', $urusan->kode_urusan) }}" maxlength="10" required />
              @error('kode_urusan')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <!-- Nama Urusan -->
            <div class="fv-row mb-7">
              <label class="required fs-6 fw-semibold mb-2">Nama Urusan</label>
              <input type="text" class="form-control form-control-solid @error('nama_urusan') is-invalid @enderror" name="nama_urusan"
                value="{{ old('nama_urusan', $urusan->nama_urusan) }}" maxlength="255" required />
              @error('nama_urusan')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <!-- Actions -->
            <div class="d-flex justify-content-end">
              <a href="{{ route('referensi.urusan.index') }}" class="btn btn-light me-3">Batal</a>
              <button type="submit" class="btn btn-primary">Update</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
