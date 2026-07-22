@extends('layouts.master')

@section('content')
  {{-- Toolbar --}}
  <div id="kt_ap_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
      <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
          <h1 class="page-heading text-dark fw-bold fs-3 m-0">Rincian Pembiayaan penerimaan</h1>
          <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
            <li class="breadcrumb-item text-muted">
              <a href="{{ url('/home') }}" class="text-muted text-hover-primary">Home</a>
            </li>
            <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
            <li class="breadcrumb-item text-muted">Pembiayaan</li>
            <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
            <li class="breadcrumb-item text-muted">
              <a href="{{ route('pembiayaan.penerimaan.index') }}" class="text-muted text-hover-primary">Penerimaan</a>
            </li>
            <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
            <li class="breadcrumb-item text-muted">Rincian</li>
          </ul>
        </div>
        <div class="d-flex align-items-center gap-2 gap-lg-3">
          <span class="badge badge-light-primary fs-7 fw-bold px-4 py-2">
            <i class="ki-outline ki-calendar fs-6 me-1"></i>
            Tahun Anggaran: {{ $tahunAnggaran }}
          </span>
        </div>
      </div>
    </div>
  </div>

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

      {{-- Info Cards --}}
      <div class="row g-5 g-xl-8 mb-5">
        {{-- Nama SKPD --}}
        <div class="col-xl-4">
          <div class="card card-flush h-100">
            <div class="card-body d-flex align-items-center py-5">
              <div class="symbol symbol-50px me-5">
                <span class="symbol-label bg-light-danger">
                  <i class="ki-duotone ki-office-bag fs-2x text-danger">
                    <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span>
                  </i>
                </span>
              </div>
              <div class="d-flex flex-column">
                <span class="text-muted fw-semibold fs-7 mb-1">SKPD</span>
                <span class="text-gray-800 fw-bold fs-5">{{ $skpd->nama_skpd ?? ($skpd->namaunit ?? '—') }}</span>
                @if (!empty($skpd->kode_skpd) || !empty($skpd->kodeunit))
                  <span class="badge badge-light-danger fs-8 mt-1 w-fit-content">
                    {{ $skpd->kode_skpd ?? $skpd->kodeunit }}
                  </span>
                @endif
              </div>
            </div>
          </div>
        </div>
        {{-- Jumlah Rekening --}}
        <div class="col-xl-2">
          <div class="card card-flush h-100">
            <div class="card-body d-flex align-items-center py-5">
              <div class="symbol symbol-50px me-5">
                <span class="symbol-label bg-light-info">
                  <i class="ki-duotone ki-chart-line-up fs-2x text-info">
                    <span class="path1"></span><span class="path2"></span>
                  </i>
                </span>
              </div>
              <div class="d-flex flex-column">
                <span class="text-muted fw-semibold fs-7 mb-1">Jumlah Rekening</span>
                <span class="text-gray-800 fw-bold fs-4" id="card-jumlah-rekening">{{ $jumlahRekening }}</span>
              </div>
            </div>
          </div>
        </div>
        {{-- Total Sebelum --}}
        <div class="col-xl-3">
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
                <span class="text-muted fw-semibold fs-7 mb-1">Sebelum Perubahan</span>
                <span class="text-gray-800 fw-bold fs-6" id="card-total-sebelum">
                  Rp {{ number_format($totalSebelum, 0, ',', '.') }}
                </span>
              </div>
            </div>
          </div>
        </div>
        {{-- Total Setelah --}}
        <div class="col-xl-3">
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
                <span class="text-muted fw-semibold fs-7 mb-1">Total Pembiayaan penerimaan</span>
                <span class="text-gray-800 fw-bold fs-6" id="card-total-setelah">
                  Rp {{ number_format($totalSetelah, 0, ',', '.') }}
                </span>
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
              <input type="text" data-kt-penerimaan-rincian-filter="search" class="form-control form-control-solid w-300px ps-12"
                placeholder="Cari rekening, uraian..." />
            </div>
          </div>
          <div class="card-toolbar gap-2">
            {{-- Bulk delete button (tampil saat ada yang dicentang) --}}
            <div class="d-none" data-kt-penerimaan-rincian-toolbar="selected">
              <div class="d-flex justify-content-end align-items-center gap-2">
                <span class="fw-bold text-gray-600 fs-7">
                  <span data-kt-penerimaan-rincian-selected="count">0</span> dipilih
                </span>
                <button type="button" class="btn btn-sm btn-danger fw-semibold" id="btn-bulk-delete">
                  <i class="ki-outline ki-trash fs-5 me-1"></i>Hapus Terpilih
                </button>
              </div>
            </div>
            <a href="{{ route('pembiayaan.penerimaan.index') }}" class="btn btn-sm btn-light fw-semibold">
              <i class="ki-outline ki-arrow-left fs-5 me-1"></i>Kembali
            </a>
            <button type="button" class="btn btn-sm btn-primary fw-semibold" data-bs-toggle="modal" data-bs-target="#modal_tambah_penerimaan">
              <i class="ki-outline ki-plus fs-5 me-1"></i>Tambah Data
            </button>
          </div>
        </div>

        <div class="card-body pt-0">
          <table id="kt_penerimaan_rincian_table" class="table align-middle table-row-dashed fs-6 gy-5">
            <thead>
              <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                <th class="w-10px pe-2">
                  <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                    <input class="form-check-input" type="checkbox" id="check-all" />
                  </div>
                </th>
                <th class="min-w-40px">No</th>
                <th class="min-w-200px">Rekening</th>
                <th class="min-w-200px">Uraian</th>
                <th class="min-w-150px">Keterangan</th>
                <th class="min-w-150px text-end">Sebelum Perubahan</th>
                <th class="min-w-150px text-end">Setelah Perubahan</th>
                <th class="min-w-100px text-end">Aksi</th>
              </tr>
            </thead>
            <tbody class="fw-semibold text-gray-600"></tbody>
          </table>
        </div>
      </div>

    </div>
  </div>

  {{-- Modal Tambah --}}
  @include('pembiayaan.penerimaan.rincian.partials.modal-create')

  <script>
    "use strict";

    var KTPenerimaanRincian = function() {
      var dt;
      var idSkpd = '{{ $id_skpd }}';
      var bulkUrl = '{{ route('pembiayaan.penerimaan.bulk-delete', $id_skpd) }}';

      var formatRupiah = function(value) {
        if (!value && value !== 0) return '<span class="text-muted fs-7">—</span>';
        return 'Rp ' + parseFloat(value).toLocaleString('id-ID', {
          minimumFractionDigits: 0
        });
      };

      // ── DataTable ──────────────────────────────────────────────────
      var initDatatable = function() {
        dt = $('#kt_penerimaan_rincian_table').DataTable({
          searchDelay: 500,
          processing: true,
          serverSide: true,
          order: [
            [2, 'asc']
          ],
          stateSave: false,
          language: {
            processing: '<span class="spinner-border spinner-border-sm align-middle me-2"></span> Memuat data...',
            emptyTable: 'Belum ada data pembiayaan penerimaan',
            zeroRecords: 'Data tidak ditemukan',
            lengthMenu: '_MENU_',
            info: 'Menampilkan _START_–_END_ dari _TOTAL_ data',
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
            url: "{{ route('pembiayaan.penerimaan.rincian.getData', $id_skpd) }}",
            type: 'GET',
            dataSrc: function(json) {
              return json.data;
            }
          },
          columns: [{
              data: null,
              orderable: false
            }, // 0 – checkbox
            {
              data: 'id',
              orderable: false
            }, // 1 – no urut
            {
              data: 'kode_akun'
            }, // 2 – rekening
            {
              data: 'uraian'
            }, // 3
            {
              data: 'keterangan'
            }, // 4
            {
              data: 'nilaimurni'
            }, // 5
            {
              data: 'total'
            }, // 6
            {
              data: null,
              orderable: false
            }, // 7 – aksi
          ],
          columnDefs: [
            // Checkbox
            {
              targets: 0,
              orderable: false,
              className: 'text-center',
              render: function(data, type, row) {
                return `<div class="form-check form-check-sm form-check-custom form-check-solid">
                          <input class="form-check-input check-item" type="checkbox" value="${row.id}" />
                        </div>`;
              }
            },
            // No urut
            {
              targets: 1,
              orderable: false,
              className: 'text-center text-muted fs-7',
              render: function(data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
              }
            },
            // Rekening (kode + nama dari tabel akun)
            {
              targets: 2,
              render: function(data, type, row) {
                var kode = row.akun_kode || row.kode_akun || '—';
                var nama = row.akun_nama || row.nama_akun || '—';
                return `<div class="d-flex flex-column">
                          <span class="text-gray-800 fw-bold fs-7">${kode}</span>
                          <span class="text-muted fw-semibold fs-8">${nama}</span>
                        </div>`;
              }
            },
            // Uraian
            {
              targets: 3,
              render: function(data) {
                return data ?
                  `<span class="text-gray-700 fs-7">${data}</span>` :
                  '<span class="text-muted fs-7">—</span>';
              }
            },
            // Keterangan
            {
              targets: 4,
              render: function(data) {
                return data ?
                  `<span class="text-gray-700 fs-7">${data}</span>` :
                  '<span class="text-muted fs-7">—</span>';
              }
            },
            // Sebelum perubahan
            {
              targets: 5,
              className: 'text-end',
              render: function(data) {
                return data > 0 ?
                  `<span class="text-gray-800 fw-bold fs-7">${formatRupiah(data)}</span>` :
                  '<span class="text-muted fs-7">—</span>';
              }
            },
            // Setelah perubahan
            {
              targets: 6,
              className: 'text-end',
              render: function(data, type, row) {
                if (!data || data <= 0) return '<span class="text-muted fs-7">—</span>';
                var selisih = parseFloat(data) - parseFloat(row.nilaimurni || 0);
                var color = selisih >= 0 ? 'danger' : 'success';
                var icon = selisih >= 0 ? 'ki-arrow-up' : 'ki-arrow-down';
                var selisihEl = row.nilaimurni > 0 ?
                  `<span class="text-${color} fs-9 fw-semibold">
                       <i class="ki-outline ${icon} fs-10 text-${color}"></i>
                       Rp ${Math.abs(selisih).toLocaleString('id-ID')}
                     </span>` :
                  '';
                return `<div class="d-flex flex-column align-items-end">
                          <span class="text-gray-800 fw-bold fs-7">${formatRupiah(data)}</span>
                          ${selisihEl}
                        </div>`;
              }
            },
            // Aksi
            {
              targets: 7,
              className: 'text-end',
              render: function(data, type, row) {
                return `<div class="d-flex justify-content-end gap-2">
              <a href="{{ url('pembiayaan/penerimaan') }}/${idSkpd}/${row.id}/edit"
                class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm" 
                title="Edit">
                <i class="ki-outline ki-pencil fs-5"></i>
              </a>
              <button type="button"
                      class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm btn-delete"
                      data-id="${row.id}"
                      title="Hapus">
                <i class="ki-outline ki-trash fs-5"></i>
              </button>
            </div>`;
              }
            }
          ],
        });
      };

      // ── Search ─────────────────────────────────────────────────────
      var handleSearch = function() {
        const el = document.querySelector('[data-kt-penerimaan-rincian-filter="search"]');
        el.addEventListener('keyup', function(e) {
          dt.search(e.target.value).draw();
        });
      };

      // ── Select All Checkbox ────────────────────────────────────────
      var handleSelectAll = function() {
        $('#check-all').on('change', function() {
          var checked = this.checked;
          $('.check-item').prop('checked', checked);
          refreshBulkToolbar();
        });

        $('#kt_penerimaan_rincian_table').on('change', '.check-item', function() {
          var total = $('.check-item').length;
          var checked = $('.check-item:checked').length;
          $('#check-all').prop('indeterminate', checked > 0 && checked < total);
          $('#check-all').prop('checked', checked === total);
          refreshBulkToolbar();
        });
      };

      var refreshBulkToolbar = function() {
        var count = $('.check-item:checked').length;
        $('[data-kt-penerimaan-rincian-selected="count"]').text(count);
        if (count > 0) {
          $('[data-kt-penerimaan-rincian-toolbar="selected"]').removeClass('d-none');
        } else {
          $('[data-kt-penerimaan-rincian-toolbar="selected"]').addClass('d-none');
        }
      };

      // ── Single Delete ──────────────────────────────────────────────
      var handleSingleDelete = function() {
        $('#kt_penerimaan_rincian_table').on('click', '.btn-delete', function() {
          var id = $(this).data('id');
          var url = `/pembiayaan/penerimaan/${idSkpd}/${id}/destroy`;

          Swal.fire({
            text: 'Data yang dihapus tidak dapat dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            buttonsStyling: false,
            customClass: {
              confirmButton: 'btn btn-danger',
              cancelButton: 'btn btn-secondary me-3'
            },
            reverseButtons: true
          }).then(function(result) {
            if (result.isConfirmed) {
              $.ajax({
                url: url,
                type: 'DELETE',
                data: {
                  _token: '{{ csrf_token() }}'
                },
                success: function(res) {
                  if (res.success) {
                    toastr.success('Data berhasil dihapus.', 'BERHASIL');
                    dt.ajax.reload(null, false);
                  }
                },
                error: function() {
                  Swal.fire('Gagal', 'Terjadi kesalahan saat menghapus data.', 'error');
                }
              });
            }
          });
        });
      };

      // ── Bulk Delete ────────────────────────────────────────────────
      var handleBulkDelete = function() {
        $('#btn-bulk-delete').on('click', function() {
          var ids = $('.check-item:checked').map(function() {
            return $(this).val();
          }).get();
          if (!ids.length) return;

          Swal.fire({
            text: ids.length + ' data akan dihapus secara permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus Semua!',
            cancelButtonText: 'Batal',
            buttonsStyling: false,
            customClass: {
              confirmButton: 'btn btn-danger',
              cancelButton: 'btn btn-secondary me-3'
            },
            reverseButtons: true
          }).then(function(result) {
            if (result.isConfirmed) {
              $.ajax({
                url: bulkUrl,
                type: 'POST',
                data: {
                  _token: '{{ csrf_token() }}',
                  ids: ids
                },
                success: function(res) {
                  if (res.success) {
                    toastr.success(res.message, 'BERHASIL');
                    dt.ajax.reload(null, false);
                    $('#check-all').prop('checked', false);
                    refreshBulkToolbar();
                  }
                },
                error: function() {
                  Swal.fire('Gagal', 'Terjadi kesalahan saat menghapus data.', 'error');
                }
              });
            }
          });
        });
      };

      // ── Public ─────────────────────────────────────────────────────
      return {
        init: function() {
          initDatatable();
          handleSearch();
          handleSelectAll();
          handleSingleDelete();
          handleBulkDelete();
        }
      };
    }();

    document.addEventListener("DOMContentLoaded", function() {
      KTPenerimaanRincian.init();

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
