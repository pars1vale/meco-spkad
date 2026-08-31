@extends('layouts.master')

@section('title', 'RKA Pembiayaan')

@section('content')
  <div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-fluid">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Rencana Kerja dan Anggaran (RKA) Pembiayaan</h3>
        </div>
        <div class="card-body">
          <table class="table table-row-bordered align-middle">
            <thead>
              <tr class="text-start fw-bold fs-7 text-uppercase gs-0">
                <th style="width: 5%;">No</th>
                <th>Kode SKPD</th>
                <th>Nama SKPD</th>
                <th style="width: 15%;" class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($skpdList as $i => $skpd)
                <tr>
                  <td>{{ $i + 1 }}</td>
                  <td>{{ $skpd->kode_skpd }}</td>
                  <td>{{ $skpd->nama_skpd }}</td>
                  <td class="text-center">
                    <button type="button" class="btn btn-sm btn-primary btn-cetak-rka-pembiayaan" data-id-skpd="{{ $skpd->id_skpd }}"
                      data-nama-skpd="{{ $skpd->nama_skpd }}" data-url-ttd-default="{{ route('rka-pembiayaan.ttd-default', $skpd->id_skpd) }}">
                      Cetak
                    </button>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="text-center text-muted">Belum ada data SKPD untuk tahun anggaran ini.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  @include('rkpd.dokumen-anggaran.rka-pembiayaan.partials.modal-cetak')
@endsection

@push('scripts')
  @include('rkpd.dokumen-anggaran.rka-pembiayaan.partials.js')
@endpush
