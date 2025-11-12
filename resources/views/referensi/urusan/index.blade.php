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
          <!--begin::Wrapper-->
          <div class="card-title">
            <!--begin::Search-->
            <div class="d-flex align-items-center position-relative my-1">
              <i class="ki-outline ki-magnifier fs-3 position-absolute ms-4"></i>
              <input type="text" data-kt-docs-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Cari Urusan">
            </div>
            <!--end::Search-->
          </div>

          <!--begin::Toolbar-->
          <div class="card-toolbar">
            <div class="d-flex justify-content-end" data-kt-docs-table-toolbar="base">
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_urusan">
                <i class="ki-outline ki-plus fs-2"></i>
                Tambah Urusan
              </button>
            </div>
            <!--end::Toolbar-->

            <!--begin::Group actions-->
            <div class="d-flex justify-content-end align-items-center d-none" data-kt-docs-table-toolbar="selected">
              <div class="fw-bold me-5">
                <span class="me-2" data-kt-docs-table-select="selected_count"></span>Terpilih
              </div>
              <button type="button" class="btn btn-danger" id="bulk_delete_btn">Hapus yg Terpilih</button>
            </div>
            <!--end::Group actions-->
          </div>
          <!--end::Wrapper-->
        </div>

        <div class="card-body pt-0">
          <!--begin::Datatable-->
          <table id="kt_datatable_example_1" class="table align-middle table-row-dashed fs-6 gy-5">
            <thead>
              <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                <th class="w-10px pe-2">
                  <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                    <input class="form-check-input" type="checkbox" data-kt-check="true"
                      data-kt-check-target="#kt_datatable_example_1 .form-check-input" value="1" />
                  </div>
                </th>
                <th class="min-w-100px">Kode Urusan</th>
                <th class="min-w-300px">Nama Urusan</th>
                <th class="text-end min-w-100px">Aksi</th>
              </tr>
            </thead>
            <tbody class="fw-semibold text-gray-600">
            </tbody>
          </table>
          <!--end::Datatable-->
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Tambah Urusan -->
  <div class="modal fade" id="kt_modal_add_urusan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
      <div class="modal-content">
        <form class="form" action="{{ route('referensi.urusan.store') }}" method="POST" id="kt_modal_add_urusan_form">
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
      var table;
      var dt;

      // Initialize DataTable dengan Server-Side Processing
      var initDatatable = function() {
        dt = $("#kt_datatable_example_1").DataTable({
          searchDelay: 500,
          processing: true,
          serverSide: true,
          order: [
            [1, 'asc']
          ],
          stateSave: false,
          ajax: {
            url: "{{ route('referensi.urusan.getData') }}",
            type: "GET",
          },
          columns: [{
              data: 'id',
              orderable: false,
              render: function(data) {
                return `
                  <div class="form-check form-check-sm form-check-custom form-check-solid">
                    <input class="form-check-input" type="checkbox" value="${data}" />
                  </div>`;
              }
            },
            {
              data: 'kode_urusan'
            },
            {
              data: 'nama_urusan'
            },
            {
              data: 'actions',
              orderable: false,
              className: 'text-end',
              render: function(data, type, row) {
                return `
                  <div class="d-flex justify-content-end">
                    <a href="{{ url('referensi/urusan') }}/${data}/edit" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title="Edit Urusan">
                      <i class="ki-outline ki-pencil fs-2"></i>
                    </a>
                    <button type="button" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm delete-btn" 
                      data-id="${data}" data-name="${row.nama_urusan}" title="Hapus Urusan">
                      <i class="ki-outline ki-trash fs-2"></i>
                    </button>
                  </div>
                `;
              }
            }
          ],
          columnDefs: [{
              targets: 0,
              orderable: false,
              className: 'text-center'
            },
            {
              targets: [1, 2],
              className: 'fs-6'
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
            },
            processing: '<span class="spinner-border spinner-border-sm align-middle ms-2"></span> Loading...'
          }
        });

        table = dt.$;

        // Re-init functions on every table re-draw
        dt.on('draw', function() {
          initToggleToolbar();
          handleDeleteRows();
        });
      };

      // Search Datatable
      var handleSearchDatatable = function() {
        const filterSearch = document.querySelector('[data-kt-docs-table-filter="search"]');
        filterSearch.addEventListener('keyup', function(e) {
          dt.search(e.target.value).draw();
        });
      };

      // Delete rows
      var handleDeleteRows = function() {
        const deleteButtons = document.querySelectorAll('.delete-btn');

        deleteButtons.forEach(button => {
          button.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');

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
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `{{ url('referensi/urusan') }}/${id}`;

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                form.appendChild(methodField);

                document.body.appendChild(form);
                form.submit();
              }
            });
          });
        });
      };

      // Init toggle toolbar
      var initToggleToolbar = function() {
        const container = document.querySelector('#kt_datatable_example_1');
        const checkboxes = container.querySelectorAll('[type="checkbox"]');
        const deleteSelected = document.querySelector('#bulk_delete_btn');

        checkboxes.forEach(c => {
          c.addEventListener('click', function() {
            setTimeout(function() {
              toggleToolbars();
            }, 50);
          });
        });

        if (deleteSelected) {
          deleteSelected.addEventListener('click', function() {
            const checkedBoxes = container.querySelectorAll('tbody [type="checkbox"]:checked');

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
                form.action = '{{ route('referensi.urusan.bulk-delete') }}';

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
        }
      };

      // Toggle toolbars
      var toggleToolbars = function() {
        const container = document.querySelector('#kt_datatable_example_1');
        const toolbarBase = document.querySelector('[data-kt-docs-table-toolbar="base"]');
        const toolbarSelected = document.querySelector('[data-kt-docs-table-toolbar="selected"]');
        const selectedCount = document.querySelector('[data-kt-docs-table-select="selected_count"]');
        const allCheckboxes = container.querySelectorAll('tbody [type="checkbox"]');

        let checkedState = false;
        let count = 0;

        allCheckboxes.forEach(c => {
          if (c.checked) {
            checkedState = true;
            count++;
          }
        });

        if (checkedState) {
          selectedCount.innerHTML = count;
          toolbarBase.classList.add('d-none');
          toolbarSelected.classList.remove('d-none');
        } else {
          toolbarBase.classList.remove('d-none');
          toolbarSelected.classList.add('d-none');
        }
      };

      // Initialize
      initDatatable();
      handleSearchDatatable();
      initToggleToolbar();

      // Session messages
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

      // Form validation
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

      // Auto show modal if validation errors exist
      @if ($errors->any() && old('_token'))
        $('#kt_modal_add_urusan').modal('show');
      @endif
    });
  </script>
@endsection
