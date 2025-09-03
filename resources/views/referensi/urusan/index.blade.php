@extends('layouts.master')

@section('content')
  <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
      <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
          <h1 class="page-heading text-dark fw-bold fs-3 m-0">Urusan</h1>
          <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
            <li class="breadcrumb-item text-muted">
              <a href="{{ url('/home') }}" class="text-muted text-hover-primary">Home</a>
            </li>
            <li class="breadcrumb-item">
              <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">Referensi</li>
            <li class="breadcrumb-item">
              <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">Urusan</li>
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
              <input type="text" id="kt_datatable_search_input" class="form-control form-control-solid w-250px ps-12" placeholder="Cari Urusan">
            </div>
          </div>
          <div class="card-toolbar">
            <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
              <div class="w-150px me-3">
              </div>
              {{-- <button type="button" class="btn btn-light-primary me-3" data-bs-toggle="modal" data-bs-target="#kt_customers_export_modal">
                <i class="ki-outline ki-exit-up fs-2"></i>Export</button> --}}
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_urusan">Tambah Urusan</button>
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
                <span>Tidak ada data Urusan yang ditemukan.</span>
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
                  <th class="min-w-100px">Kode Urusan</th>
                  <th class="min-w-300px">Nama Urusan</th>
                  <th class="min-w-100px">Aksi</th>
                </tr>
              </thead>
              <tbody class="fw-semibold text-gray-600">
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
              </tbody>
            </table>
          @endif
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Tambah Urusan -->
  <div class="modal fade" id="kt_modal_add_urusan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
      <div class="modal-content">
        <form class="form" action="{{ route('urusan.store') }}" method="POST" id="kt_modal_add_urusan_form">
          @csrf
          <div class="modal-header" id="kt_modal_add_urusan_header">
            <h2 class="fw-bold">Tambah Urusan</h2>
            <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
              <i class="ki-outline ki-cross fs-1"></i>
            </div>
          </div>

          <div class="modal-body py-10 px-lg-17">
            <div class="scroll-y me-n7 pe-7" id="kt_modal_add_urusan_scroll">

              <!-- Kode Urusan -->
              <div class="fv-row mb-7">
                <label class="required fs-6 fw-semibold mb-2">Kode Urusan</label>
                <input type="text" class="form-control form-control-solid @error('kode_urusan') is-invalid @enderror"
                  placeholder="Masukkan kode urusan" name="kode_urusan" value="{{ old('kode_urusan') }}" maxlength="10" required />
                @error('kode_urusan')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">Maksimal 10 karakter</div>
              </div>

              <!-- Nama Urusan -->
              <div class="fv-row mb-7">
                <label class="required fs-6 fw-semibold mb-2">Nama Urusan</label>
                <input type="text" class="form-control form-control-solid @error('nama_urusan') is-invalid @enderror"
                  placeholder="Masukkan nama urusan" name="nama_urusan" value="{{ old('nama_urusan') }}" maxlength="255" required />
                @error('nama_urusan')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">Maksimal 255 karakter</div>
              </div>

            </div>
          </div>

          <div class="modal-footer flex-center">
            <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
            <button type="submit" id="kt_modal_add_urusan_submit" class="btn btn-primary">
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
      // Initialize DataTable
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
            targets: [1, 2],
            className: 'fs-6'
          },
          {
            targets: [3],
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
          html: `Data urusan <strong>"${name}"</strong> akan dihapus!`,
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
      const checkboxes = document.querySelectorAll('#kt_datatable_column_rendering tbody input[type="checkbox"]');
      const masterCheckbox = document.querySelector('#kt_datatable_column_rendering thead input[type="checkbox"]');
      const selectedToolbar = document.querySelector('[data-kt-customer-table-toolbar="selected"]');
      const baseToolbar = document.querySelector('[data-kt-customer-table-toolbar="base"]');
      const selectedCount = document.querySelector('[data-kt-customer-table-select="selected_count"]');

      function updateToolbar() {
        const checkedBoxes = document.querySelectorAll('#kt_datatable_column_rendering tbody input[type="checkbox"]:checked');

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
          const checkedBoxes = document.querySelectorAll('#kt_datatable_column_rendering tbody input[type="checkbox"]:checked');
          if (masterCheckbox) {
            masterCheckbox.checked = checkedBoxes.length === checkboxes.length;
            masterCheckbox.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < checkboxes.length;
          }
        });
      });

      // === Bulk delete confirmation pakai SweetAlert2 ===
      document.getElementById('bulk_delete_btn')?.addEventListener('click', function() {
        const checkedBoxes = document.querySelectorAll('#kt_datatable_column_rendering tbody input[type="checkbox"]:checked');

        if (checkedBoxes.length === 0) {
          Swal.fire({
            icon: 'info',
            title: 'Tidak ada data dipilih',
            text: 'Pilih minimal satu urusan untuk dihapus.',
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
          html: `Anda akan menghapus <strong>${checkedBoxes.length}</strong> data urusan terpilih!`,
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
            form.action = '{{ route('urusan.bulk-delete') }}';

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
      const form = document.getElementById('kt_modal_add_urusan_form');
      const submitButton = document.getElementById('kt_modal_add_urusan_submit');

      if (form && submitButton) {
        form.addEventListener('submit', function(e) {
          const kodeUrusan = form.querySelector('input[name="kode_urusan"]').value.trim();
          const namaUrusan = form.querySelector('input[name="nama_urusan"]').value.trim();

          if (!kodeUrusan || !namaUrusan) {
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
        $('#kt_modal_add_urusan').modal('show');
      @endif
    });
  </script>

@endsection
