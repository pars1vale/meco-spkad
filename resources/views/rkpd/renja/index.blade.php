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
        <div class="col-md-6 col-lg-4 col-xl-4 mb-10">
          <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-center border-0 mb-5 mb-xl-10"
            style="background-color: #080655; min-height: 200px;">
            <div class="card-header pt-5">
              <div class="card-title d-flex flex-column">
                <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">Batasan Pagu</span>
                <span class="text-white opacity-50 pt-1 fw-semibold fs-6">Active Projects</span>
              </div>
            </div>
            <div class="card-body d-flex align-items-end pt-0 pb-5">
              <div class="d-flex align-items-center flex-column mt-3 w-100">
                <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-50 w-100 mt-auto mb-2">
                  <span>43 Pending</span>
                  <span>72%</span>
                </div>
                <div class="h-8px w-100 bg-light-danger rounded">
                  <div class="bg-danger rounded h-8px" role="progressbar" style="width: 72%;" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-4 col-xl-4 mb-10">
          <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-center border-0 mb-5 mb-xl-10"
            style="background-color: #1C325E; min-height: 200px;">
            <div class="card-header pt-5">
              <div class="card-title d-flex flex-column">
                <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">Inputan Pagu</span>
                <span class="text-white opacity-50 pt-1 fw-semibold fs-6">Completed Tasks</span>
              </div>
            </div>
            <div class="card-body d-flex align-items-end pt-0 pb-5">
              <div class="d-flex align-items-center flex-column mt-3 w-100">
                <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-50 w-100 mt-auto mb-2">
                  <span>38 Done</span>
                  <span>85%</span>
                </div>
                <div class="h-8px w-100 bg-light-success rounded">
                  <div class="bg-success rounded h-8px" role="progressbar" style="width: 85%;" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-4 col-xl-4 mb-10">
          <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-center border-0 mb-5 mb-xl-10"
            style="background-color: #7239EA; min-height: 200px;">
            <div class="card-header pt-5">
              <div class="card-title d-flex flex-column">
                <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">Pagu Validasi</span>
                <span class="text-white opacity-50 pt-1 fw-semibold fs-6">Total Clients</span>
              </div>
            </div>
            <div class="card-body d-flex align-items-end pt-0 pb-5">
              <div class="d-flex align-items-center flex-column mt-3 w-100">
                <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-50 w-100 mt-auto mb-2">
                  <span>89 Active</span>
                  <span>63%</span>
                </div>
                <div class="h-8px w-100 bg-light-primary rounded">
                  <div class="bg-primary rounded h-8px" role="progressbar" style="width: 63%;" aria-valuenow="63" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-4 col-xl-4 mb-10">
          <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-center border-0 mb-5 mb-xl-10"
            style="background-color: #F1416C; min-height: 200px;">
            <div class="card-header pt-5">
              <div class="card-title d-flex flex-column">
                <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">Rincian</span>
                <span class="text-white opacity-50 pt-1 fw-semibold fs-6">Overdue Items</span>
              </div>
            </div>
            <div class="card-body d-flex align-items-end pt-0 pb-5">
              <div class="d-flex align-items-center flex-column mt-3 w-100">
                <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-50 w-100 mt-auto mb-2">
                  <span>15 Critical</span>
                  <span>54%</span>
                </div>
                <div class="h-8px w-100 bg-light-warning rounded">
                  <div class="bg-warning rounded h-8px" role="progressbar" style="width: 54%;" aria-valuenow="54" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-4 col-xl-4 mb-10">
          <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-center border-0 mb-5 mb-xl-10"
            style="background-color: #00A3FF; min-height: 200px;">
            <div class="card-header pt-5">
              <div class="card-title d-flex flex-column">
                <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">Realisasi</span>
                <span class="text-white opacity-50 pt-1 fw-semibold fs-6">New Messages</span>
              </div>
            </div>
            <div class="card-body d-flex align-items-end pt-0 pb-5">
              <div class="d-flex align-items-center flex-column mt-3 w-100">
                <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-50 w-100 mt-auto mb-2">
                  <span>198 Unread</span>
                  <span>61%</span>
                </div>
                <div class="h-8px w-100 bg-light-info rounded">
                  <div class="bg-info rounded h-8px" role="progressbar" style="width: 61%;" aria-valuenow="61" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-4 col-xl-4 mb-10">
          <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-center border-0 mb-5 mb-xl-10"
            style="background-color: #50CD89; min-height: 200px;">
            <div class="card-header pt-5">
              <div class="card-title d-flex flex-column">
                <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">Persentase</span>
                <span class="text-white opacity-50 pt-1 fw-semibold fs-6">Team Members</span>
              </div>
            </div>
            <div class="card-body d-flex align-items-end pt-0 pb-5">
              <div class="d-flex align-items-center flex-column mt-3 w-100">
                <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-50 w-100 mt-auto mb-2">
                  <span>76 Online</span>
                  <span>85%</span>
                </div>
                <div class="h-8px w-100 bg-white bg-opacity-25 rounded">
                  <div class="bg-white rounded h-8px" role="progressbar" style="width: 85%;" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
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
          @endif
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Tambah kegiatan -->
  <div class="modal fade" id="kt_modal_add_kegiatan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-900px">
      <div class="modal-content">
        <form class="form" action="{{ route('renja.store') }}" method="POST" id="kt_modal_add_kegiatan_form">
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
                <select class="form-select form-select-solid @error('id_skpd') is-invalid @enderror" name="id_skpd"
                  id="select_skpd" data-control="select2" data-dropdown-parent="#kt_modal_add_kegiatan" required>
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
                <select class="form-select form-select-solid @error('id_sub_kegiatan') is-invalid @enderror"
                  name="id_sub_kegiatan" id="select_sub_kegiatan" data-control="select2"
                  data-dropdown-parent="#kt_modal_add_kegiatan" required>
                  <option value="">Pilih Sub Kegiatan</option>
                </select>
                @error('id_sub_kegiatan')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror>
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

                <!-- Indikator Section -->
                <div id="indikator_section" class="mt-5 d-none">
                  <div class="separator separator-dashed my-4"></div>
                  <div id="indikator_list">
                    <!-- Indikator akan dimuat di sini -->
                  </div>
                </div>
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

              <div class="fv-row mb-7">
                <label class="fs-6 fw-semibold mb-2">Rincian Lokasi</label>
                <div class="d-flex gap-3">
                  {{-- Kabupaten --}}
                  <select id="id_lokasi" class="form-select form-select-solid" name="id_lokasi" required>
                    <option value="">Pilih Daerah</option>
                    @foreach ($daerah as $kab)
                    <option value="{{ $kab->id_daerah }}">{{ $kab->nama_daerah }}</option>
                    @endforeach
                  </select>

                  {{-- Kecamatan --}}
                  <select id="kecamatan" class="form-select form-select-solid" name="id_camat">
                    <option value="">Pilih Kecamatan</option>
                    @foreach ($kec as $kc)
                    <option value="{{ $kc->id_camat }}">{{ $kc->camat_teks }}</option>
                    @endforeach
                  </select>

                  {{-- Kelurahan --}}
                  <select id="kelurahan" class="form-select form-select-solid" name="id_lurah">
                    <option value="">Pilih Kelurahan</option>
                    @foreach ($kel as $kl)
                    <option value="{{ $kl->id_lurah }}">{{ $kl->lurah_teks }}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="fv-row mb-7">
                <label class="fs-6 fw-semibold mb-2">Waktu Pelaksana</label>
                <div class="d-flex gap-3">
                  {{-- Waktu Awal --}}
                  <select id="waktu_awal" class="form-select form-select-solid" name="waktu_awal" required>
                    <option value="">Pilih Waktu Awal</option>
                    @foreach ($bln as $bl)
                    <option value="{{ $bl->id }}">{{ $bl->nama }}</option>
                    @endforeach
                  </select>
                  <span>S/D</span>
                  {{-- Waktu Akhir --}}
                  <select id="waktu_akhir" class="form-select form-select-solid" name="waktu_akhir">
                    <option value="">Pilih Waktu Akhir</option>
                     @foreach ($bln as $bl)
                    <option value="{{ $bl->id }}">{{ $bl->nama }}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="fv-row mb-7">
                <label class=" fs-6 fw-semibold mb-2">Anggaran N+1 Sub Kegiatan</label>
                <div class="input-group">
                  <span class="input-group-text">Rp</span>
                  <input type="text" class="form-control form-control-solid input-pagu" name="pagu_n_depan"
                    placeholder="0" >
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

  <style>
