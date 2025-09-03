@extends('layouts.master')
@section('content')
  <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
      <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
          <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">Kegiatan</h1>
          <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
            <li class="breadcrumb-item text-muted">
              <a href="{{ url('/') }}" class="text-muted text-hover-primary">Home</a>
            </li>
            <li class="breadcrumb-item">
              <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">Referensi</li>
            <li class="breadcrumb-item">
              <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">Kegiatan</li>
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

      <div class="card">
        <div class="card-header border-0 pt-6">
          <div class="card-title">
            <div class="d-flex align-items-center position-relative my-1">
              <i class="ki-outline ki-magnifier fs-3 position-absolute ms-4"></i>
              <input type="text" id="kt_datatable_search_input" class="form-control form-control-solid w-250px ps-12" placeholder="Cari Kegiatan">
            </div>
          </div>
          <div class="card-toolbar">
            <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
              <div class="w-150px me-3"></div>
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_kegiatan">Tambah Kegiatan</button>
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
                <span>Tidak ada data Kegiatan yang ditemukan.</span>
              </div>
            </div>
          @else
            <table id="kt_kegiatan_table" class="table table-striped table-row-bordered gy-5 gs-7">
              <thead>
                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                  <th class="w-10px pe-2">
                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                      <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_kegiatan_table .form-check-input"
                        value="1" />
                    </div>
                  </th>
                  <th class="min-w-100px">Kode</th>
                  <th class="min-w-300px">Nama Kegiatan</th>
                  <th class="d-none">Urusan Group</th>
                  <th class="d-none">Bidang Group</th>
                  <th class="d-none">Program Group</th>
                  <th class="min-w-100px">Aksi</th>
                </tr>
              </thead>
              <tbody class="fw-semibold text-gray-600">
                @foreach ($data as $urusanGroup)
                  @php
                    $urusan = $urusanGroup->first();
                    $bidangGrouped = $urusanGroup->groupBy('id_bidang_urusan');
                  @endphp

                  @foreach ($bidangGrouped as $bidangGroup)
                    @php
                      $bidang = $bidangGroup->first();
                      $programGrouped = $bidangGroup->groupBy('id_program');
                    @endphp

                    @foreach ($programGrouped as $programGroup)
                      @php
                        $program = $programGroup->first();
                      @endphp

                      @foreach ($programGroup as $kegiatan)
                        <tr>
                          <td>
                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                              <input class="form-check-input" type="checkbox" value="{{ $kegiatan->id }}" />
                            </div>
                          </td>
                          <td class="fw-bold">{{ $kegiatan->kode_kegiatan }}</td>
                          <td>{{ $kegiatan->nama_kegiatan }}</td>
                          <td class="d-none">[URUSAN] {{ $urusan->kode_urusan }} {{ $urusan->nama_urusan }}</td>
                          <td class="d-none">[BIDANG URUSAN] {{ $bidang->kode_bidang_urusan }} {{ $bidang->nama_bidang_urusan }}</td>
                          <td class="d-none">[PROGRAM] {{ $program->kode_program }} {{ $program->nama_program }}</td>
                          <td>
                            <div class="d-flex justify-content-end">
                              <a href="{{ route('kegiatan.edit', $kegiatan->id) }}"
                                class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title="Edit Kegiatan">
                                <i class="ki-outline ki-pencil fs-2"></i>
                              </a>
                              <form action="{{ route('kegiatan.destroy', $kegiatan->id) }}" method="POST" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm delete-btn"
                                  title="Hapus Kegiatan" data-name="{{ $kegiatan->nama_kegiatan }}">
                                  <i class="ki-outline ki-trash fs-2"></i>
                                </button>
                              </form>
                            </div>
                          </td>
                        </tr>
                      @endforeach
                    @endforeach
                  @endforeach
                @endforeach
              </tbody>
            </table>
          @endif
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Tambah Kegiatan -->
  <div class="modal fade" id="kt_modal_add_kegiatan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
      <div class="modal-content">
        <form class="form" action="{{ route('kegiatan.store') }}" method="POST" id="kt_modal_add_kegiatan_form">
          @csrf
          <div class="modal-header" id="kt_modal_add_kegiatan_header">
            <h2 class="fw-bold">Tambah Kegiatan</h2>
            <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
              <i class="ki-outline ki-cross fs-1"></i>
            </div>
          </div>

          <div class="modal-body py-10 px-lg-17">
            <div class="scroll-y me-n7 pe-7" id="kt_modal_add_kegiatan_scroll">

              <!-- Program -->
              <div class="fv-row mb-7">
                <label class="required fs-6 fw-semibold mb-2">Program</label>
                <select class="form-select form-select-solid @error('id_program') is-invalid @enderror" name="id_program" required>
                  <option value="">Pilih Program</option>
                  @php
                    $currentUrusan = null;
                    $currentBidang = null;
                  @endphp
                  @foreach ($listProgram as $program)
                    @if ($currentUrusan !== $program->nama_urusan)
                      @if ($currentUrusan !== null)
                        </optgroup>
                      @endif
                      <optgroup label="{{ $program->nama_urusan }}">
                        @php
                          $currentUrusan = $program->nama_urusan;
                          $currentBidang = null;
                        @endphp
                    @endif
                    @if ($currentBidang !== $program->nama_bidang_urusan)
                      @if ($currentBidang !== null)
                        </optgroup>
                      @endif
                      <optgroup label="&nbsp;&nbsp;{{ $program->nama_bidang_urusan }}">
                        @php $currentBidang = $program->nama_bidang_urusan; @endphp
                    @endif
                    <option value="{{ $program->id }}" {{ old('id_program') == $program->id ? 'selected' : '' }}>
                      &nbsp;&nbsp;&nbsp;&nbsp;{{ $program->kode_program }} - {{ $program->nama_program }}
                    </option>
                  @endforeach
                  @if ($currentBidang !== null)
                    </optgroup>
                  @endif
                  @if ($currentUrusan !== null)
                    </optgroup>
                  @endif
                </select>
                @error('id_program')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Kode Kegiatan -->
              <div class="fv-row mb-7">
                <label class="required fs-6 fw-semibold mb-2">Kode Kegiatan</label>
                <input type="text" class="form-control form-control-solid @error('kode_kegiatan') is-invalid @enderror"
                  placeholder="Masukkan kode kegiatan" name="kode_kegiatan" value="{{ old('kode_kegiatan') }}" maxlength="20" required />
                @error('kode_kegiatan')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">Maksimal 20 karakter</div>
              </div>

              <!-- Nama Kegiatan -->
              <div class="fv-row mb-7">
                <label class="required fs-6 fw-semibold mb-2">Nama Kegiatan</label>
                <textarea class="form-control form-control-solid @error('nama_kegiatan') is-invalid @enderror" rows="3" placeholder="Masukkan nama kegiatan"
                  name="nama_kegiatan" maxlength="500" required>{{ old('nama_kegiatan') }}</textarea>
                @error('nama_kegiatan')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">Maksimal 500 karakter</div>
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
      var table = $('#kt_kegiatan_table').DataTable({
        responsive: true,
        searchDelay: 500,
        processing: true,
        serverSide: false,
        order: [
          [3, 'asc'],
          [4, 'asc'],
          [5, 'asc'],
          [1, 'asc']
        ],
        columnDefs: [{
            targets: [0],
            orderable: false,
            className: 'text-center'
          },
          {
            targets: [3, 4, 5],
            visible: false
          },
          {
            targets: [1, 2],
            className: 'fs-6'
          },
          {
            targets: [6],
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
        },
        drawCallback: function(settings) {
          var api = this.api();
          var rows = api.rows({
            page: 'current'
          }).nodes();
          var lastUrusan = null;
          var lastBidang = null;
          var lastProgram = null;

          api.column(3, {
            page: 'current'
          }).data().each(function(urusan, i) {
            var bidang = api.cell(rows[i], 4).data();
            var program = api.cell(rows[i], 5).data();

            if (lastUrusan !== urusan) {
              $(rows[i]).before(
                '<tr class="group bg-light-primary">' +
                '<td colspan="7" class="fw-bold fs-5 px-4 py-3">' +
                urusan +
                '</td></tr>'
              );
              lastUrusan = urusan;
              lastBidang = null;
              lastProgram = null;
            }

            if (lastBidang !== bidang) {
              $(rows[i]).before(
                '<tr class="group bg-secondary">' +
                '<td colspan="7" class="fw-bold fs-5 px-4 py-3">' +
                bidang +
                '</td></tr>'
              );
              lastBidang = bidang;
              lastProgram = null;
            }

            if (lastProgram !== program) {
              $(rows[i]).before(
                '<tr class="group bg-light">' +
                '<td colspan="7" class="fw-bold fs-5 px-4 py-3">' +
                program +
                '</td></tr>'
              );
              lastProgram = program;
            }
          });
        }
      });

      $('#kt_datatable_search_input').keyup(function() {
        table.search(this.value).draw();
      });

      $('#kt_kegiatan_table').on('click', 'tr.group', function() {
        var colIdx;
        if ($(this).hasClass('bg-light-primary')) colIdx = 3; // Urusan
        else if ($(this).hasClass('bg-secondary')) colIdx = 4; // Bidang Urusan
        else colIdx = 5; // Program

        var currentOrder = table.order()[0];

        if (currentOrder[0] === colIdx && currentOrder[1] === 'asc') {
          table.order([colIdx, 'desc']).draw();
        } else {
          table.order([colIdx, 'asc']).draw();
        }
      });

      // === SweetAlert2 Session Messages ===
      const sessionMessages = document.querySelectorAll('#session-messages div');
      sessionMessages.forEach(msg => {
        const type = msg.dataset.type;
        const message = msg.dataset.message;

        Swal.fire({
          icon: type,
          title: type === 'success' ? 'Berhasil' : 'Gagal',
          text: message,
          confirmButtonText: 'OK',
          buttonsStyling: false,
          customClass: {
            confirmButton: "btn btn-primary"
          }
        });
      });

      // === Delete confirmation pakai SweetAlert2 ===
      $(document).on('click', '.delete-btn', function(e) {
        e.preventDefault();
        const form = $(this).closest('form');
        const name = $(this).data('name');

        Swal.fire({
          title: 'Apakah Anda yakin?',
          html: `Data kegiatan <strong>"${name}"</strong> akan dihapus!`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Ya, hapus!',
          cancelButtonText: 'Batal',
          buttonsStyling: false,
          customClass: {
            confirmButton: "btn btn-danger",
            cancelButton: "btn btn-secondary"
          }
        }).then((result) => {
          if (result.isConfirmed) {
            form.submit();
          }
        });
      });

      // === Bulk selection ===
      const checkboxes = document.querySelectorAll('#kt_kegiatan_table tbody input[type="checkbox"]');
      const masterCheckbox = document.querySelector('#kt_kegiatan_table thead input[type="checkbox"]');
      const selectedToolbar = document.querySelector('[data-kt-customer-table-toolbar="selected"]');
      const baseToolbar = document.querySelector('[data-kt-customer-table-toolbar="base"]');
      const selectedCount = document.querySelector('[data-kt-customer-table-select="selected_count"]');

      function updateToolbar() {
        const checkedBoxes = document.querySelectorAll('#kt_kegiatan_table tbody input[type="checkbox"]:checked');

        if (checkedBoxes.length > 0) {
          selectedCount.textContent = checkedBoxes.length;
          baseToolbar.classList.add('d-none');
          selectedToolbar.classList.remove('d-none');
        } else {
          baseToolbar.classList.remove('d-none');
          selectedToolbar.classList.add('d-none');
        }
      }

      // Master checkbox functionality
      if (masterCheckbox) {
        masterCheckbox.addEventListener('change', function() {
          checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
          });
          updateToolbar();
        });
      }

      // Individual checkbox functionality
      checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
          updateToolbar();
          const checkedBoxes = document.querySelectorAll('#kt_kegiatan_table tbody input[type="checkbox"]:checked');
          if (masterCheckbox) {
            masterCheckbox.checked = checkedBoxes.length === checkboxes.length;
            masterCheckbox.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < checkboxes.length;
          }
        });
      });

      // === Bulk delete confirmation pakai SweetAlert2 ===
      document.getElementById('bulk_delete_btn')?.addEventListener('click', function() {
        const checkedBoxes = document.querySelectorAll('#kt_kegiatan_table tbody input[type="checkbox"]:checked');

        if (checkedBoxes.length === 0) {
          Swal.fire({
            icon: 'info',
            title: 'Tidak ada data dipilih',
            text: 'Pilih minimal satu kegiatan untuk dihapus.',
            confirmButtonText: 'OK',
            buttonsStyling: false,
            customClass: {
              confirmButton: "btn btn-primary"
            }
          });
          return;
        }

        Swal.fire({
          title: 'Apakah Anda yakin?',
          html: `Anda akan menghapus <strong>${checkedBoxes.length}</strong> data kegiatan terpilih!`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Ya, hapus!',
          cancelButtonText: 'Batal',
          buttonsStyling: false,
          customClass: {
            confirmButton: "btn btn-danger",
            cancelButton: "btn btn-secondary"
          }
        }).then((result) => {
          if (result.isConfirmed) {
            const ids = Array.from(checkedBoxes).map(cb => cb.value);

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('kegiatan.bulk-delete') }}';

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            ids.forEach(id => {
              const input = document.createElement('input');
              input.type = 'hidden';
              input.name = 'ids[]';
              input.value = id;
              form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
          }
        });
      });

      // === Form validation ===
      const form = document.getElementById('kt_modal_add_kegiatan_form');
      const submitButton = document.getElementById('kt_modal_add_kegiatan_submit');

      if (form && submitButton) {
        form.addEventListener('submit', function(e) {
          const idProgram = form.querySelector('select[name="id_program"]').value;
          const kodeKegiatan = form.querySelector('input[name="kode_kegiatan"]').value.trim();
          const namaKegiatan = form.querySelector('textarea[name="nama_kegiatan"]').value.trim();

          if (!idProgram || !kodeKegiatan || !namaKegiatan) {
            e.preventDefault();
            Swal.fire({
              icon: 'error',
              title: 'Validasi gagal',
              text: 'Semua field wajib diisi!',
              confirmButtonText: 'OK',
              buttonsStyling: false,
              customClass: {
                confirmButton: "btn btn-primary"
              }
            });
            return;
          }

          submitButton.setAttribute('data-kt-indicator', 'on');
          submitButton.disabled = true;
        });
      }

      // === Auto show modal if validation errors exist ===
      @if ($errors->any() && old('_token'))
        $('#kt_modal_add_kegiatan').modal('show');
      @endif
    });
  </script>
@endsection
