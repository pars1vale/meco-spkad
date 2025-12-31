@extends('layouts.master')
@section('content')
  <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
      <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
          <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">Akun</h1>
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
            <li class="breadcrumb-item text-muted">Akun</li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-fluid">

      <!-- Session Messages -->
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
              <input type="text" data-kt-docs-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Cari Akun">
            </div>
          </div>
          <div class="card-toolbar">
            <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_akun">
                <i class="ki-outline ki-plus fs-2"></i>
                Tambah Akun
              </button>
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
          <div class="table-responsive">
            <table id="kt_akun_table" class="table align-middle table-row-dashed fs-6 gy-5">
              <thead>
                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                  <th class="w-10px pe-2">
                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                      <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_akun_table .form-check-input"
                        value="1" id="master_checkbox" />
                    </div>
                  </th>
                  <th class="min-w-100px">Kode</th>
                  <th class="min-w-250px">Nama Akun</th>
                  <th class="min-w-100px">Pendapatan</th>
                  <th class="min-w-100px">Belanja</th>
                  <th class="min-w-100px">Pembiayaan</th>
                  <th class="text-end min-w-150px">Aksi</th>
                </tr>
              </thead>
              <tbody class="fw-semibold text-gray-600">
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  @include('referensi.akun.partials.modal-create')
  @include('referensi.akun.partials.modal-view')

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      var dt;

      var initDatatable = function() {
        dt = $("#kt_akun_table").DataTable({
          searchDelay: 500,
          processing: true,
          serverSide: true,
          scrollX: true,
          responsive: false,
          order: [
            [1, 'asc']
          ],
          stateSave: false,
          ajax: {
            url: "{{ route('referensi.akun.getData') }}",
            type: "GET",
          },
          columns: [{
              data: 'id',
              orderable: false,
              render: function(data) {
                return `<div class="form-check form-check-sm form-check-custom form-check-solid">
                  <input class="form-check-input row-checkbox" type="checkbox" value="${data}" />
                </div>`;
              }
            },
            {
              data: 'kode_akun'
            },
            {
              data: 'nama_akun'
            },
            {
              data: 'pendapatan',
              render: function(data, type, row) {
                const badgeClass = row.is_pendapatan ? 'success' : 'secondary';
                return `<span class="badge badge-light-${badgeClass}">${data}</span>`;
              }
            },
            {
              data: 'belanja',
              render: function(data, type, row) {
                const badgeClass = row.is_bl ? 'success' : 'secondary';
                return `<span class="badge badge-light-${badgeClass}">${data}</span>`;
              }
            },
            {
              data: 'pembiayaan',
              render: function(data, type, row) {
                const badgeClass = row.is_pembiayaan ? 'success' : 'secondary';
                return `<span class="badge badge-light-${badgeClass}">${data}</span>`;
              }
            },
            {
              data: 'id',
              orderable: false,
              className: 'text-end',
              render: function(data, type, row) {
                return `
                  <div class="d-flex justify-content-end gap-1">
                    <button type="button" class="btn btn-icon btn-bg-light btn-active-color-info btn-sm view-btn" 
                      data-id="${data}" title="Lihat Detail">
                      <i class="ki-outline ki-eye fs-2"></i>
                    </button>
                    <a href="{{ url('referensi/akun') }}/${data}/edit" 
                       class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm" title="Edit">
                      <i class="ki-outline ki-pencil fs-2"></i>
                    </a>
                    <button type="button" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm delete-btn" 
                      data-id="${data}" data-name="${row.nama_akun}" title="Hapus">
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
            "<'row mt-4'<'col-sm-12 col-md-5 d-flex align-items-center justify-content-start'li>" +
            "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-end'p>>",
          language: {
            paginate: {
              previous: '<i class="ki-outline ki-arrow-left fs-4"></i>',
              next: '<i class="ki-outline ki-arrow-right fs-4"></i>'
            },
            processing: '<span class="spinner-border spinner-border-sm align-middle ms-2"></span> Loading...'
          },
          drawCallback: function() {
            attachCheckboxListeners();
            // View listeners sudah ada di modal-view.blade.php
            if (typeof window.attachViewListeners === 'function') {
              window.attachViewListeners();
            }
            attachDeleteListeners();
          }
        });
      };

      var attachCheckboxListeners = function() {
        const container = document.querySelector('#kt_akun_table');
        const masterCheckbox = document.getElementById('master_checkbox');
        const rowCheckboxes = container.querySelectorAll('.row-checkbox');

        if (masterCheckbox) {
          masterCheckbox.onclick = function() {
            rowCheckboxes.forEach(cb => cb.checked = this.checked);
            toggleToolbars();
          };
        }

        rowCheckboxes.forEach(cb => {
          cb.onclick = function() {
            updateMasterCheckbox();
            toggleToolbars();
          };
        });
      };

      var updateMasterCheckbox = function() {
        const container = document.querySelector('#kt_akun_table');
        const masterCheckbox = document.getElementById('master_checkbox');
        const rowCheckboxes = container.querySelectorAll('.row-checkbox');
        const checkedBoxes = container.querySelectorAll('.row-checkbox:checked');

        if (masterCheckbox && rowCheckboxes.length > 0) {
          masterCheckbox.checked = checkedBoxes.length === rowCheckboxes.length;
          masterCheckbox.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < rowCheckboxes.length;
        }
      };

      var toggleToolbars = function() {
        const container = document.querySelector('#kt_akun_table');
        const toolbarBase = document.querySelector('[data-kt-customer-table-toolbar="base"]');
        const toolbarSelected = document.querySelector('[data-kt-customer-table-toolbar="selected"]');
        const selectedCount = document.querySelector('[data-kt-customer-table-select="selected_count"]');
        const checkedBoxes = container.querySelectorAll('.row-checkbox:checked');

        if (checkedBoxes.length > 0) {
          selectedCount.textContent = checkedBoxes.length;
          toolbarBase.classList.add('d-none');
          toolbarSelected.classList.remove('d-none');
        } else {
          toolbarBase.classList.remove('d-none');
          toolbarSelected.classList.add('d-none');
        }
      };

      var handleSearchDatatable = function() {
        const filterSearch = document.querySelector('[data-kt-docs-table-filter="search"]');
        filterSearch.addEventListener('keyup', function(e) {
          dt.search(e.target.value).draw();
        });
      };

      var attachDeleteListeners = function() {
        const deleteButtons = document.querySelectorAll('.delete-btn');

        deleteButtons.forEach(button => {
          button.onclick = function(e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');

            Swal.fire({
              title: 'Apakah Anda yakin?',
              html: `Data akun <strong>"${name}"</strong> akan dihapus!`,
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
                form.action = `{{ url('referensi/akun') }}/${id}`;
                form.innerHTML = `
                  @csrf
                  @method('DELETE')
                `;
                document.body.appendChild(form);
                form.submit();
              }
            });
          };
        });
      };

      var handleBulkDelete = function() {
        const bulkDeleteBtn = document.getElementById('bulk_delete_btn');

        if (bulkDeleteBtn) {
          bulkDeleteBtn.onclick = function() {
            const container = document.querySelector('#kt_akun_table');
            const checkedBoxes = container.querySelectorAll('.row-checkbox:checked');

            if (checkedBoxes.length === 0) {
              Swal.fire({
                icon: 'info',
                title: 'Tidak ada data dipilih',
                text: 'Pilih minimal satu akun untuk dihapus.',
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
              html: `Anda akan menghapus <strong>${checkedBoxes.length}</strong> data akun terpilih!`,
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
                form.action = '{{ route('referensi.akun.bulk-delete') }}';

                let formContent = '@csrf';
                ids.forEach(id => {
                  formContent += `<input type="hidden" name="ids[]" value="${id}">`;
                });
                form.innerHTML = formContent;

                document.body.appendChild(form);
                form.submit();
              }
            });
          };
        }
      };

      initDatatable();
      handleSearchDatatable();
      handleBulkDelete();

      const sessionMessages = document.querySelectorAll('#session-messages div');
      sessionMessages.forEach(msg => {
        const type = msg.dataset.type;
        const message = msg.dataset.message;

        toastr.options = {
          closeButton: true,
          progressBar: true,
          positionClass: "toastr-top-right",
          timeOut: 5000
        };

        if (type === 'error') toastr.error(message, "GAGAL");
        else if (type === 'success') toastr.success(message, "BERHASIL");
        else toastr.info(message);
      });
    });
  </script>
@endsection
