@extends('layouts.master')

@section('content')
  <x-toolbar title="Renja" :breadcrumbs="[['label' => 'Home', 'url' => url('/')], ['label' => 'RKPD'], ['label' => 'Renja']]" />

  <div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-fluid">
      <div id="session-messages" style="display: none;">
        @if (session('success'))
          <div data-type="success" data-message="{{ session('success') }}"></div>
        @endif
        @if (session('error'))
          <div data-type="error" data-message="{{ session('error') }}"></div>
        @endif
      </div>
      {{-- SKPD selector: wajib dipilih dulu sebelum summary & tree tampil --}}
      <div class="card mb-5">
        <div class="card-body d-flex align-items-center gap-3">
          <label class="fs-6 fw-semibold mb-0 text-nowrap">Pilih SKPD</label>
          <select class="form-select form-select-solid w-100 w-md-400px" id="filter_skpd" data-control="select2" data-placeholder="-- Pilih SKPD --">
            <option value="">-- Pilih SKPD --</option>
            @foreach ($data_unit as $unit)
              <option value="{{ $unit->id_skpd }}">{{ $unit->kode_skpd }} - {{ $unit->nama_skpd }}</option>
            @endforeach
          </select>
        </div>
      </div>

      {{-- Placeholder sebelum SKPD dipilih --}}
      <div id="belum_pilih_skpd" class="alert alert-light-primary d-flex align-items-center p-5 rounded mb-5">
        <i class="ki-outline ki-information fs-2hx me-3 text-primary"></i>
        <div class="d-flex flex-column">
          <h4 class="mb-1 text-primary">Pilih SKPD terlebih dahulu</h4>
          <span>Ringkasan pagu dan daftar sub kegiatan akan tampil setelah SKPD dipilih.</span>
        </div>
      </div>

      <div id="konten_skpd" style="display:none;">

        {{-- header card --}}
        <div class="row gx-5 gx-xl-10 mb-xl-10">
          {{-- inputan, batasan, validasi PAGU --}}
          <div class="col-md-6 col-lg-4 col-xl-4 mb-4">
            <div class="card card-flush border-0 mb-5 mb-xl-10" style="background-color: #080655; min-height: 150px;">
              <div class="card-body d-flex flex-column justify-content-center align-items-start">
                <span class="fs-2hx fw-bold text-white lh-1 ls-n2">
                  <span id="card_batasan_pagu">Rp 0</span>
                </span>
                <span class="text-white opacity-75 pt-2 fw-semibold fs-6">Batasan Pagu</span>
              </div>
            </div>
          </div>

          <div class="col-md-6 col-lg-4 col-xl-4 mb-4">
            <div class="card card-flush border-0 mb-5 mb-xl-10" style="background-color: #1C325E; min-height: 150px;">
              <div class="card-body d-flex flex-column justify-content-center align-items-start">
                <span class="fs-2hx fw-bold text-white lh-1 ls-n2">
                  <span id="card_inputan_pagu">Rp 0</span>
                </span>
                <span class="text-white opacity-75 pt-2 fw-semibold fs-6">Inputan Pagu</span>
              </div>
            </div>
          </div>

          <div class="col-md-6 col-lg-4 col-xl-4 mb-4">
            <div class="card card-flush border-0 mb-5 mb-xl-10" style="background-color: #7239EA; min-height: 150px;">
              <div class="card-body d-flex flex-column justify-content-center align-items-start">
                <span class="fs-2hx fw-bold text-white lh-1 ls-n2">
                  <span id="card_pagu_validasi">Rp 0</span>
                </span>
                <span class="text-white opacity-75 pt-2 fw-semibold fs-6">Pagu Validasi</span>
              </div>
            </div>
          </div>

          {{-- rincian, realisasi, persentase --}}
          <div class="col-md-6 col-lg-4 col-xl-4 mb-4">
            <div class="card card-flush border-0 mb-5 mb-xl-10" style="background-color: #F1416C; min-height: 150px;">
              <div class="card-body d-flex flex-column justify-content-center align-items-start">
                <span class="fs-2hx fw-bold text-white lh-1 ls-n2">
                  <span id="card_rincian">Rp 0</span>
                </span>
                <span class="text-white opacity-75 pt-2 fw-semibold fs-6">Rincian</span>
              </div>
            </div>
          </div>

          <div class="col-md-6 col-lg-4 col-xl-4 mb-4">
            <div class="card card-flush border-0 mb-5 mb-xl-10" style="background-color: #00A3FF; min-height: 150px;">
              <div class="card-body d-flex flex-column justify-content-center align-items-start">
                <span class="fs-2hx fw-bold text-white lh-1 ls-n2">
                  <span id="card_realisasi">Rp 0</span>
                </span>
                <span class="text-white opacity-75 pt-2 fw-semibold fs-6">Realisasi</span>
              </div>
            </div>
          </div>

          <div class="col-md-6 col-lg-4 col-xl-4 mb-4">
            <div class="card card-flush border-0 mb-5 mb-xl-10" style="background-color: #50CD89; min-height: 150px;">
              <div class="card-body d-flex flex-column justify-content-center align-items-start">
                <span class="fs-2hx fw-bold text-white lh-1 ls-n2">
                  <span id="card_persentase">0,00%</span>
                </span>
                <span class="text-white opacity-75 pt-2 fw-semibold fs-6">Persentase</span>
              </div>
            </div>
          </div>
        </div>
        {{-- content card --}}
        <div class="card">
          <div class="card-header border-0 pt-6">
            <div class="card-title">
              <div class="d-flex align-items-center position-relative my-1">
                <i class="ki-outline ki-magnifier fs-3 position-absolute ms-4"></i>
                <input type="text" id="kt_datatable_search_input" class="form-control form-control-solid w-250px ps-12" placeholder="Cari">
              </div>
            </div>
            <div class="card-toolbar">
              <div class="dropdown">
                <button class="btn btn-sm btn-success dropdown-toggle" type="button" id="btnExportPdfDropdown" data-bs-toggle="dropdown"
                  aria-expanded="false">
                  <i class="ki-outline ki-file-down fs-3"></i>
                  Export PDF
                </button>
                <div class="dropdown-menu p-4" style="min-width: 280px;" aria-labelledby="btnExportPdfDropdown">
                  <div class="mb-3">
                    <label class="form-label fs-7 fw-semibold">Pilih SKPD</label>
                    <select class="form-select form-select-sm" id="export_pdf_id_skpd">
                      <option value="">-- Pilih SKPD --</option>
                      @foreach ($data_unit as $unit)
                        <option value="{{ $unit->id_skpd }}">{{ $unit->kode_skpd }} - {{ $unit->nama_skpd }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="mb-3">
                    <label class="form-label fs-7 fw-semibold">Tahun Anggaran</label>
                    <input type="number" class="form-control form-control-sm" id="export_pdf_tahun" value="{{ date('Y') }}" min="2020"
                      max="2100">
                  </div>
                  <button type="button" class="btn btn-sm btn-primary w-100" id="btnDoExportPdf">
                    <i class="ki-outline ki-file-down fs-4"></i>
                    Download PDF
                  </button>
                </div>
              </div>
              <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                <div class="w-150px me-3"></div>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_kegiatan">Tambah</button>
              </div>
              <div class="d-flex justify-content-end align-items-center d-none" data-kt-customer-table-toolbar="selected">
                <div class="fw-bold me-5">
                  <span class="me-2" data-kt-customer-table-select="selected_count"></span>Terpilih
                </div>
                <button type="button" class="btn btn-danger" id="bulk_delete_btn">Hapus yg Terpilih</button>
              </div>
            </div>
          </div>

          <div class="card-body pt-0">
            <table id="kt_datatable_column_rendering" class="table table-striped table-row-bordered gy-5 gs-7">
              <thead>
                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                  <th class="min-w-10px" style="display:none;">Check</th>
                  <th class="min-w-10px" style="display:none;">SKPD</th>
                  <th class="min-w-10px" style="display:none;">Urusan</th>
                  <th class="min-w-10px" style="display:none;">Program</th>
                  <th class="min-w-10px" style="display:none;">Kegiatan</th>
                  <th class="min-w-300px">Sub Kegiatan</th>
                  <th class="min-w-100px">Status Sub Kegiatan</th>
                  <th class="min-w-100px">Status Rincian</th>
                  <th class="min-w-150px">Sebelum Perubahan</th>
                  <th class="min-w-150px">Pagu Validasi Setelah Perubahan</th>
                  <th class="min-w-150px">Total Rincian Setelah Perubahan</th>
                  <th class="min-w-100px">Total Realisasi</th>
                  <th class="min-w-100px">Persentase</th>
                  <th class="min-w-100px">Aksi</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  @include('rkpd.renja.partials.modal-add-kegiatan')
  @include('rkpd.renja.partials.modal-cetak-rincian')

  <style>
    .dtrg-group {
      font-weight: 600;
      font-size: 14px;
      padding: 12px 15px !important;
    }

    .dtrg-level-0 {
      background-color: #1e4d8f !important;
      color: #ffffff;
      font-size: 15px;
    }

    .dtrg-level-1 {
      background-color: #3f6cb0 !important;
      padding-left: 30px !important;
      color: #ffffff;
    }

    .dtrg-level-2 {
      background-color: #a9c6ee !important;
      padding-left: 50px !important;
      color: #112233;
    }

    .dtrg-level-3 {
      background-color: #dbe7fa !important;
      padding-left: 70px !important;
      color: #112233;
    }

    /* Sub kegiatan row styling */
    table.dataTable tbody tr {
      background-color: #ffffff;
    }

    table.dataTable tbody tr:hover {
      background-color: #f8f9fa !important;
    }

    /* Collapse button */
    .btn-collapse {
      transition: transform 0.3s;
    }

    .btn-collapse.collapsed {
      transform: rotate(-90deg);
    }
  </style>

  @include('rkpd.renja.partials.js')
@endsection
