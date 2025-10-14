@extends('layouts.master')

@section('content')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
  <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
    <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
      <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
        <h1 class="page-heading text-dark fw-bold fs-3 m-0">Edit Jadwal RKPD</h1>
        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
          <li class="breadcrumb-item text-muted">
            <a href="{{ url('/home') }}" class="text-muted text-hover-primary">Home</a>
          </li>
          <li class="breadcrumb-item">
            <span class="bullet bg-gray-400 w-5px h-2px"></span>
          </li>
          <li class="breadcrumb-item text-muted">RKPD</li>
          <li class="breadcrumb-item">
            <span class="bullet bg-gray-400 w-5px h-2px"></span>
          </li>
          <li class="breadcrumb-item text-muted">
            <a href="{{ route('rkpd.jadwal-rkpd.index') }}" class="text-muted text-hover-primary">Jadwal RKPD</a>
          </li>
          <li class="breadcrumb-item">
            <span class="bullet bg-gray-400 w-5px h-2px"></span>
          </li>
          <li class="breadcrumb-item text-dark">Edit Jadwal</li>
        </ul>
      </div>
    </div>
  </div>
</div>

<div id="kt_app_content" class="app-content flex-column-fluid">
  <div id="kt_app_content_container" class="app-container container-fluid">

    <div id="session-messages" style="display:none;">
      @if (session('success'))
        <div data-type="success" data-message="{{ session('success') }}"></div>
      @endif
      @if (session('error'))
        <div data-type="error" data-message="{{ session('error') }}"></div>
      @endif
    </div>

    <div class="card">
      <div class="card-header border-0 pt-6">
        <div class="card-title">
          <h2 class="fw-bold mb-0">Form Edit Jadwal RKPD</h2>
        </div>
      </div>

      <form action="{{ route('rkpd.jadwal-rkpd.update', $jadwal->id_jadwal) }}" method="POST" class="form" id="formEditJadwal">
        @csrf
        @method('PUT')

        <div class="card-body pt-0">

          <div class="fv-row mb-7">
            <label class="required fs-6 fw-semibold mb-2">Sub Tahap</label>
            <select class="form-select form-select-solid" name="id_sub_tahap" required>
              <option value="">Pilih Sub Tahap</option>
              @foreach ($subTahap as $st)
                <option value="{{ $st->id_sub_tahap }}" 
                  {{ $jadwal->id_sub_tahap == $st->id_sub_tahap ? 'selected' : '' }}>
                  {{ $st->tahap->nama_tahap ?? '-' }} — {{ $st->nama_sub_tahap }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="fv-row mb-7">
            <label class="required fs-6 fw-semibold mb-2">Waktu Mulai</label>
            <input type="datetime-local" name="waktu_mulai" class="form-control form-control-solid" 
              value="{{ $jadwal->waktu_mulai ? \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('Y-m-d\TH:i') : '' }}" required />
          </div>

          <div class="fv-row mb-7">
            <label class="required fs-6 fw-semibold mb-2">Waktu Selesai</label>
            <input type="datetime-local" name="waktu_selesai" class="form-control form-control-solid" 
              value="{{ $jadwal->waktu_selesai ? \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('Y-m-d\TH:i') : '' }}" required />
          </div>

        </div>

        <div class="card-footer d-flex justify-content-between">
          <button type="submit" id="btnUpdateJadwal" class="btn btn-primary">
            <span class="indicator-label">Simpan Perubahan</span>
            <span class="indicator-progress">Menyimpan...
              <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </span>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
  const sessionMessages = document.querySelectorAll('#session-messages div');
  sessionMessages.forEach(msg => {
    Swal.fire({
      icon: msg.dataset.type,
      title: msg.dataset.type === 'success' ? 'Berhasil' : 'Gagal',
      text: msg.dataset.message,
      confirmButtonText: 'OK',
      buttonsStyling: false,
      customClass: { confirmButton: "btn btn-primary" }
    });
  });
});
</script>
@endsection
