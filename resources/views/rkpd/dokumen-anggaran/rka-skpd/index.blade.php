@extends('layouts.master')

@section('content')
  <div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-fluid">
      <div class="card">
        <div class="card-header border-0 pt-6">
          <div class="card-title">
            <h2>RKA SKPD</h2>
          </div>
        </div>

        <div class="card-body pt-0">
          <table class="table table-bordered align-middle" id="kt_table_rka_skpd">
            <thead>
              <tr class="fw-bold fs-3">
                <th>NAMA SKPD</th>
                <th class="text-start">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($skpdList as $skpd)
                <tr>
                  <td>{{ $skpd->kode_skpd }} {{ $skpd->nama_skpd }}</td>
                  <td class="text-start">
                    <a href="#" class="btn btn-active-light-primary btn-cetak-rka-skpd hover-scale" data-id-skpd="{{ $skpd->id_skpd }}"
                      data-nama-skpd="{{ $skpd->nama_skpd }}" data-url-ttd-default="{{ route('rka-skpd.ttd-default', $skpd->id_skpd) }}"
                      title="Cetak Rincian RKA SKPD">
                      <i class="ki-outline ki-printer fs-3 me-2 text-black"></i>
                      Cetak RKA
                    </a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  @include('rkpd.dokumen-anggaran.rka-skpd.partials.modal-cetak')
@endsection

@push('scripts')
  @include('rkpd.dokumen-anggaran.rka-skpd.partials.js')
@endpush
