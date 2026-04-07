@extends('layouts.master')

@section('content')
  <x-toolbar title="Sumber Dana" :breadcrumbs="[['label' => 'Home', 'url' => url('/')], ['label' => 'Referensi'], ['label' => 'Sumber Dana']]" />

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
              <input type="text" data-kt-docs-table-filter="search" class="form-control form-control-solid w-250px ps-12"
                placeholder="Cari Sumber Dana">
            </div>
          </div>

          <div class="card-toolbar">
            <!-- Toolbar normal -->
            <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_sumberdana">
                <i class="ki-outline ki-plus fs-2"></i>
                Tambah Sumber Dana
              </button>
            </div>

            <!-- Toolbar saat ada data terpilih -->
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
            <table id="kt_sumberdana_table" class="table align-middle table-row-dashed fs-6 gy-5">
              <thead>
                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                  <th class="w-10px pe-2">
                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                      <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_sumberdana_table .form-check-input"
                        value="1" />
                    </div>
                  </th>
                  <th class="min-w-80px">Kode</th>
                  <th class="min-w-150px">Nama Dana</th>
                  <th class="min-w-250px">Sumber Dana</th>
                  <th class="min-w-100px">Set Inputan</th>
                  <th class="text-end min-w-70px">Aksi</th>
                </tr>
              </thead>
              <tbody class="fw-semibold text-gray-600">
                <!-- Data akan dimuat via Ajax -->
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  @include('referensi.sumber-dana.partials.modal-create')

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      var dt;

      // 1️⃣ Inisialisasi DataTable
      var initDatatable = function() {
        dt = $("#kt_sumberdana_table").DataTable({
          searchDelay: 500,
          processing: true,
          serverSide: true,
          scrollX: false,
          responseive: false,
          order: [
            [1, 'asc']
          ],
          stateSave: false,
          ajax: {
            url: "{{ route('referensi.sumber-dana.getData') }}",
            type: "GET",
          },
          columns: [{
              data: 'id',
              orderable: false,
              render: function(data) {
                return `
                  <div class="form-check form-check-sm form-check-custom form-check-solid">
                    <input class="form-check-input row-checkbox" type="checkbox" value="${data}" onclick="toggleToolbars()" />
                  </div>`;
              }
            },
            {
              data: 'kode_dana'
            },
            {
              data: 'nama_dana'
            },
            {
              data: 'sumber_dana'
            },
            {
              data: 'set_input',
              render: function(data) {
                const badgeClass = data === 'Ya' ? 'success' : 'secondary';
                const labelText = data === 'Ya' ? 'Aktif' : 'Tidak Aktif';
                return `<span class="badge badge-light-${badgeClass}">${labelText}</span>`;
              }
            },
            {
              data: 'actions',
              orderable: false,
              className: 'text-end',
              render: function(data, type, row) {
                return `
                  <div class="d-flex justify-content-end">
                    <a href="{{ url('referensi/sumber-dana') }}/${data}/edit" 
                       class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" 
                       title="Edit Sumber Dana">
                      <i class="ki-outline ki-pencil fs-2"></i>
                    </a>
                    <button type="button" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm delete-btn" 
                      data-id="${data}" data-name="${row.nama_dana}" title="Hapus Sumber Dana">
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
              targets: [1, 2, 3],
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
          },
          drawCallback: function() {
            handleDeleteRows();
          }
        });
      };

      // 2️⃣ Search Datatable
      var handleSearchDatatable = function() {
        const filterSearch = document.querySelector('[data-kt-docs-table-filter="search"]');
        filterSearch.addEventListener('keyup', function(e) {
          dt.search(e.target.value).draw();
        });
      };

      // 3️⃣ Delete row tunggal
      var handleDeleteRows = function() {
        const deleteButtons = document.querySelectorAll('.delete-btn');

        deleteButtons.forEach(button => {
          button.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');

            Swal.fire({
              title: 'Apakah Anda yakin?',
              html: `Data sumber dana <strong>"${name}"</strong> akan dihapus!`,
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
                $.ajax({
                  url: `{{ url('referensi/sumber-dana') }}/${id}`,
                  method: 'DELETE',
                  data: {
                    _token: '{{ csrf_token() }}'
                  },
                  success: function(response) {
                    if (response.success) {
                      toastr.success(response.message, 'BERHASIL');
                      dt.ajax.reload();
                    }
                  },
                  error: function(xhr) {
                    console.error(xhr.responseText);
                    toastr.error('Terjadi kesalahan saat menghapus data', 'GAGAL');
                  }
                });
              }
            });
          });
        });
      };

      // 4️⃣ Bulk delete (hapus banyak sekaligus)
      var initToggleToolbar = function() {
        const masterCheckbox = document.querySelector('#kt_sumberdana_table thead [type="checkbox"]');
        const deleteSelected = document.querySelector('#bulk_delete_btn');

        if (masterCheckbox) {
          masterCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            document.querySelectorAll('.row-checkbox').forEach(cb => {
              cb.checked = isChecked;
            });
            toggleToolbars();
          });
        }

        if (deleteSelected) {
          deleteSelected.addEventListener('click', function() {
            const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');

            if (checkedBoxes.length === 0) {
              Swal.fire({
                icon: 'info',
                title: 'Tidak ada data dipilih',
                text: 'Pilih minimal satu sumber dana untuk dihapus.',
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
              html: `Anda akan menghapus <strong>${checkedBoxes.length}</strong> data sumber dana terpilih!`,
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

                $.ajax({
                  url: '{{ route('referensi.sumber-dana.bulk-delete') }}',
                  method: 'POST',
                  data: {
                    _token: '{{ csrf_token() }}',
                    ids: ids
                  },
                  success: function(response) {
                    if (response.success) {
                      toastr.success(response.message, 'BERHASIL');
                      dt.ajax.reload();
                      const masterCheckbox = document.querySelector('#kt_sumberdana_table thead [type="checkbox"]');
                      if (masterCheckbox) masterCheckbox.checked = false;
                      toggleToolbars();
                    }
                  },
                  error: function(xhr) {
                    console.error(xhr.responseText);
                    toastr.error('Terjadi kesalahan saat menghapus data', 'GAGAL');
                  }
                });
              }
            });
          });
        }
      };

      // 5️⃣ Update tampilan toolbar
      var toggleToolbars = function() {
        const toolbarBase = document.querySelector('[data-kt-customer-table-toolbar="base"]');
        const toolbarSelected = document.querySelector('[data-kt-customer-table-toolbar="selected"]');
        const selectedCount = document.querySelector('[data-kt-customer-table-select="selected_count"]');
        const masterCheckbox = document.querySelector('#kt_sumberdana_table thead [type="checkbox"]');
        const rowCheckboxes = document.querySelectorAll('.row-checkbox');
        const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');

        const count = checkedBoxes.length;
        const total = rowCheckboxes.length;

        if (count > 0) {
          selectedCount.innerHTML = count;
          toolbarBase.classList.add('d-none');
          toolbarSelected.classList.remove('d-none');
        } else {
          toolbarBase.classList.remove('d-none');
          toolbarSelected.classList.add('d-none');
        }

        if (masterCheckbox && total > 0) {
          if (count === 0) {
            masterCheckbox.checked = false;
            masterCheckbox.indeterminate = false;
          } else if (count === total) {
            masterCheckbox.checked = true;
            masterCheckbox.indeterminate = false;
          } else {
            masterCheckbox.checked = false;
            masterCheckbox.indeterminate = true;
          }
        }
      };

      // 🔧 Jalankan semua inisialisasi
      initDatatable();
      handleSearchDatatable();
      initToggleToolbar();

      // 6️⃣ Tampilkan pesan session (Toastr)
      const sessionMessages = document.querySelectorAll('#session-messages div');
      sessionMessages.forEach(msg => {
        const type = msg.dataset.type;
        const message = msg.dataset.message;

        toastr.options = {
          "closeButton": true,
          "progressBar": true,
          "positionClass": "toastr-top-right",
          "timeOut": "5000"
        };

        if (type === 'error') toastr.error(message, "GAGAL");
        else if (type === 'success') toastr.success(message, "BERHASIL");
        else toastr.info(message);
      });

    });
  </script>
@endsection
