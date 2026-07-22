@extends('layouts.master')
@section('content')
  <x-toolbar title=" Sub Kegiatan" :breadcrumbs="[['label' => 'Home', 'url' => url('/')], ['label' => 'Referensi'], ['label' => 'Sub Kegiatan']]" />


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
                placeholder="Cari Sub Kegiatan">
            </div>
          </div>
          <div class="card-toolbar">
            <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_sub_kegiatan">
                <i class="ki-outline ki-plus fs-2"></i>
                Tambah Sub Kegiatan
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
          <!--begin::Datatable-->
          <table id="kt_sub_kegiatan_table" class="table align-middle table-row-dashed fs-6 gy-5">
            <thead>
              <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                <th class="w-10px pe-2">
                  <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                    <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_sub_kegiatan_table .form-check-input"
                      value="1" />
                  </div>
                </th>
                <th class="min-w-100px">Kode</th>
                <th class="min-w-300px">Nama Sub Kegiatan</th>
                <th class="d-none">Urusan Group</th>
                <th class="d-none">Bidang Group</th>
                <th class="d-none">Program Group</th>
                <th class="d-none">Kegiatan Group</th>
                <th class="text-end min-w-100px">Aksi</th>
              </tr>
            </thead>
            <tbody class="fw-semibold text-gray-600">
              <!-- Data akan dimuat via Ajax -->
            </tbody>
          </table>
          <!--end::Datatable-->
        </div>
      </div>
    </div>
  </div>

  @include('referensi.sub-kegiatan.partials.modal-add')

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      var dt;

      // 1. Inisialisasi DataTable dengan Server-Side Processing
      var initDatatable = function() {
        dt = $("#kt_sub_kegiatan_table").DataTable({
          searchDelay: 500,
          processing: true,
          serverSide: true,
          order: [
            [3, 'asc'],
            [4, 'asc'],
            [5, 'asc'],
            [6, 'asc'],
            [1, 'asc']
          ], // Sort by urusan, bidang, program, kegiatan, then kode
          stateSave: false,
          ajax: {
            url: "{{ route('referensi.sub-kegiatan.getData') }}",
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
              data: 'kode_sub_kegiatan'
            },
            {
              data: 'nama_sub_kegiatan'
            },
            {
              data: 'urusan_group',
              visible: false // Hidden column untuk grouping urusan
            },
            {
              data: 'bidang_group',
              visible: false // Hidden column untuk grouping bidang urusan
            },
            {
              data: 'program_group',
              visible: false // Hidden column untuk grouping program
            },
            {
              data: 'kegiatan_group',
              visible: false // Hidden column untuk grouping kegiatan
            },
            {
              data: 'actions',
              orderable: false,
              className: 'text-end',
              render: function(data, type, row) {
                return `
                  <div class="d-flex justify-content-end">
                    <a href="{{ url('referensi/sub-kegiatan') }}/${data}/edit" 
                       class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" 
                       title="Edit Sub Kegiatan">
                      <i class="ki-outline ki-pencil fs-2"></i>
                    </a>
                    <button type="button" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm delete-btn" 
                      data-id="${data}" data-name="${row.nama_sub_kegiatan}" title="Hapus Sub Kegiatan">
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
            },
            {
              targets: [3, 4, 5, 6],
              visible: false
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
          drawCallback: function(settings) {
            var api = this.api();
            var rows = api.rows({
              page: 'current'
            }).nodes();
            var lastUrusan = null;
            var lastBidang = null;
            var lastProgram = null;
            var lastKegiatan = null;

            // Group by 4 levels: urusan (col 3), bidang urusan (col 4), program (col 5), kegiatan (col 6)
            api.column(3, {
              page: 'current'
            }).data().each(function(urusan, i) {
              var bidang = api.cell(rows[i], 4).data();
              var program = api.cell(rows[i], 5).data();
              var kegiatan = api.cell(rows[i], 6).data();

              // Level 1: Urusan Group
              if (lastUrusan !== urusan) {
                $(rows).eq(i).before(
                  '<tr class="group bg-light-primary">' +
                  '<td colspan="8" class="fw-bold fs-5 px-4 py-3">' +
                  urusan +
                  '</td></tr>'
                );
                lastUrusan = urusan;
                lastBidang = null;
                lastProgram = null;
                lastKegiatan = null;
              }

              // Level 2: Bidang Urusan Group
              if (lastBidang !== bidang) {
                $(rows).eq(i).before(
                  '<tr class="group bg-secondary">' +
                  '<td colspan="8" class="fw-bold fs-6 px-4 py-3">' +
                  bidang +
                  '</td></tr>'
                );
                lastBidang = bidang;
                lastProgram = null;
                lastKegiatan = null;
              }

              // Level 3: Program Group
              if (lastProgram !== program) {
                $(rows).eq(i).before(
                  '<tr class="group bg-light">' +
                  '<td colspan="8" class="fw-bold fs-6 px-4 py-3">' +
                  program +
                  '</td></tr>'
                );
                lastProgram = program;
                lastKegiatan = null;
              }

              // Level 4: Kegiatan Group (UNIQUE untuk Sub-Kegiatan!)
              if (lastKegiatan !== kegiatan) {
                $(rows).eq(i).before(
                  '<tr class="group bg-light-warning">' +
                  '<td colspan="8" class="fw-bold fs-6 px-4 py-3">' +
                  kegiatan +
                  '</td></tr>'
                );
                lastKegiatan = kegiatan;
              }
            });

            // Re-init handlers after draw
            initToggleToolbar();
            handleDeleteRows();
            handleGroupClick();
          }
        });
      };

      // 2. Search Datatable
      var handleSearchDatatable = function() {
        const filterSearch = document.querySelector('[data-kt-docs-table-filter="search"]');
        filterSearch.addEventListener('keyup', function(e) {
          dt.search(e.target.value).draw();
        });
      };

      // 3. Group click untuk sorting
      var handleGroupClick = function() {
        $('#kt_sub_kegiatan_table').off('click', 'tr.group').on('click', 'tr.group', function() {
          var colIdx;
          if ($(this).hasClass('bg-light-primary')) colIdx = 3; // Urusan
          else if ($(this).hasClass('bg-secondary')) colIdx = 4; // Bidang Urusan
          else if ($(this).hasClass('bg-light')) colIdx = 5; // Program
          else colIdx = 6; // Kegiatan

          var currentOrder = dt.order()[0];

          if (currentOrder[0] === colIdx && currentOrder[1] === 'asc') {
            dt.order([colIdx, 'desc']).draw();
          } else {
            dt.order([colIdx, 'asc']).draw();
          }
        });
      };

      // 4. Delete rows handler
      var handleDeleteRows = function() {
        const deleteButtons = document.querySelectorAll('.delete-btn');

        deleteButtons.forEach(button => {
          button.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');

            Swal.fire({
              title: 'Apakah Anda yakin?',
              html: `Data sub kegiatan <strong>"${name}"</strong> akan dihapus!`,
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
                form.action = `{{ url('referensi/sub-kegiatan') }}/${id}`;

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

      // 5. Init toggle toolbar
      var initToggleToolbar = function() {
        const container = document.querySelector('#kt_sub_kegiatan_table');
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
                text: 'Pilih minimal satu sub kegiatan untuk dihapus.',
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
              html: `Anda akan menghapus <strong>${checkedBoxes.length}</strong> data sub kegiatan terpilih!`,
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
                form.action = '{{ route('referensi.sub-kegiatan.bulk-delete') }}';

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

      // 6. Toggle toolbars
      var toggleToolbars = function() {
        const container = document.querySelector('#kt_sub_kegiatan_table');
        const toolbarBase = document.querySelector('[data-kt-customer-table-toolbar="base"]');
        const toolbarSelected = document.querySelector('[data-kt-customer-table-toolbar="selected"]');
        const selectedCount = document.querySelector('[data-kt-customer-table-select="selected_count"]');
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

      // Session messages with Toastr
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
    });
  </script>

  {{-- Include modal scripts --}}
  @include('referensi.sub-kegiatan.partials.scripts-modal')
@endsection
