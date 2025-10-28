
@extends('layouts.master')

@section('content')
  <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
      <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
          <h1 class="page-heading text-dark fw-bold fs-3 m-0">Edit Tahap Penjadwalan</h1>
          <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
            <li class="breadcrumb-item text-muted">
              <a href="{{ route('rkpd.tahap-penjadwalan.index') }}" class="text-muted text-hover-primary">Tahap Penjadwalan</a>
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
          <form action="{{ route('tahap-penjadwalan.update', $tahap->id_tahap) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Nama Tahap -->
            <div class="fv-row mb-7">
              <label class="required fs-6 fw-semibold mb-2">Nama Tahap</label>
              <input type="text" 
                     class="form-control form-control-solid @error('nama_tahap') is-invalid @enderror" 
                     name="nama_tahap"
                     value="{{ old('nama_tahap', $tahap->nama_tahap) }}" 
                     maxlength="255" 
                     required />
              @error('nama_tahap')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <!-- Actions -->
            <div class="d-flex justify-content-end">
              <a href="{{ route('rkpd.tahap-penjadwalan.index') }}" class="btn btn-light me-3">Batal</a>
              <button type="submit" class="btn btn-primary">Update</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