/* Custom styling untuk grouping hierarki */
.dtrg-group {
    background-color: #f5f8fa !important;
    font-weight: 600;
    font-size: 14px;
    padding: 12px 15px !important;
    border-left: 4px solid #3699FF;
}

.dtrg-level-0 {
    background-color: #E8F5E9 !important;
    border-left-color: #4CAF50 !important;
    font-size: 15px;
}

.dtrg-level-1 {
    background-color: #E3F2FD !important;
    border-left-color: #2196F3 !important;
    padding-left: 30px !important;
}

.dtrg-level-2 {
    background-color: #FFF3E0 !important;
    border-left-color: #FF9800 !important;
    padding-left: 50px !important;
}

.dtrg-level-3 {
    background-color: #F3E5F5 !important;
    border-left-color: #9C27B0 !important;
    padding-left: 70px !important;
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
          $('#indikator_section').addClass('d-none');
          $('#total_sub_kegiatan').text('0');
          return;
        }

        $('#loading_sub_kegiatan').removeClass('d-none');
        $('#sub_kegiatan_container').hide();
        $('#detail_sub_kegiatan').addClass('d-none');
        $('#indikator_section').addClass('d-none');
        $('#select_sub_kegiatan').html('<option value="">Memuat...</option>');

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

  $('#select_sub_kegiatan').on('change', function() {
    const selectedOption = $(this).find('option:selected');

    if (selectedOption.val()) {
      $('#detail_bidang_urusan').text(selectedOption.data('bidang') || '-');
      $('#detail_program').text(selectedOption.data('program') || '-');
      $('#detail_kegiatan').text(selectedOption.data('kegiatan') || '-');
      $('#detail_sub_keg').text(selectedOption.data('subkeg') || '-');
      
      const idSubKegiatan = selectedOption.val();
      displayIndikator(idSubKegiatan);
      
      $('#detail_sub_kegiatan').removeClass('d-none');
    } else {
      $('#detail_sub_kegiatan').addClass('d-none');
      $('#indikator_section').addClass('d-none');
    }
  });

  function displayIndikator(idSubKegiatan) {
    const indikatorData = subKegiatanData.filter(item =>
      item.id_sub_kegiatan == idSubKegiatan && item.indikator
    );

    if (indikatorData.length > 0) {
      let indikatorHtml = '<h6 class="mb-3 fw-bold">Indikator Kinerja</h6>';

      indikatorData.forEach((item, index) => {
        indikatorHtml += `
          <div class="row align-items-center mb-3">
            <div class="col-md-5">
              <div class="fw-semibold text-gray-800">${item.indikator}</div>
              <input type="hidden" name="indikator[${index}][id_indikator]" value="${item.id_indikator || ''}">
              <input type="hidden" name="indikator[${index}][indikator_text]" value="${item.indikator}">
              <input type="hidden" name="indikator[${index}][satuan]" value="${item.satuan}">
            </div>
            <div class="col-md-5">
              <input type="text" 
                    class="form-control form-control-solid input-target" 
                    name="indikator[${index}][target]" 
                    placeholder="0" 
                    required />
            </div>
            <div class="col-md-2">
              <div class="text-gray-600">${item.satuan}</div>
            </div>
          </div>
        `;
      });

      $('#indikator_list').html(indikatorHtml);
      $('#indikator_section').removeClass('d-none');
      initializeTargetFormat();
    } else {
      $('#indikator_section').addClass('d-none');
    }
  }

  function initializeTargetFormat() {
    $('.input-target').off('input').on('input', function() {
      let value = $(this).val().replace(/[^\d]/g, '');
      if (value) {
        const formatted = new Intl.NumberFormat('id-ID').format(value);
        $(this).val(formatted);
      }
    });
    
    $('.input-target').off('blur').on('blur', function() {
      let value = $(this).val().replace(/[^\d]/g, '');
      if (value) {
        const formatted = new Intl.NumberFormat('id-ID').format(value);
        $(this).val(formatted);
      }
    });
  }

  $('#btn_add_sumber_dana').on('click', function() {
    sumberDanaCounter++;
    addSumberDanaForm(sumberDanaCounter);
    updateSumberDanaInfo();
  });

  function addSumberDanaForm(id) {
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

    $(`.select-sumber-dana-${id}`).select2({
      dropdownParent: $('#kt_modal_add_kegiatan'),
      placeholder: "Pilih Sumber Dana",
      allowClear: true
    });

    initializeCurrencyFormat();
    reorderSumberDana();
  }

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
        const cardElement = button.closest('.sumber-dana-item');

        cardElement.find('select[data-control="select2"]').each(function() {
          if ($(this).data('select2')) {
            $(this).select2('destroy');
          }
        });

        cardElement.fadeOut(300, function() {
          $(this).remove();
          reorderSumberDana();
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

  function reorderSumberDana() {
    $('.sumber-dana-item').each(function(index) {
      const newNumber = index + 1;
      $(this).find('.sumber-dana-number').text(`Sumber Dana #${newNumber}`);
      $(this).find('.sumber-dana-number-text').text(`Sumber Dana #${newNumber}`);
    });
    updateSumberDanaCount();
  }

  function updateSumberDanaCount() {
    const count = $('.sumber-dana-item').length;
    let counterBadge = $('#sumber_dana_counter');
    if (counterBadge.length === 0) {
      $('label.fs-6.fw-semibold:contains("Sumber Dana")').html(`
        Sumber Dana 
        <span id="sumber_dana_counter" class="badge badge-light-primary ms-2">${count} Item</span>
      `);
    } else {
      counterBadge.text(`${count} Item`);
    }
  }

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

  function initializeCurrencyFormat() {
    $('.input-pagu').off('input').on('input', function() {
      let value = $(this).val().replace(/[^\d]/g, '');

      if (value) {
        const formatted = new Intl.NumberFormat('id-ID').format(value);
        $(this).val(formatted);
        const id = $(this).closest('.sumber-dana-item').data('id');
        $(`.pagu-display-${id}`).text('Rp ' + formatted);
      } else {
        $(this).val('');
        const id = $(this).closest('.sumber-dana-item').data('id');
        $(`.pagu-display-${id}`).text('Rp 0');
      }

      updateTotalPagu();
    });

    $('.input-pagu').off('blur').on('blur', function() {
      let value = $(this).val().replace(/[^\d]/g, '');
      if (value) {
        const formatted = new Intl.NumberFormat('id-ID').format(value);
        $(this).val(formatted);
      }
    });
  }

  function updateTotalPagu() {
    let total = 0;
    $('.input-pagu').each(function() {
      const value = $(this).val().replace(/[^\d]/g, '');
      total += parseInt(value) || 0;
    });

    const formattedTotal = new Intl.NumberFormat('id-ID').format(total);
    $('#grand_total_pagu').text('Rp ' + formattedTotal);
  }

  // === Initialize DataTable ===
  var table = $('#kt_datatable_column_rendering').DataTable({
    responsive: true,
    searchDelay: 500,
    processing: true,
    serverSide: true,
    ajax: {
      url: '{{ route('renja.data') }}',
      type: 'GET',
      error: function(xhr, error, code) {
        console.error('DataTable Error:', error);
        Swal.fire({
          icon: 'error',
          title: 'Gagal Memuat Data',
          text: 'Terjadi kesalahan saat mengambil data',
          confirmButtonText: 'OK',
          buttonsStyling: false,
          customClass: {
            confirmButton: "btn btn-primary"
          }
        });
      }
    },
    columns: [
      { data: 'checkbox', orderable: false, searchable: false, visible: false },
      { data: 'group_skpd', visible: false },
      { data: 'group_urusan', visible: false },
      { data: 'group_program', visible: false },
      { data: 'group_kegiatan', visible: false },
      { data: 'sub_kegiatan' },
      { data: 'status_sub_kegiatan' },
      { data: 'status_rincian' },
      { data: 'sebelum_perubahan', className: 'text-end' },
      { data: 'pagu_validasi', className: 'text-end' },
      { data: 'total_rincian', className: 'text-end' },
      { data: 'total_realisasi', className: 'text-end' },
      { data: 'persentase', className: 'text-end' },
      { data: 'aksi', orderable: false, searchable: false }
    ],
    order: [[1, 'asc']],
    rowGroup: {
      dataSrc: ['group_skpd', 'group_urusan', 'group_program', 'group_kegiatan'],
      startRender: function(rows, group, level) {
        return $('<tr class="dtrg-group dtrg-level-' + level + '"/>')
          .append('<td colspan="14">' + group + '</td>');
      }
    },
    dom: "<'row'<'col-sm-12'tr>>" +
      "<'row mt-4'" +
      "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-start'li>" +
      "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-end'p>" +
      ">",
    language: {
      paginate: {
        previous: '<i class="ki-outline ki-arrow-left fs-4"></i>',
        next: '<i class="ki-outline ki-arrow-right fs-4"></i>'
      },
      processing: '<div class="d-flex justify-content-center"><div class="spinner-border text-primary" role="status"></div></div>',
      emptyTable: 'Tidak ada data yang tersedia',
      info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
      infoEmpty: 'Menampilkan 0 sampai 0 dari 0 data',
      infoFiltered: '(disaring dari _MAX_ total data)',
      lengthMenu: 'Tampilkan _MENU_ data',
      zeroRecords: 'Tidak ada data yang cocok'
    }
  });

  // Search functionality
  $('#kt_datatable_search_input').keyup(function() {
    table.search(this.value).draw();
  });

  // Collapse/Expand button functionality
  $(document).on('click', '.btn-collapse', function(e) {
    e.preventDefault();
    $(this).toggleClass('collapsed');
  });

  // ========== EVENT HANDLER MENU AKSI ==========
  // 1. Lihat Sub Kegiatan
  $(document).on('click', '.btn-lihat-sub-kegiatan', function(e) {
    e.preventDefault();
    const id = $(this).data('id');
    
    Swal.fire({
      title: 'Lihat Sub Kegiatan',
      html: `
        <div class="text-start">
          <p>Menampilkan detail lengkap sub kegiatan dengan ID: <strong>${id}</strong></p>
          <ul class="list-unstyled mt-3">
            <li><i class="ki-outline ki-check-circle text-success"></i> Informasi sub kegiatan</li>
            <li><i class="ki-outline ki-check-circle text-success"></i> Daftar indikator dan target</li>
            <li><i class="ki-outline ki-check-circle text-success"></i> Sumber dana</li>
            <li><i class="ki-outline ki-check-circle text-success"></i> Lokasi dan waktu pelaksanaan</li>
          </ul>
        </div>
      `,
      icon: 'info',
      confirmButtonText: 'Tutup',
      buttonsStyling: false,
      customClass: {
        confirmButton: "btn btn-primary"
      }
    });
  });

  // 2. Lihat Rincian Belanja
 
$(document).on('click', '.btn-lihat-rincian', function(e) {
  e.preventDefault();
  const id = $(this).data('id');
  
  // Redirect ke halaman rincian belanja
  window.location.href = `/rkpd/renja/${id}/rincian`;
});

  // 3. RKA Paket / Kelompok
  $(document).on('click', '.btn-rka-paket', function(e) {
    e.preventDefault();
    const id = $(this).data('id');
    
    Swal.fire({
      title: 'RKA Paket / Kelompok',
      html: `
        <div class="text-start">
          <p>Mengelola paket/kelompok belanja untuk ID: <strong>${id}</strong></p>
          <ul class="list-unstyled mt-3">
            <li><i class="ki-outline ki-check-circle text-success"></i> Pengelompokan rincian belanja</li>
            <li><i class="ki-outline ki-check-circle text-success"></i> Manajemen paket pekerjaan</li>
            <li><i class="ki-outline ki-check-circle text-success"></i> Alokasi anggaran per paket</li>
          </ul>
        </div>
      `,
      icon: 'info',
      confirmButtonText: 'Tutup',
      buttonsStyling: false,
      customClass: {
        confirmButton: "btn btn-success"
      }
    });
  });

  // 4. RKA Rincian Belanja
  $(document).on('click', '.btn-rka-rincian', function(e) {
    e.preventDefault();
    const id = $(this).data('id');
    
    Swal.fire({
      title: 'RKA Rincian Belanja',
      html: `
        <div class="text-start">
          <p>Input dan edit RKA rincian belanja untuk ID: <strong>${id}</strong></p>
          <ul class="list-unstyled mt-3">
            <li><i class="ki-outline ki-check-circle text-warning"></i> Input detail belanja</li>
            <li><i class="ki-outline ki-check-circle text-warning"></i> Kode rekening dan uraian</li>
            <li><i class="ki-outline ki-check-circle text-warning"></i> Volume, satuan, dan tarif</li>
            <li><i class="ki-outline ki-check-circle text-warning"></i> Perhitungan total anggaran</li>
          </ul>
        </div>
      `,
      icon: 'info',
      confirmButtonText: 'Tutup',
      buttonsStyling: false,
      customClass: {
        confirmButton: "btn btn-warning"
      }
    });
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

      $('.input-pagu').each(function() {
        const plainValue = $(this).val().replace(/[^\d]/g, '');
        $(this).val(plainValue);
      });

      $('.input-target').each(function () {
        const plainValue = $(this).val().replace(/[^\d]/g, '');
        $(this).val(plainValue);
      });

      submitButton.setAttribute('data-kt-indicator', 'on');
      submitButton.disabled = true;
    });
  }

  // === Reset modal when closed ===
  $('#kt_modal_add_kegiatan').on('hidden.bs.modal', function() {
    $('#sumber_dana_container select[data-control="select2"]').each(function() {
      if ($(this).hasClass("select2-hidden-accessible")) {
        $(this).select2('destroy');
      }
    });

    form.reset();
    $('#sub_kegiatan_container').hide();
    $('#detail_sub_kegiatan').addClass('d-none');
    $('#indikator_section').addClass('d-none');
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