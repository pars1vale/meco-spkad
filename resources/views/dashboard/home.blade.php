@extends('layouts.master')
@section('content')
  <div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
      <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
        <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
          <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
            <h1><span id="kt_typedjs_banner" class="fs-1 fw-bold"></span></h1>
          </div>
          <div class="d-flex align-items-center gap-2 gap-lg-3">
            <span class="badge badge-light-primary fs-7 fw-bold px-4 py-2">
              <i class="ki-outline ki-calendar fs-6 me-1"></i>
              Tahun Anggaran: {{ session('tahun_anggaran', '-') }}
            </span>
          </div>
        </div>
      </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
      <div id="kt_app_content_container" class="app-container container-fluid">

        <div class="row gx-5 gx-xl-10 mb-5">
          {{-- Card: Terakhir di-update --}}
          <div class="col-xl-4 col-md-6 mb-5">
            <div class="card h-100">
              <div class="card-body d-flex align-items-center py-4 px-6">
                <div class="symbol symbol-50px me-4">
                  <span class="symbol-label bg-light-primary">
                    <i class="ki-duotone ki-arrows-circle fs-2x text-primary">
                      <span class="path1"></span>
                      <span class="path2"></span>
                    </i>
                  </span>
                </div>
                <div class="d-flex flex-column">
                  <span class="text-gray-500 fw-semibold fs-7 mb-1">Terakhir di-update</span>
                  <span class="text-gray-800 fw-bold fs-6">Selasa, 10 Maret 2026</span>
                  <span class="text-gray-500 fw-semibold fs-8">Pukul 0:43</span>
                </div>
              </div>
            </div>
          </div>
          {{-- Card: Total Anggaran SPM --}}
          <div class="col-xl-4 col-md-6 mb-5">
            <div class="card h-100">
              <div class="card-body d-flex align-items-center py-4 px-6">
                <div class="symbol symbol-50px me-4">
                  <span class="symbol-label bg-light-info">
                    <i class="ki-duotone ki-chart-line-down fs-2x text-info">
                      <span class="path1"></span>
                      <span class="path2"></span>
                    </i>
                  </span>
                </div>
                <div class="d-flex flex-column">
                  <span class="text-gray-500 fw-semibold fs-7 mb-1">Total Anggaran SPM</span>
                  <span class="text-gray-800 fw-bold fs-6">Rp 66.542.928.090,00</span>
                </div>
              </div>
            </div>
          </div>
          {{-- Card: Total Anggaran Kemiskinan Ekstrem --}}
          <div class="col-xl-4 col-md-6 mb-5">
            <div class="card h-100">
              <div class="card-body d-flex align-items-center py-4 px-6">
                <div class="symbol symbol-50px me-4">
                  <span class="symbol-label bg-light-danger">
                    <i class="ki-duotone ki-shield-tick fs-2x text-danger">
                      <span class="path1"></span>
                      <span class="path2"></span>
                    </i>
                  </span>
                </div>
                <div class="d-flex flex-column">
                  <span class="text-gray-500 fw-semibold fs-7 mb-1">Total Anggaran Kemiskinan Ekstrem</span>
                  <span class="text-gray-800 fw-bold fs-6">Rp 918.363.878.888,00</span>
                </div>
              </div>
            </div>
          </div>
          {{-- Card: Total Pendapatan --}}
          <div class="col-xl-4 col-md-6 mb-5">
            <div class="card h-100">
              <div class="card-body d-flex align-items-center py-4 px-6">
                <div class="symbol symbol-50px me-4">
                  <span class="symbol-label bg-light-success">
                    <i class="ki-duotone ki-wallet fs-2x text-success">
                      <span class="path1"></span>
                      <span class="path2"></span>
                      <span class="path3"></span>
                      <span class="path4"></span>
                    </i>
                  </span>
                </div>
                <div class="d-flex flex-column">
                  <span class="text-gray-500 fw-semibold fs-7 mb-1">Total Pendapatan</span>
                  <span class="text-gray-800 fw-bold fs-6">Rp 2.004.703.597.918,00</span>
                </div>
              </div>
            </div>
          </div>
          {{-- Card: Total Belanja --}}
          <div class="col-xl-4 col-md-6 mb-5">
            <div class="card h-100">
              <div class="card-body d-flex align-items-center py-4 px-6">
                <div class="symbol symbol-50px me-4">
                  <span class="symbol-label bg-light-primary">
                    <i class="ki-duotone ki-basket fs-2x text-primary">
                      <span class="path1"></span>
                      <span class="path2"></span>
                      <span class="path3"></span>
                      <span class="path4"></span>
                    </i>
                  </span>
                </div>
                <div class="d-flex flex-column">
                  <span class="text-gray-500 fw-semibold fs-7 mb-1">Total Belanja</span>
                  <span class="text-gray-800 fw-bold fs-6">Rp 1.946.662.619.480,00</span>
                </div>
              </div>
            </div>
          </div>
          {{-- Card: Total Pembiayaan --}}
          <div class="col-xl-4 col-md-6 mb-5">
            <div class="card h-100">
              <div class="card-body d-flex align-items-center py-4 px-6">
                <div class="symbol symbol-50px me-4">
                  <span class="symbol-label bg-light-warning">
                    <i class="ki-duotone ki-calculator fs-2x text-warning">
                      <span class="path1"></span>
                      <span class="path2"></span>
                      <span class="path3"></span>
                    </i>
                  </span>
                </div>
                <div class="d-flex flex-column">
                  <span class="text-gray-500 fw-semibold fs-7 mb-1">Total Pembiayaan</span>
                  <span class="text-danger fw-bold fs-6">-Rp 114.000.000.000,00</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row gx-5 gx-xl-10 mb-5">
          {{-- total SKPD --}}
          <div class="col-xl-2 col-md-6 mb-5">
            <div class="card h-100">
              <div class="card-body p-7">
                <div class="mb-7">
                  <span class="text-gray-800 fw-bold fs-4 d-block mb-1">SKPD</span>
                  <span class="text-gray-400 fw-semibold fs-6">Terdata : </span>
                </div>
                <div class="mb-4">
                  <span class="text-gray-900 fw-bolder lh-1" style="font-size: 4rem;">{{ $totalSkpd }}</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                  <span class="symbol symbol-30px">
                    <span class="symbol-label bg-success rounded-circle">
                      <i class="ki-duotone ki-arrow-up-right fs-5 text-white">
                        <span class="path1"></span>
                        <span class="path2"></span>
                      </i>
                    </span>
                  </span>
                  <span class="text-gray-600 fw-semibold fs-6">Terdaftar</span>
                </div>
              </div>
            </div>
          </div>
          {{-- total unit SKPD --}}
          <div class="col-xl-2 col-md-6 mb-5">
            <div class="card h-100">
              <div class="card-body p-7">
                <div class="mb-7">
                  <span class="text-gray-800 fw-bold fs-4 d-block mb-1">Unit SKPD </span>
                  <span class="text-gray-400 fw-semibold fs-6">Terdata :</span>
                </div>
                <div class="mb-4">
                  <span class="text-gray-900 fw-bolder lh-1" style="font-size: 4rem;">{{ $totalUnitSkpd }}</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                  <span class="symbol symbol-30px">
                    <span class="symbol-label bg-success rounded-circle">
                      <i class="ki-duotone ki-arrow-up-right fs-5 text-white">
                        <span class="path1"></span>
                        <span class="path2"></span>
                      </i>
                    </span>
                  </span>
                  <span class="text-gray-600 fw-semibold fs-6">Terdaftar</span>
                </div>
              </div>
            </div>
          </div>
          {{-- tabel total data referensi --}}
          <div class="col-xl-4 col-md-6 mb-5">
            <div class="card card-flush h-lg-100">
              <div class="card-header pt-5">
                <h3 class="card-title text-gray-800">Data Referensi</h3>
              </div>
              <div class="card-body pt-5">
                <div class="d-flex flex-stack">
                  <div class="text-gray-700 fw-semibold fs-6 me-2">Program</div>
                  <div class="d-flex align-items-senter">
                    <span class="text-gray-900 fw-bolder fs-6">{{ $totalProgram }}<span class="text-gray-500 fw-bold fs-6"> Item</span></span>

                  </div>
                </div>
                <div class="separator separator-dashed my-3"></div>
                <div class="d-flex flex-stack">
                  <div class="text-gray-700 fw-semibold fs-6 me-2">Kegiatan</div>
                  <div class="d-flex align-items-senter">
                    <span class="text-gray-900 fw-bolder fs-6">{{ $totalKegiatan }} <span class="text-gray-500 fw-bold fs-6"> Item</span></span>
                  </div>
                </div>
                <div class="separator separator-dashed my-3"></div>
                <div class="d-flex flex-stack">
                  <div class="text-gray-700 fw-semibold fs-6 me-2">Sub Kegiatan</div>
                  <div class="d-flex align-items-senter">
                    <span class="text-gray-900 fw-bolder fs-6">{{ $totalSubKegiatan }} <span class="text-gray-500 fw-bold fs-6"> Item</span></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          {{-- tabel total data jenis rekening --}}
          <div class="col-xl-4 col-md-6 mb-5">
            <div class="card card-flush h-lg-100">
              <div class="card-header pt-5">
                <h3 class="card-title text-gray-800">Jenis Rekening</h3>
              </div>
              <div class="card-body pt-5">
                <div class="d-flex flex-stack">
                  <div class="text-gray-700 fw-semibold fs-6 me-2">Rekening Pendapatan</div>
                  <div class="d-flex align-items-senter">
                    <span class="text-gray-900 fw-bolder fs-6">{{ $totalRekPendapatan }} <span class="text-gray-500 fw-bold fs-6"> Item</span></span>
                  </div>
                </div>
                <div class="separator separator-dashed my-3"></div>
                <div class="d-flex flex-stack">
                  <div class="text-gray-700 fw-semibold fs-6 me-2">Rekening Belanja</div>
                  <div class="d-flex align-items-senter">
                    <span class="text-gray-900 fw-bolder fs-6">{{ $totalRekBelanja }} <span class="text-gray-500 fw-bold fs-6"> Item</span></span>
                  </div>
                </div>
                <div class="separator separator-dashed my-3"></div>
                <div class="d-flex flex-stack">
                  <div class="text-gray-700 fw-semibold fs-6 me-2">Rekening Pembiayaan</div>
                  <div class="d-flex align-items-senter">
                    <span class="text-gray-900 fw-bolder fs-6">{{ $totalRekPembiayaan }} <span class="text-gray-500 fw-bold fs-6"> Item</span></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row gx-5 gx-xl-10 mb-5">

          {{-- Card: Total Belanja Hibah --}}
          <div class="col-xl-4 col-md-6 mb-5">
            <div class="card h-100">
              <div class="card-body d-flex align-items-center py-4 px-6">
                <div class="symbol symbol-50px me-4">
                  <span class="symbol-label bg-light-success">
                    <i class="ki-duotone ki-delivery-3 fs-2x text-success">
                      <span class="path1"></span>
                      <span class="path2"></span>
                      <span class="path3"></span>
                      <span class="path4"></span>
                    </i>
                  </span>
                </div>
                <div class="d-flex flex-column">
                  <span class="text-gray-500 fw-semibold fs-7 mb-1">Total Belanja Hibah</span>
                  <span class="text-gray-800 fw-bold fs-6">{{ rupiah($totalBelanjaHibah) }}</span>
                </div>
              </div>
            </div>
          </div>
          {{-- Card: Total  Bantuan Sosial --}}
          <div class="col-xl-4 col-md-6 mb-5">
            <div class="card h-100">
              <div class="card-body d-flex align-items-center py-4 px-6">
                <div class="symbol symbol-50px me-4">
                  <span class="symbol-label bg-light-primary">
                    <i class="ki-duotone ki-basket fs-2x text-primary">
                      <span class="path1"></span>
                      <span class="path2"></span>
                      <span class="path3"></span>
                      <span class="path4"></span>
                    </i>
                  </span>
                </div>
                <div class="d-flex flex-column">
                  <span class="text-gray-500 fw-semibold fs-7 mb-1">Total Belanja Bantuan Sosial</span>
                  <span class="text-gray-800 fw-bold fs-6">{{ rupiah($totalBelanjaBansos) }}</span>
                </div>
              </div>
            </div>
          </div>
          {{-- Card: Total Belanja Bantuan Keuangan --}}
          <div class="col-xl-4 col-md-6 mb-5">
            <div class="card h-100">
              <div class="card-body d-flex align-items-center py-4 px-6">
                <div class="symbol symbol-50px me-4">
                  <span class="symbol-label bg-light-info">
                    <i class="ki-duotone ki-calculator fs-2x text-info">
                      <span class="path1"></span>
                      <span class="path2"></span>
                      <span class="path3"></span>
                    </i>
                  </span>
                </div>
                <div class="d-flex flex-column">
                  <span class="text-gray-500 fw-semibold fs-7 mb-1">Total Belanja Bantuan Keuangan</span>
                  <span class="text-gray-800 fw-bold fs-6">{{ rupiah($totalBelanjaBankeu) }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@push('scripts')
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      var typed = new Typed("#kt_typedjs_banner", {
        strings: [
          "Dashboard Analisis Anggaran.",
          "Monitoring Pendapatan Daerah.",
          "Transparansi Belanja Publik.",
          "Data Kemiskinan Ekstrem.",
          "Data Anggaran SPM.",
          "Jumlah Pendapatan.",
          "Jumlah Belanja.",
          "Jumlah Pembiayaan."
        ],
        typeSpeed: 45,
        backSpeed: 15,
        loop: true
      });
    });
  </script>
@endpush
