@extends('layouts.master')

@section('content')
  <x-toolbar title="Pembiayaan - Pengeluaran" :breadcrumbs="[['label' => 'Home', 'url' => url('/')], ['label' => 'Pebiayaan'], ['label' => 'Pengeluaran']]" />

  <div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-fluid">

      {{-- Session Messages --}}
      <div id="session-messages" style="display:none;">
        @if (session('success'))
          <div data-type="success" data-message="{{ session('success') }}"></div>
        @endif
        @if (session('error'))
          <div data-type="error" data-message="{{ session('error') }}"></div>
        @endif
      </div>

      {{-- Grand Total Cards --}}
      <div class="row g-5 g-xl-8 mb-5">
        <div class="col-xl-4">
          <div class="card card-flush h-100">
            <div class="card-body d-flex align-items-center py-5">
              <div class="symbol symbol-50px me-5">
                <span class="symbol-label bg-light-danger">
                  <i class="ki-duotone ki-abstract-26 fs-2x text-danger">
                    <span class="path1"></span><span class="path2"></span>
                  </i>
                </span>
              </div>
              <div class="d-flex flex-column">
                <span class="text-muted fw-semibold fs-7 mb-1">Jumlah SKPD</span>
                <span class="text-gray-800 fw-bold fs-4" id="card-jumlah-skpd">—</span>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-4">
          <div class="card card-flush h-100">
            <div class="card-body d-flex align-items-center py-5">
              <div class="symbol symbol-50px me-5">
                <span class="symbol-label bg-light-warning">
                  <i class="ki-duotone ki-dollar fs-2x text-warning">
                    <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                  </i>
                </span>
              </div>
              <div class="d-flex flex-column">
                <span class="text-muted fw-semibold fs-7 mb-1">Total Sebelum Perubahan</span>
                <span class="text-gray-800 fw-bold fs-5" id="card-grand-sebelum">—</span>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-4">
          <div class="card card-flush h-100">
            <div class="card-body d-flex align-items-center py-5">
              <div class="symbol symbol-50px me-5">
                <span class="symbol-label bg-light-success">
                  <i class="ki-duotone ki-dollar fs-2x text-success">
                    <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                  </i>
                </span>
              </div>
              <div class="d-flex flex-column">
                <span class="text-muted fw-semibold fs-7 mb-1">Total Setelah Perubahan</span>
                <span class="text-gray-800 fw-bold fs-5" id="card-grand-setelah">—</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Table Card --}}
      <div class="card">
        <div class="card-header border-0 pt-6">
          <div class="card-title">
            <div class="d-flex align-items-center position-relative my-1">
              <i class="ki-outline ki-magnifier fs-3 position-absolute ms-4"></i>
              <input type="text" data-kt-pengeluaran-filter="search" class="form-control form-control-solid w-300px ps-12"
                placeholder="Cari SKPD..." />
            </div>
          </div>
        </div>

        <div class="card-body pt-0">
          <table id="kt_pengeluaran_index_table" class="table align-middle table-row-dashed fs-6 gy-5">
            <thead>
              <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                <th class="min-w-40px">No</th>
                <th class="min-w-120px">Kode SKPD</th>
                <th class="min-w-300px">Nama SKPD</th>
                <th class="min-w-160px text-end">Sebelum Perubahan</th>
                <th class="min-w-160px text-end">Setelah Perubahan</th>
                <th class="min-w-100px text-end">Aksi</th>
              </tr>
            </thead>
            <tbody class="fw-semibold text-gray-600"></tbody>
          </table>
        </div>
      </div>

    </div>
  </div>

  <script>
    "use strict";

    var KTPengeluaranIndex = function() {
      var dt;

      var formatRupiah = function(value) {
        if (!value && value !== 0) return '<span class="text-muted fs-7">—</span>';
        return 'Rp ' + parseFloat(value).toLocaleString('id-ID', {
          minimumFractionDigits: 0
        });
      };

      var initDatatable = function() {
        dt = $('#kt_pengeluaran_index_table').DataTable({
          searchDelay: 500,
          processing: true,
          serverSide: true,
          order: [
            [1, 'asc']
          ],
          stateSave: false,
          language: {
            processing: '<span class="spinner-border spinner-border-sm align-middle me-2"></span> Memuat data...',
            emptyTable: 'Tidak ada SKPD dengan pembiayaan pengeluaran aktif',
            zeroRecords: 'SKPD tidak ditemukan',
            lengthMenu: '_MENU_',
            info: 'Menampilkan _START_–_END_ dari _TOTAL_ SKPD',
            infoEmpty: 'Tidak ada data',
            search: '',
            paginate: {
              first: '«',
              last: '»',
              next: '›',
              previous: '‹'
            }
          },
          ajax: {
            url: "{{ route('pembiayaan.pengeluaran.getData') }}",
            type: 'GET',
            dataSrc: function(json) {
              if (json.grandTotal) {
                $('#card-jumlah-skpd').text(json.recordsFiltered);
                $('#card-grand-sebelum').text(formatRupiah(json.grandTotal.sebelum));
                $('#card-grand-setelah').text(formatRupiah(json.grandTotal.setelah));
              }
              return json.data;
            }
          },
          columns: [{
              data: 'id',
              orderable: false
            }, // 0 – no urut
            {
              data: 'kode_skpd'
            }, // 1
            {
              data: 'nama_skpd'
            }, // 2
            {
              data: 'total_sebelum'
            }, // 3
            {
              data: 'total_setelah'
            }, // 4
            {
              data: null,
              orderable: false
            }, // 5 – aksi
          ],
          columnDefs: [
            // No urut
            {
              targets: 0,
              orderable: false,
              className: 'text-center text-muted fs-7',
              render: function(data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
              }
            },
            // Kode SKPD
            {
              targets: 1,
              render: function(data) {
                return `<span class="badge badge-light-primary fs-7 fw-bold">${data ?? '—'}</span>`;
              }
            },
            // Nama SKPD
            {
              targets: 2,
              render: function(data, type, row) {
                var kepala = row.namakepala ?
                  `<span class="text-muted fw-semibold fs-7 mt-1 d-block">
                       <i class="ki-outline ki-profile-circle fs-7 me-1"></i>${row.namakepala}
                     </span>` :
                  '';
                return `<div class="d-flex flex-column">
                          <span class="text-gray-800 fw-bold fs-6">${data ?? '—'}</span>
                          ${kepala}
                        </div>`;
              }
            },
            // Sebelum perubahan
            {
              targets: 3,
              className: 'text-end',
              render: function(data) {
                return data > 0 ?
                  `<span class="text-gray-800 fw-bold fs-6">${formatRupiah(data)}</span>` :
                  '<span class="text-muted fs-7">Belum ada data</span>';
              }
            },
            // Setelah perubahan + selisih
            {
              targets: 4,
              className: 'text-end',
              render: function(data, type, row) {
                if (!data || data <= 0) {
                  return '<span class="text-muted fs-7">Belum ada data</span>';
                }
                var selisih = parseFloat(data) - parseFloat(row.total_sebelum || 0);
                var color = selisih >= 0 ? 'danger' : 'success'; // pengeluaran: naik = bahaya
                var icon = selisih >= 0 ? 'ki-arrow-up' : 'ki-arrow-down';
                var selisihEl = row.total_sebelum > 0 ?
                  `<span class="text-${color} fs-8 fw-semibold">
                       <i class="ki-outline ${icon} fs-9 text-${color}"></i>
                       Rp ${Math.abs(selisih).toLocaleString('id-ID')}
                     </span>` :
                  '';
                return `<div class="d-flex flex-column align-items-end">
                          <span class="text-gray-800 fw-bold fs-6">${formatRupiah(data)}</span>
                          ${selisihEl}
                        </div>`;
              }
            },
            // Aksi
            {
              targets: 5,
              className: 'text-end',
              render: function(data, type, row) {
                return `<a href="{{ url('pembiayaan/pengeluaran') }}/${row.id_skpd}/rincian"
                           class="btn btn-sm btn-primary fw-semibold">
                          <i class="ki-outline ki-eye fs-5 me-1"></i>Lihat Rincian
                        </a>`;
              }
            },
          ],
        });
      };

      var handleSearch = function() {
        const el = document.querySelector('[data-kt-pengeluaran-filter="search"]');
        el.addEventListener('keyup', function(e) {
          dt.search(e.target.value).draw();
        });
      };

      return {
        init: function() {
          initDatatable();
          handleSearch();
        }
      };
    }();

    document.addEventListener("DOMContentLoaded", function() {
      KTPengeluaranIndex.init();

      document.querySelectorAll('#session-messages div').forEach(function(msg) {
        toastr.options = {
          closeButton: true,
          progressBar: true,
          positionClass: 'toastr-top-right',
          timeOut: '5000'
        };
        if (msg.dataset.type === 'error') toastr.error(msg.dataset.message, 'GAGAL');
        if (msg.dataset.type === 'success') toastr.success(msg.dataset.message, 'BERHASIL');
      });
    });
  </script>
@endsection
