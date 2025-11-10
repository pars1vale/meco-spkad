@extends('layouts.master')

@section('content')
  <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
      <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
          <h1 class="page-heading text-dark fw-bold fs-3 m-0">Renja</h1>
          <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
            <li class="breadcrumb-item text-muted">
              <a href="{{ url('/home') }}" class="text-muted text-hover-primary">Home</a>
            </li>
            <li class="breadcrumb-item">
              <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">Rkpd</li>
            <li class="breadcrumb-item">
              <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">Renja</li>
          </ul>
        </div>
      </div>
    </div>
  </div>

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

      <div class="row gx-5 gx-xl-10 mb-xl-10">
        <!--begin::Col-->
        <div class="col-md-6 col-lg-4 col-xl-4 mb-10">
          <!--begin::Card widget 16-->
          <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-center border-0 mb-5 mb-xl-10"
            style="background-color: #080655; min-height: 200px;">
            <!--begin::Header-->
            <div class="card-header pt-5">
              <!--begin::Title-->
              <div class="card-title d-flex flex-column">
                <!--begin::Amount-->
                <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">Batasan Pagu</span>
                <!--end::Amount-->
                <!--begin::Subtitle-->
                <span class="text-white opacity-50 pt-1 fw-semibold fs-6">Active Projects</span>
                <!--end::Subtitle-->
              </div>
              <!--end::Title-->
            </div>
            <!--end::Header-->
            <!--begin::Card body-->
            <div class="card-body d-flex align-items-end pt-0 pb-5">
              <!--begin::Progress-->
              <div class="d-flex align-items-center flex-column mt-3 w-100">
                <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-50 w-100 mt-auto mb-2">
                  <span>43 Pending</span>
                  <span>72%</span>
                </div>
                <div class="h-8px w-100 bg-light-danger rounded">
                  <div class="bg-danger rounded h-8px" role="progressbar" style="width: 72%;" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100">
                  </div>
                </div>
              </div>
              <!--end::Progress-->
            </div>
            <!--end::Card body-->
          </div>
          <!--end::Card widget 16-->
        </div>
        <!--end::Col-->

        <!--begin::Col-->
        <div class="col-md-6 col-lg-4 col-xl-4 mb-10">
          <!--begin::Card widget 16-->
          <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-center border-0 mb-5 mb-xl-10"
            style="background-color: #1C325E; min-height: 200px;">
            <!--begin::Header-->
            <div class="card-header pt-5">
              <!--begin::Title-->
              <div class="card-title d-flex flex-column">
                <!--begin::Amount-->
                <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">Inputan Pagu</span>
                <!--end::Amount-->
                <!--begin::Subtitle-->
                <span class="text-white opacity-50 pt-1 fw-semibold fs-6">Completed Tasks</span>
                <!--end::Subtitle-->
              </div>
              <!--end::Title-->
            </div>
            <!--end::Header-->
            <!--begin::Card body-->
            <div class="card-body d-flex align-items-end pt-0 pb-5">
              <!--begin::Progress-->
              <div class="d-flex align-items-center flex-column mt-3 w-100">
                <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-50 w-100 mt-auto mb-2">
                  <span>38 Done</span>
                  <span>85%</span>
                </div>
                <div class="h-8px w-100 bg-light-success rounded">
                  <div class="bg-success rounded h-8px" role="progressbar" style="width: 85%;" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100">
                  </div>
                </div>
              </div>
              <!--end::Progress-->
            </div>
            <!--end::Card body-->
          </div>
          <!--end::Card widget 16-->
        </div>
        <!--end::Col-->

        <!--begin::Col-->
        <div class="col-md-6 col-lg-4 col-xl-4 mb-10">
          <!--begin::Card widget 16-->
          <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-center border-0 mb-5 mb-xl-10"
            style="background-color: #7239EA; min-height: 200px;">
            <!--begin::Header-->
            <div class="card-header pt-5">
              <!--begin::Title-->
              <div class="card-title d-flex flex-column">
                <!--begin::Amount-->
                <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">Pagu Validasi</span>
                <!--end::Amount-->
                <!--begin::Subtitle-->
                <span class="text-white opacity-50 pt-1 fw-semibold fs-6">Total Clients</span>
                <!--end::Subtitle-->
              </div>
              <!--end::Title-->
            </div>
            <!--end::Header-->
            <!--begin::Card body-->
            <div class="card-body d-flex align-items-end pt-0 pb-5">
              <!--begin::Progress-->
              <div class="d-flex align-items-center flex-column mt-3 w-100">
                <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-50 w-100 mt-auto mb-2">
                  <span>89 Active</span>
                  <span>63%</span>
                </div>
                <div class="h-8px w-100 bg-light-primary rounded">
                  <div class="bg-primary rounded h-8px" role="progressbar" style="width: 63%;" aria-valuenow="63" aria-valuemin="0" aria-valuemax="100">
                  </div>
                </div>
              </div>
              <!--end::Progress-->
            </div>
            <!--end::Card body-->
          </div>
          <!--end::Card widget 16-->
        </div>
        <!--end::Col-->

        <!--begin::Col-->
        <div class="col-md-6 col-lg-4 col-xl-4 mb-10">
          <!--begin::Card widget 16-->
          <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-center border-0 mb-5 mb-xl-10"
            style="background-color: #F1416C; min-height: 200px;">
            <!--begin::Header-->
            <div class="card-header pt-5">
              <!--begin::Title-->
              <div class="card-title d-flex flex-column">
                <!--begin::Amount-->
                <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">Rincian</span>
                <!--end::Amount-->
                <!--begin::Subtitle-->
                <span class="text-white opacity-50 pt-1 fw-semibold fs-6">Overdue Items</span>
                <!--end::Subtitle-->
              </div>
              <!--end::Title-->
            </div>
            <!--end::Header-->
            <!--begin::Card body-->
            <div class="card-body d-flex align-items-end pt-0 pb-5">
              <!--begin::Progress-->
              <div class="d-flex align-items-center flex-column mt-3 w-100">
                <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-50 w-100 mt-auto mb-2">
                  <span>15 Critical</span>
                  <span>54%</span>
                </div>
                <div class="h-8px w-100 bg-light-warning rounded">
                  <div class="bg-warning rounded h-8px" role="progressbar" style="width: 54%;" aria-valuenow="54" aria-valuemin="0" aria-valuemax="100">
                  </div>
                </div>
              </div>
              <!--end::Progress-->
            </div>
            <!--end::Card body-->
          </div>
          <!--end::Card widget 16-->
        </div>
        <!--end::Col-->

        <!--begin::Col-->
        <div class="col-md-6 col-lg-4 col-xl-4 mb-10">
          <!--begin::Card widget 16-->
          <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-center border-0 mb-5 mb-xl-10"
            style="background-color: #00A3FF; min-height: 200px;">
            <!--begin::Header-->
            <div class="card-header pt-5">
              <!--begin::Title-->
              <div class="card-title d-flex flex-column">
                <!--begin::Amount-->
                <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">Realisasi</span>
                <!--end::Amount-->
                <!--begin::Subtitle-->
                <span class="text-white opacity-50 pt-1 fw-semibold fs-6">New Messages</span>
                <!--end::Subtitle-->
              </div>
              <!--end::Title-->
            </div>
            <!--end::Header-->
            <!--begin::Card body-->
            <div class="card-body d-flex align-items-end pt-0 pb-5">
              <!--begin::Progress-->
              <div class="d-flex align-items-center flex-column mt-3 w-100">
                <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-50 w-100 mt-auto mb-2">
                  <span>198 Unread</span>
                  <span>61%</span>
                </div>
                <div class="h-8px w-100 bg-light-info rounded">
                  <div class="bg-info rounded h-8px" role="progressbar" style="width: 61%;" aria-valuenow="61" aria-valuemin="0"
                    aria-valuemax="100"></div>
                </div>
              </div>
              <!--end::Progress-->
            </div>
            <!--end::Card body-->
          </div>
          <!--end::Card widget 16-->
        </div>
        <!--end::Col-->

        <!--begin::Col-->
        <div class="col-md-6 col-lg-4 col-xl-4 mb-10">
          <!--begin::Card widget 16-->
          <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-center border-0 mb-5 mb-xl-10"
            style="background-color: #50CD89; min-height: 200px;">
            <!--begin::Header-->
            <div class="card-header pt-5">
              <!--begin::Title-->
              <div class="card-title d-flex flex-column">
                <!--begin::Amount-->
                <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">Persentase</span>
                <!--end::Amount-->
                <!--begin::Subtitle-->
                <span class="text-white opacity-50 pt-1 fw-semibold fs-6">Team Members</span>
                <!--end::Subtitle-->
              </div>
              <!--end::Title-->
            </div>
            <!--end::Header-->
            <!--begin::Card body-->
            <div class="card-body d-flex align-items-end pt-0 pb-5">
              <!--begin::Progress-->
              <div class="d-flex align-items-center flex-column mt-3 w-100">
                <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-50 w-100 mt-auto mb-2">
                  <span>76 Online</span>
                  <span>85%</span>
                </div>
                <div class="h-8px w-100 bg-white bg-opacity-25 rounded">
                  <div class="bg-white rounded h-8px" role="progressbar" style="width: 85%;" aria-valuenow="85" aria-valuemin="0"
                    aria-valuemax="100"></div>
                </div>
              </div>
              <!--end::Progress-->
            </div>
            <!--end::Card body-->
          </div>
          <!--end::Card widget 16-->
        </div>
        <!--end::Col-->
      </div>

      <div id="session-messages" style="display: none;">
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
            <div class="d-flex align-items-center position-relative my-1">
              <i class="ki-outline ki-magnifier fs-3 position-absolute ms-4"></i>
              <input type="text" id="kt_datatable_search_input" class="form-control form-control-solid w-250px ps-12" placeholder="Cari">
            </div>
          </div>
          <div class="card-toolbar">
            <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
              <div class="w-150px me-3">
              </div>
              {{-- <button type="button" class="btn btn-light-primary me-3" data-bs-toggle="modal" data-bs-target="#kt_customers_export_modal">
                <i class="ki-outline ki-exit-up fs-2"></i>Export</button> --}}
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
          @if ($data->isEmpty())
            <div class="alert alert-warning d-flex align-items-center p-5 rounded">
              <i class="ki-outline ki-information fs-2hx me-3 text-warning"></i>
              <div class="d-flex flex-column">
                <h4 class="mb-1 text-warning">Tidak ada data</h4>
                <span>Tidak ada data yang ditemukan.</span>
              </div>
            </div>
          @else
            <table id="kt_datatable_column_rendering" class="table table-striped table-row-bordered gy-5 gs-7">
              <thead>
                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                  <th class="w-10px pe-2">
                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                      <input class="form-check-input" type="checkbox" data-kt-check="true"
                        data-kt-check-target="#kt_datatable_column_rendering .form-check-input" value="1" />
                    </div>
                  </th>
                  <th class="min-w-300px">Sub Kegiatan</th>
                  <th class="min-w-100px">Status Sub Kegiatan</th>
                  <th class="min-w-100px">Status Rincian</th>
                  <th class="min-w-100px">Sebelum Perubahan</th>
                  <th class="min-w-100px">Pagu Validasi Setelah Perubahan</th>
                  <th class="min-w-100px">Total Rincian Setelah Perubahan</th>
                  <th class="min-w-100px">Total Realisasi</th>
                  <th class="min-w-100px">Persentase</th>
                  <th class="min-w-100px">Aksi</th>
                </tr>
              </thead>
              {{--  <tbody class="fw-semibold text-gray-600">
                @foreach ($data as $item)
                  <tr>
                    <td>
                      <div class="form-check form-check-sm form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" value="{{ $item->id }}" />
                      </div>
                    </td>
                    <td class="fw-bold">{{ $item->kode_urusan }}</td>
                    <td>{{ $item->nama_urusan }}</td>
                    <td>
                      <div class="d-flex justify-content-end">
                        <a href="{{ route('urusan.edit', $item->id) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1"
                          title="Edit Urusan">
                          <i class="ki-outline ki-pencil fs-2"></i>
                        </a>
                        <form action="{{ route('urusan.destroy', $item->id) }}" method="POST" class="d-inline delete-form">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm delete-btn" title="Hapus Urusan"
                            data-name="{{ $item->nama_urusan }}">
                            <i class="ki-outline ki-trash fs-2"></i>
                          </button>
                        </form>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody> --}}
            </table>
          @endif
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Tambah kegiatan -->
  <div class="modal fade" id="kt_modal_add_kegiatan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-900px">
      <div class="modal-content">
        <form class="form" action="{{-- {{ route('renja.store') }} --}}" method="POST" id="kt_modal_add_kegiatan_form">
          @csrf
          <div class="modal-header" id="kt_modal_add_kegiatan_header">
            <h2 class="fw-bold">Tambah Sub Kegiatan Belanja</h2>
            <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
              <i class="ki-outline ki-cross fs-1"></i>
            </div>
          </div>

          <div class="modal-body py-10 px-lg-17">
            <div class="scroll-y me-n7 pe-7" id="kt_modal_add_kegiatan_scroll" style="max-height: 500px;">

              <div class="fv-row mb-7">
                <label class="required fs-6 fw-semibold mb-2">Pilih SKPD/Sub Unit</label>
                <select class="form-select form-select-solid @error('id_skpd') is-invalid @enderror" name="id_skpd" id="select_skpd"
                  data-control="select2" data-dropdown-parent="#kt_modal_add_kegiatan" required>
                  <option value="">Pilih SKPD</option>
                  @foreach ($data_unit as $skpd)
                    <option value="{{ $skpd->id_skpd }}" {{ old('id_skpd') == $skpd->id_skpd ? 'selected' : '' }}>
                      {{ $skpd->kode_skpd }} - {{ $skpd->nama_skpd }}
                    </option>
                  @endforeach
                </select>
                @error('id_skpd')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Loading indicator -->
              <div id="loading_sub_kegiatan" class="d-none">
                <div class="d-flex align-items-center">
                  <span class="spinner-border spinner-border-sm me-2"></span>
                  <span>Memuat sub kegiatan...</span>
                </div>
              </div>

              <!-- Sub Kegiatan List -->
              <div class="fv-row mb-7" id="sub_kegiatan_container" style="display: none;">
                <label class="required fs-6 fw-semibold mb-2">Sub Kegiatan</label>
                <select class="form-select form-select-solid @error('id_sub_kegiatan') is-invalid @enderror" name="id_sub_kegiatan"
                  id="select_sub_kegiatan" data-control="select2" data-dropdown-parent="#kt_modal_add_kegiatan" required>
                  <option value="">Pilih Sub Kegiatan</option>
                </select>
                @error('id_sub_kegiatan')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">
                  Total: <span id="total_sub_kegiatan">0</span> sub kegiatan
                </div>
              </div>

              <!-- Detail Sub Kegiatan yang dipilih -->
              <div id="detail_sub_kegiatan" class="alert alert-info d-none mt-5">
                <h5 class="mb-3">Detail Sub Kegiatan</h5>
                <table class="table table-sm table-borderless">
                  <tr>
                    <td width="150px"><strong>Bidang Urusan:</strong></td>
                    <td id="detail_bidang_urusan">-</td>
                  </tr>
                  <tr>
                    <td><strong>Program:</strong></td>
                    <td id="detail_program">-</td>
                  </tr>
                  <tr>
                    <td><strong>Kegiatan:</strong></td>
                    <td id="detail_kegiatan">-</td>
                  </tr>
                  <tr>
                    <td><strong>Sub Kegiatan:</strong></td>
                    <td id="detail_sub_keg">-</td>
                  </tr>
                </table>
              </div>

              <!-- Separator -->
              <div class="separator separator-dashed my-7"></div>

              <!-- Sumber Dana Section -->
              <div class="fv-row mb-7">
                <div class="d-flex justify-content-between align-items-center mb-5">
                  <label class="fs-6 fw-semibold">Sumber Dana</label>
                  <button type="button" class="btn btn-sm btn-light-primary" id="btn_add_sumber_dana">
                    <i class="ki-outline ki-plus fs-3"></i>
                    Sumber Dana
                  </button>
                </div>

                <!-- Container untuk dynamic forms -->
                <div id="sumber_dana_container">
                  <!-- Dynamic forms akan ditambahkan di sini -->
                </div>

                <!-- Info jika belum ada sumber dana -->
                <div id="no_sumber_dana_info" class="alert alert-light-warning d-flex align-items-center p-5">
                  <i class="ki-outline ki-information-5 fs-2hx text-warning me-4"></i>
                  <div class="d-flex flex-column">
                    <h4 class="mb-1 text-warning">Belum ada sumber dana</h4>
                    <span>Klik tombol "+ Sumber Dana" untuk menambahkan sumber dana dan pagu</span>
                  </div>
                </div>

                <!-- Total Pagu Summary -->
                <div id="total_pagu_summary" class="card bg-light-primary d-none mt-5">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                      <div>
                        <h5 class="mb-1">Total Pagu Keseluruhan</h5>
                        <span class="text-gray-600 fs-7">Jumlah dari semua sumber dana</span>
                      </div>
                      <h2 class="mb-0 text-primary" id="grand_total_pagu">Rp 0</h2>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>

          <div class="modal-footer flex-center">
            <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
            <button type="submit" id="kt_modal_add_kegiatan_submit" class="btn btn-primary">
              <span class="indicator-label">Simpan</span>
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
      // Store data globally
      let subKegiatanData = [];
      let sumberDanaCounter = 0;

      // Sumber Dana dari database
      const sumberDanaList = @json($sumberdana);

      // === AJAX Load Sub Kegiatan ketika SKPD dipilih ===
      $('#select_skpd').on('change', function() {
        const idSkpd = $(this).val();

        if (!idSkpd) {
          $('#sub_kegiatan_container').hide();
          $('#select_sub_kegiatan').html('<option value="">Pilih Sub Kegiatan</option>');
          $('#detail_sub_kegiatan').addClass('d-none');
          $('#total_sub_kegiatan').text('0');
          return;
        }

        // Show loading
        $('#loading_sub_kegiatan').removeClass('d-none');
        $('#sub_kegiatan_container').hide();
        $('#detail_sub_kegiatan').addClass('d-none');
        $('#select_sub_kegiatan').html('<option value="">Memuat...</option>');

        // AJAX request
        $.ajax({
          url: '{{ route('sub-kegiatan') }}',
          method: 'GET',
          data: {
            id_skpd: idSkpd,
            tahun_anggaran: 2025
          },
          success: function(response) {
            $('#loading_sub_kegiatan').addClass('d-none');

            if (response.success && response.data.length > 0) {
              subKegiatanData = response.data;

              // Group by bidang urusan
              let groupedData = {};
              response.data.forEach(item => {
                const bidangKey = item.kode_bidang_urusan;
                if (!groupedData[bidangKey]) {
                  groupedData[bidangKey] = {
                    nama: item.nama_bidang_urusan,
                    items: []
                  };
                }
                groupedData[bidangKey].items.push(item);
              });

              // Build options dengan optgroup
              let options = '<option value="">Pilih Sub Kegiatan</option>';
              Object.keys(groupedData).sort().forEach(key => {
                const group = groupedData[key];
                options += `<optgroup label="${key} - ${group.nama}">`;

                group.items.forEach(item => {
                  options += `<option value="${item.id_sub_kegiatan}" 
                                    data-bidang="${item.kode_bidang_urusan} - ${item.nama_bidang_urusan}"
                                    data-program="${item.kode_program} - ${item.nama_program}"
                                    data-kegiatan="${item.kode_kegiatan} - ${item.nama_kegiatan}"
                                    data-subkeg="${item.kode_sub_kegiatan} - ${item.nama_sub_kegiatan}">
                              ${item.kode_sub_kegiatan} - ${item.nama_sub_kegiatan}
                            </option>`;
                });

                options += '</optgroup>';
              });

              $('#select_sub_kegiatan').html(options);
              $('#total_sub_kegiatan').text(response.count);
              $('#sub_kegiatan_container').show();

            } else {
              $('#select_sub_kegiatan').html('<option value="">Tidak ada data</option>');
              $('#total_sub_kegiatan').text('0');

              Swal.fire({
                icon: 'info',
                title: 'Tidak ada data',
                text: 'Tidak ada sub kegiatan untuk SKPD yang dipilih',
                confirmButtonText: 'OK',
                buttonsStyling: false,
                customClass: {
                  confirmButton: "btn btn-primary"
                }
              });
            }
          },
          error: function(xhr, status, error) {
            $('#loading_sub_kegiatan').addClass('d-none');
            $('#select_sub_kegiatan').html('<option value="">Error memuat data</option>');

            Swal.fire({
              icon: 'error',
              title: 'Gagal',
              text: xhr.responseJSON?.message || 'Terjadi kesalahan saat mengambil data sub kegiatan',
              confirmButtonText: 'OK',
              buttonsStyling: false,
              customClass: {
                confirmButton: "btn btn-primary"
              }
            });

            console.error('Error:', xhr.responseJSON || error);
          }
        });
      });

      // === Show detail when sub kegiatan selected ===
      $('#select_sub_kegiatan').on('change', function() {
        const selectedOption = $(this).find('option:selected');

        if (selectedOption.val()) {
          $('#detail_bidang_urusan').text(selectedOption.data('bidang') || '-');
          $('#detail_program').text(selectedOption.data('program') || '-');
          $('#detail_kegiatan').text(selectedOption.data('kegiatan') || '-');
          $('#detail_sub_keg').text(selectedOption.data('subkeg') || '-');
          $('#detail_sub_kegiatan').removeClass('d-none');
        } else {
          $('#detail_sub_kegiatan').addClass('d-none');
        }
      });

      // === Add Sumber Dana ===
      $('#btn_add_sumber_dana').on('click', function() {
        sumberDanaCounter++;
        addSumberDanaForm(sumberDanaCounter);
        updateSumberDanaInfo();
      });

      // === Function: Add Sumber Dana Form ===
      function addSumberDanaForm(id) {
        // Build sumber dana options dari database
        let sumberDanaOptions = '<option value="">Pilih Sumber Dana</option>';
        sumberDanaList.forEach(item => {
          sumberDanaOptions += `<option value="${item.id}">${item.kode_dana} - ${item.nama_dana}</option>`;
        });

        const formHtml = `
          <div class="card card-bordered mb-5 sumber-dana-item" data-id="${id}">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center mb-5">
                <h5 class="card-title m-0">
                  <i class="ki-outline ki-wallet fs-2 text-primary me-2"></i>
                  <span class="sumber-dana-number">Sumber Dana #1</span>
                </h5>
                <button type="button" class="btn btn-icon btn-sm btn-light-danger btn-remove-sumber-dana" data-id="${id}">
                  <i class="ki-outline ki-trash fs-2"></i>
                </button>
              </div>

              <div class="row g-5">
                <!-- Sumber Dana Select -->
                <div class="col-md-6">
                  <label class="required fs-6 fw-semibold mb-2">Pilih Sumber Dana</label>
                  <select class="form-select form-select-solid select-sumber-dana-${id}" 
                          name="sumber_dana[${id}][id_sumber_dana]" 
                          data-control="select2" 
                          data-dropdown-parent="#kt_modal_add_kegiatan"
                          data-placeholder="Pilih Sumber Dana"
                          data-allow-clear="true"
                          required>
                    ${sumberDanaOptions}
                  </select>
                </div>

                <!-- Pagu Input -->
                <div class="col-md-6">
                  <label class="required fs-6 fw-semibold mb-2">Pagu</label>
                  <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="text" class="form-control form-control-solid input-pagu" 
                          name="sumber_dana[${id}][pagu]" 
                          placeholder="0" 
                          required>
                  </div>
                  <div class="form-text">Format: 1.000.000 (otomatis terformat)</div>
                </div>
              </div>

              <!-- Pagu Display -->
              <div class="mt-5 p-4 bg-light-primary rounded">
                <div class="d-flex justify-content-between align-items-center">
                  <span class="fw-bold text-gray-800">
                    Pagu <span class="sumber-dana-number-text">Sumber Dana #1</span>:
                  </span>
                  <span class="fs-4 fw-bold text-primary pagu-display-${id}">Rp 0</span>
                </div>
              </div>
            </div>
          </div>
        `;

        $('#sumber_dana_container').append(formHtml);

        // PENTING: Inisialisasi Select2 untuk elemen yang baru ditambahkan
        $(`.select-sumber-dana-${id}`).select2({
          dropdownParent: $('#kt_modal_add_kegiatan'),
          placeholder: "Pilih Sumber Dana",
          allowClear: true
        });

        // Initialize currency format for new input
        initializeCurrencyFormat();

        // Reorder numbering setelah menambah
        reorderSumberDana();
      }

      // === Remove Sumber Dana ===
      $(document).on('click', '.btn-remove-sumber-dana', function(e) {
        e.preventDefault();

        const button = $(this);
        const id = button.data('id');

        Swal.fire({
          title: 'Hapus Sumber Dana?',
          text: "Data sumber dana ini akan dihapus!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Ya, Hapus!',
          cancelButtonText: 'Batal',
          buttonsStyling: false,
          customClass: {
            confirmButton: "btn btn-danger",
            cancelButton: "btn btn-light"
          }
        }).then((result) => {
          if (result.isConfirmed) {
            // Find the parent card
            const cardElement = button.closest('.sumber-dana-item');

            // Destroy Select2 if exists
            cardElement.find('select[data-control="select2"]').each(function() {
              if ($(this).data('select2')) {
                $(this).select2('destroy');
              }
            });

            // Remove the card with animation
            cardElement.fadeOut(300, function() {
              $(this).remove();

              // Reorder setelah remove
              reorderSumberDana();

              // Update UI
              updateSumberDanaInfo();
              updateTotalPagu();
            });

            Swal.fire({
              icon: 'success',
              title: 'Berhasil!',
              text: 'Sumber dana telah dihapus',
              timer: 1500,
              showConfirmButton: false
            });
          }
        });
      });

      // === Function: Reorder Sumber Dana Numbering ===
      function reorderSumberDana() {
        $('.sumber-dana-item').each(function(index) {
          const newNumber = index + 1;

          // Update title number
          $(this).find('.sumber-dana-number').text(`Sumber Dana #${newNumber}`);

          // Update pagu display text
          $(this).find('.sumber-dana-number-text').text(`Sumber Dana #${newNumber}`);
        });

        // Update info badge (opsional - menampilkan total sumber dana)
        updateSumberDanaCount();
      }

      // === Function: Update Sumber Dana Count ===
      function updateSumberDanaCount() {
        const count = $('.sumber-dana-item').length;

        // Update atau buat badge counter jika belum ada
        let counterBadge = $('#sumber_dana_counter');
        if (counterBadge.length === 0) {
          // Tambahkan badge di header sumber dana
          $('label.fs-6.fw-semibold:contains("Sumber Dana")').html(`
            Sumber Dana 
            <span id="sumber_dana_counter" class="badge badge-light-primary ms-2">${count} Item</span>
          `);
        } else {
          counterBadge.text(`${count} Item`);
        }
      }

      // === Update Sumber Dana Info ===
      function updateSumberDanaInfo() {
        const count = $('.sumber-dana-item').length;
        if (count > 0) {
          $('#no_sumber_dana_info').hide();
          $('#total_pagu_summary').removeClass('d-none');
        } else {
          $('#no_sumber_dana_info').show();
          $('#total_pagu_summary').addClass('d-none');
        }
      }

      // === Initialize Currency Format ===
      function initializeCurrencyFormat() {
        $('.input-pagu').off('input').on('input', function() {
          let value = $(this).val().replace(/[^\d]/g, '');

          if (value) {
            // Format dengan separator ribuan
            const formatted = new Intl.NumberFormat('id-ID').format(value);
            $(this).val(formatted);

            // Update display
            const id = $(this).closest('.sumber-dana-item').data('id');
            $(`.pagu-display-${id}`).text('Rp ' + formatted);
          } else {
            $(this).val('');
            const id = $(this).closest('.sumber-dana-item').data('id');
            $(`.pagu-display-${id}`).text('Rp 0');
          }

          updateTotalPagu();
        });

        // Format saat blur untuk memastikan format tetap bagus
        $('.input-pagu').off('blur').on('blur', function() {
          let value = $(this).val().replace(/[^\d]/g, '');
          if (value) {
            const formatted = new Intl.NumberFormat('id-ID').format(value);
            $(this).val(formatted);
          }
        });
      }

      // === Update Total Pagu ===
      function updateTotalPagu() {
        let total = 0;
        $('.input-pagu').each(function() {
          const value = $(this).val().replace(/[^\d]/g, '');
          total += parseInt(value) || 0;
        });

        // Display grand total
        const formattedTotal = new Intl.NumberFormat('id-ID').format(total);
        $('#grand_total_pagu').text('Rp ' + formattedTotal);
      }

      // === Initialize DataTable ===
      var table = $('#kt_datatable_column_rendering').DataTable({
        responsive: true,
        searchDelay: 500,
        processing: true,
        serverSide: false,
        order: [
          [1, 'asc']
        ],
        columnDefs: [{
            targets: [0],
            orderable: false,
            className: 'text-center'
          },
          {
            targets: [9],
            orderable: false,
            className: 'text-end'
          }
        ],
        dom: "<'row'<'col-sm-12'tr>>" +
          "<'row mt-4'" +
          "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-start'li>" +
          "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-end'p>" +
          ">",
        language: {
          paginate: {
            previous: '<i class="ki-outline ki-arrow-left fs-4"></i>',
            next: '<i class="ki-outline ki-arrow-right fs-4"></i>'
          }
        }
      });

      // Search functionality
      $('#kt_datatable_search_input').keyup(function() {
        table.search(this.value).draw();
      });

      // === Session Messages ===
      const sessionMessages = document.querySelectorAll('#session-messages div');
      sessionMessages.forEach(msg => {
        const type = msg.dataset.type;
        const message = msg.dataset.message;
        toastr.options = {
          "closeButton": true,
          "debug": false,
          "newestOnTop": false,
          "progressBar": true,
          "positionClass": "toastr-top-right",
          "preventDuplicates": false,
          "onclick": null,
          "showDuration": "300",
          "hideDuration": "1000",
          "timeOut": "5000",
          "extendedTimeOut": "1000",
          "showEasing": "swing",
          "hideEasing": "linear",
          "showMethod": "fadeIn",
          "hideMethod": "fadeOut"
        };
        if (type === 'error') toastr.error(message, "GAGAL");
        else if (type === 'success') toastr.success(message, "BERHASIL");
        else toastr.info(message);
      });

      // === Form validation ===
      const form = document.getElementById('kt_modal_add_kegiatan_form');
      const submitButton = document.getElementById('kt_modal_add_kegiatan_submit');

      if (form && submitButton) {
        form.addEventListener('submit', function(e) {
          const idSkpd = form.querySelector('select[name="id_skpd"]').value;
          const idSubKegiatan = form.querySelector('select[name="id_sub_kegiatan"]').value;
          const sumberDanaCount = $('.sumber-dana-item').length;

          if (!idSkpd || !idSubKegiatan) {
            e.preventDefault();
            Swal.fire({
              icon: 'error',
              title: 'Validasi gagal',
              text: 'Pilih SKPD dan Sub Kegiatan terlebih dahulu!',
              confirmButtonText: 'OK',
              buttonsStyling: false,
              customClass: {
                confirmButton: "btn btn-primary"
              }
            });
            return;
          }

          if (sumberDanaCount === 0) {
            e.preventDefault();
            Swal.fire({
              icon: 'error',
              title: 'Validasi gagal',
              text: 'Tambahkan minimal 1 sumber dana!',
              confirmButtonText: 'OK',
              buttonsStyling: false,
              customClass: {
                confirmButton: "btn btn-primary"
              }
            });
            return;
          }

          // Sebelum submit, convert formatted numbers back to plain numbers
          $('.input-pagu').each(function() {
            const plainValue = $(this).val().replace(/[^\d]/g, '');
            $(this).val(plainValue);
          });

          submitButton.setAttribute('data-kt-indicator', 'on');
          submitButton.disabled = true;
        });
      }

      // === Reset modal when closed ===
      $('#kt_modal_add_kegiatan').on('hidden.bs.modal', function() {
        // Destroy all Select2 instances in sumber dana container
        $('#sumber_dana_container select[data-control="select2"]').each(function() {
          if ($(this).hasClass("select2-hidden-accessible")) {
            $(this).select2('destroy');
          }
        });

        form.reset();
        $('#sub_kegiatan_container').hide();
        $('#detail_sub_kegiatan').addClass('d-none');
        $('#select_sub_kegiatan').html('<option value="">Pilih Sub Kegiatan</option>');
        $('#total_sub_kegiatan').text('0');
        $('#sumber_dana_container').html('');
        $('#no_sumber_dana_info').show();
        $('#total_pagu_summary').addClass('d-none');
        $('#grand_total_pagu').text('Rp 0');
        sumberDanaCounter = 0;
        submitButton.removeAttribute('data-kt-indicator');
        submitButton.disabled = false;
      });

      // === Auto show modal if validation errors exist ===
      @if ($errors->any() && old('_token'))
        $('#kt_modal_add_kegiatan').modal('show');
      @endif
    });
  </script>
@endsection
