@extends('layouts.master')

@section('content')
  {{-- Toolbar --}}
  <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
      <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
          <h1 class="page-heading text-dark fw-bold fs-3 m-0">Rincian Pendapatan</h1>
          <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
            <li class="breadcrumb-item text-muted">
              <a href="{{ url('/home') }}" class="text-muted text-hover-primary">Home</a>
            </li>
            <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
            <li class="breadcrumb-item text-muted">
              <a href="{{ route('pendapatan.index') }}" class="text-muted text-hover-primary">Pendapatan</a>
            </li>
            <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
            <li class="breadcrumb-item text-muted">Rincian</li>
          </ul>
        </div>
        <div class="d-flex align-items-center gap-2 gap-lg-3">
          <a href="{{ route('pendapatan.index') }}" class="btn btn-sm btn-light fw-semibold">
            <i class="ki-outline ki-arrow-left fs-6 me-1"></i>Kembali
          </a>
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

      <div class="row g-5 g-xl-8 mb-5">
        <div class="col-xl-6">
          <div class="card card-flush h-100">
            <div class="card-body d-flex align-items-center py-5">
              <div class="symbol symbol-50px me-5">
                <span class="symbol-label bg-light-primary">
                  <i class="ki-outline ki-office-bag fs-2x text-primary"></i>
                </span>
              </div>
              <div class="d-flex flex-column">
                <span class="text-muted fw-semibold fs-7 mb-1">SKPD Dipilih</span>
                <span class="text-gray-800 fw-bold fs-5">
                  {{ $skpd->nama_skpd ?? ($skpd->namaunit ?? '-') }}
                </span>
                <span class="text-muted fw-semibold fs-7 mt-1">
                  <span class="badge badge-light-primary">{{ $skpd->kode_skpd ?? ($skpd->kodeunit ?? '-') }}</span>
                  @if ($skpd->namakepala)
                    &nbsp;| Kepala: {{ $skpd->namakepala }}
                  @endif
                </span>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3">
          <div class="card card-flush h-100">
            <div class="card-body d-flex flex-column justify-content-between py-5">
              <div class="d-flex align-items-center mb-3">
                <span class="symbol symbol-40px me-3">
                  <span class="symbol-label bg-light-success">
                    <i class="ki-outline ki-finance-calculator fs-2 text-success"></i>
                  </span>
                </span>
                <span class="text-muted fw-semibold fs-7">Total Setelah Perubahan</span>
              </div>
              <div>
                <span class="text-gray-800 fw-bolder fs-3 d-block lh-1">
                  Rp {{ number_format($totalSetelah, 0, ',', '.') }}
                </span>
                <span class="text-muted fw-semibold fs-8 mt-1 d-block">
                  {{ $jumlahRekening }} rekening pendapatan
                </span>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3">
          @php
            $selisihTotal = $totalSetelah - $totalSebelum;
            $selisihPct = $totalSebelum > 0 ? ($selisihTotal / $totalSebelum) * 100 : 0;
            $selisihColor = $selisihTotal >= 0 ? 'success' : 'danger';
            $selisihIcon = $selisihTotal >= 0 ? 'ki-arrow-up' : 'ki-arrow-down';
          @endphp
          <div class="card card-flush h-100">
            <div class="card-body d-flex flex-column justify-content-between py-5">
              <div class="d-flex align-items-center mb-3">
                <span class="symbol symbol-40px me-3">
                  <span class="symbol-label bg-light-info">
                    <i class="ki-outline ki-chart-line fs-2 text-info"></i>
                  </span>
                </span>
                <span class="text-muted fw-semibold fs-7">Sebelum Perubahan</span>
              </div>
              <div>
                <span class="text-gray-800 fw-bolder fs-3 d-block lh-1">
                  Rp {{ number_format($totalSebelum, 0, ',', '.') }}
                </span>
                <span class="text-{{ $selisihColor }} fs-8 fw-semibold mt-1 d-block">
                  <i class="ki-outline {{ $selisihIcon }} fs-8 text-{{ $selisihColor }}"></i>
                  {{ number_format(abs($selisihPct), 2) }}% dari sebelum perubahan
                </span>
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
              <input type="text" data-kt-rincian-filter="search" class="form-control form-control-solid w-300px ps-12"
                placeholder="Cari rekening / uraian..." />
            </div>
          </div>
          <div class="card-toolbar">
            {{-- Toolbar: default --}}
            <div class="d-flex justify-content-end gap-2" data-kt-rincian-table-toolbar="base">
              <button type="button" class="btn btn-primary btn-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#modal_tambah_pendapatan">
                <i class="ki-outline ki-plus fs-6 me-1"></i>Tambah Pendapatan
              </button>
            </div>
            {{-- Toolbar: selected (bulk) --}}
            <div class="d-flex justify-content-end align-items-center d-none gap-2" data-kt-rincian-table-toolbar="selected">
              <div class="fw-bold me-3 text-gray-700">
                <span data-kt-rincian-table-select="selected_count">0</span> data terpilih
              </div>
              <button type="button" class="btn btn-danger btn-sm fw-semibold" data-kt-rincian-table-select="delete_selected">
                <i class="ki-outline ki-trash fs-6 me-1"></i>Hapus Terpilih
              </button>
            </div>
          </div>
        </div>

        <div class="card-body pt-0">
          <table id="kt_pendapatan_rincian_table" class="table align-middle table-row-dashed fs-6 gy-5">
            <thead>
              <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                <th class="w-10px pe-2">
                  <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                    <input class="form-check-input" type="checkbox" data-kt-check="true"
                      data-kt-check-target="#kt_pendapatan_rincian_table .form-check-input" value="1" />
                  </div>
                </th>
                <th class="min-w-40px">No</th>
                <th class="min-w-250px">Rekening</th>
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

  @include('pendapatan.rincian.partials.modal-create')

  <form id="delete-single-form" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
  </form>

  {{-- Hidden form: bulk delete --}}
  <form id="bulk-delete-form" method="POST" action="{{ route('pendapatan.bulk-delete', $id_skpd) }}" style="display:none;">
    @csrf
  </form>

  <script>
    "use strict";

    var KTPendapatanRincian = function() {
      var dt;
      var idSkpd = "{{ $id_skpd }}";

      // ── Helpers ──────────────────────────────────────────────────────
      var rupiah = function(v) {
        if (v === null || v === undefined || v === '') return '—';
        return 'Rp ' + parseFloat(v).toLocaleString('id-ID', {
          minimumFractionDigits: 0
        });
      };

      // ── Init DataTable ───────────────────────────────────────────────
      var initDatatable = function() {
        dt = $('#kt_pendapatan_rincian_table').DataTable({
          searchDelay: 500,
          processing: true,
          serverSide: true,
          order: [
            [2, 'asc']
          ],
          stateSave: false,
          pageLength: 25,
          select: {
            style: 'multi',
            selector: 'td:first-child input[type="checkbox"]',
            className: 'row-selected'
          },
          language: {
            processing: '<span class="spinner-border spinner-border-sm align-middle me-2"></span> Memuat data...',
            emptyTable: 'Belum ada data pendapatan untuk SKPD ini',
            zeroRecords: 'Data tidak ditemukan',
            lengthMenu: '_MENU_',
            info: 'Menampilkan _START_–_END_ dari _TOTAL_ rekening',
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
            url: "{{ route('pendapatan.rincian.getData', $id_skpd) }}",
            type: 'GET',
          },
          columns: [{
              data: 'id',
              orderable: false
            }, // 0 – checkbox
            {
              data: 'id',
              orderable: false
            }, // 1 – nomor
            {
              data: 'akun_kode'
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
              render: function(data) {
                return `<div class="form-check form-check-sm form-check-custom form-check-solid">
                          <input class="form-check-input" type="checkbox" value="${data}" />
                        </div>`;
              }
            },
            // Nomor urut
            {
              targets: 1,
              orderable: false,
              className: 'text-muted',
              render: function(data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
              }
            },
            // Rekening (kode + nama)
            {
              targets: 2,
              render: function(data, type, row) {
                var kode = row.akun_kode || row.kode_akun || '—';
                var nama = row.akun_nama || row.nama_akun || '—';
                return `<div class="d-flex flex-column">
                          <span class="badge badge-light-primary fs-8 fw-bold mb-1">${kode}</span>
                          <span class="text-gray-800 fw-semibold fs-7 text-wrap">${nama}</span>
                        </div>`;
              }
            },
            // Uraian
            {
              targets: 3,
              render: function(data) {
                return `<span class="text-gray-700 fs-7 text-wrap">${data ?? '—'}</span>`;
              }
            },
            // Keterangan
            {
              targets: 4,
              render: function(data) {
                return `<span class="text-muted fs-7 text-wrap">${data ?? '—'}</span>`;
              }
            },
            // Sebelum Perubahan
            {
              targets: 5,
              className: 'text-end',
              render: function(data) {
                return `<span class="text-gray-700 fw-semibold fs-6">${rupiah(data)}</span>`;
              }
            },
            // Setelah Perubahan
            {
              targets: 6,
              className: 'text-end',
              render: function(data, type, row) {
                var selisih = parseFloat(data || 0) - parseFloat(row.nilaimurni || 0);
                var color = selisih >= 0 ? 'success' : 'danger';
                var icon = selisih >= 0 ? 'ki-arrow-up' : 'ki-arrow-down';
                var selisihEl = (row.nilaimurni && selisih !== 0) ?
                  `<span class="text-${color} fs-8 fw-semibold">
                       <i class="ki-outline ${icon} fs-9 text-${color}"></i>
                       Rp ${Math.abs(selisih).toLocaleString('id-ID')}
                     </span>` :
                  '';
                return `<div class="d-flex flex-column align-items-end">
                          <span class="text-gray-800 fw-bold fs-6">${rupiah(data)}</span>
                          ${selisihEl}
                        </div>`;
              }
            },
            // Aksi — data-uraian diisi dari kolom uraian (fallback ke keterangan)
            {
              targets: 7,
              className: 'text-end',
              render: function(data, type, row) {
                var uraian = (row.uraian || row.keterangan || '—')
                  .replace(/"/g, '&quot;'); // escape untuk atribut HTML
                return `
                  <div class="d-flex justify-content-end gap-1">
                    <a href="{{ url('pendapatan') }}/${idSkpd}/${row.id}/edit"
                       class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm" title="Edit">
                      <i class="ki-outline ki-pencil fs-4"></i>
                    </a>
                    <button type="button"
                      class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm"
                      data-kt-rincian-filter="delete_row"
                      data-id="${row.id}"
                      data-uraian="${uraian}"
                      title="Hapus">
                      <i class="ki-outline ki-trash fs-4"></i>
                    </button>
                  </div>`;
              }
            },
          ],
          drawCallback: function() {
            initToggleToolbar();
            toggleToolbars();
            handleDeleteRow();
            KTMenu.createInstances();
          }
        });
      };

      // ── Search ───────────────────────────────────────────────────────
      var handleSearch = function() {
        document.querySelector('[data-kt-rincian-filter="search"]')
          .addEventListener('keyup', function(e) {
            dt.search(e.target.value).draw();
          });
      };

      // ── Delete single row ────────────────────────────────────────────
      var handleDeleteRow = function() {
        document.querySelectorAll('[data-kt-rincian-filter="delete_row"]').forEach(function(btn) {
          btn.addEventListener('click', function() {
            var id = this.dataset.id;
            var uraian = this.dataset.uraian || '—';

            Swal.fire({
              title: 'Hapus Data?',
              html: `Anda akan menghapus data pendapatan:<br><strong>${uraian}</strong>`,
              icon: 'warning',
              showCancelButton: true,
              buttonsStyling: false,
              confirmButtonText: 'Ya, Hapus!',
              cancelButtonText: 'Batal',
              customClass: {
                confirmButton: 'btn fw-bold btn-danger',
                cancelButton: 'btn fw-bold btn-active-light-primary'
              }
            }).then(function(result) {
              if (result.isConfirmed) {
                var form = document.getElementById('delete-single-form');
                form.action = `/pendapatan/${idSkpd}/${id}/destroy`;
                form.submit();
              }
            });
          });
        });
      };

      // ── Toggle toolbar (basis vs selected) ──────────────────────────
      var initToggleToolbar = function() {
        const container = document.querySelector('#kt_pendapatan_rincian_table');
        const checkboxes = container.querySelectorAll('[type="checkbox"]');
        const deleteBtn = document.querySelector('[data-kt-rincian-table-select="delete_selected"]');

        checkboxes.forEach(function(c) {
          c.addEventListener('click', function() {
            setTimeout(toggleToolbars, 50);
          });
        });

        if (deleteBtn) {
          deleteBtn.addEventListener('click', function() {
            const checked = container.querySelectorAll('tbody [type="checkbox"]:checked');
            if (!checked.length) return;

            Swal.fire({
              title: 'Hapus Data Terpilih?',
              html: `Anda akan menghapus <strong>${checked.length}</strong> data pendapatan.`,
              icon: 'warning',
              showCancelButton: true,
              buttonsStyling: false,
              confirmButtonText: 'Ya, Hapus!',
              cancelButtonText: 'Batal',
              customClass: {
                confirmButton: 'btn fw-bold btn-danger',
                cancelButton: 'btn fw-bold btn-active-light-primary'
              }
            }).then(function(result) {
              if (!result.isConfirmed) return;

              var form = document.getElementById('bulk-delete-form');
              form.querySelectorAll('input[name="ids[]"]').forEach(function(el) {
                el.remove();
              });
              Array.from(checked).forEach(function(cb) {
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = cb.value;
                form.appendChild(input);
              });
              form.submit();
            });
          });
        }
      };

      var toggleToolbars = function() {
        const container = document.querySelector('#kt_pendapatan_rincian_table');
        const toolbarBase = document.querySelector('[data-kt-rincian-table-toolbar="base"]');
        const toolbarSelected = document.querySelector('[data-kt-rincian-table-toolbar="selected"]');
        const selectedCount = document.querySelector('[data-kt-rincian-table-select="selected_count"]');
        const allCheckboxes = container.querySelectorAll('tbody [type="checkbox"]');

        var count = 0;
        allCheckboxes.forEach(function(c) {
          if (c.checked) count++;
        });

        if (count > 0) {
          selectedCount.innerHTML = count;
          toolbarBase.classList.add('d-none');
          toolbarSelected.classList.remove('d-none');
        } else {
          toolbarBase.classList.remove('d-none');
          toolbarSelected.classList.add('d-none');
        }
      };

      return {
        init: function() {
          initDatatable();
          handleSearch();
          initToggleToolbar();
        }
      };
    }();

    document.addEventListener("DOMContentLoaded", function() {
      KTPendapatanRincian.init();

      // Session messages
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
