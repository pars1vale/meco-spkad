@extends('layouts.master')
@section('content')
  <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
      <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
          <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">Sumber Dana</h1>
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
            <li class="breadcrumb-item text-muted">Sumber Dana</li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-fluid">
      <div class="card">
        <div class="card-header border-0 pt-6">
          <div class="card-title">
            <div class="d-flex align-items-center position-relative my-1">
              <i class="ki-outline ki-magnifier fs-3 position-absolute ms-4"></i>
              <input type="text" id="kt_datatable_search_input" class="form-control form-control-solid w-250px ps-12" placeholder="Cari Sumber Dana">
            </div>
          </div>
          <div class="card-toolbar">
            <div class="d-flex justify-content-end" data-kt-sumberdana-table-toolbar="base">
              <button type="button" class="btn btn-light-primary me-3" id="kt_sumberdana_bulk_delete" style="display: none;">
                <i class="ki-outline ki-trash fs-2"></i>Hapus Terpilih
              </button>
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_sumberdana">
                <i class="ki-outline ki-plus fs-2"></i>Tambah Sumber Dana
              </button>
            </div>
          </div>
        </div>

        <div class="card-body pt-0">
          @if ($data->isEmpty())
            <div class="alert alert-warning d-flex align-items-center p-5 rounded">
              <i class="ki-outline ki-information fs-2hx me-3 text-warning"></i>
              <div class="d-flex flex-column">
                <h4 class="mb-1 text-warning">Tidak ada data</h4>
                <span>Tidak ada Sumber Dana yang ditemukan.</span>
              </div>
            </div>
          @else
            <table id="kt_sumberdana_table" class="table table-striped align-middle table-row-dashed fs-6 gy-5">
              <thead>
                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                  <th class="w-10px pe-2">
                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                      <input class="form-check-input" type="checkbox" id="kt_sumberdana_select_all" />
                    </div>
                  </th>
                  <th class="min-w-100px">Kode</th>
                  <th class="min-w-200px">Nama Dana</th>
                  <th class="min-w-300px">Sumber Dana</th>
                  <th class="min-w-150px">Set Inputan</th>
                  <th class="min-w-100px">Aksi</th>
                </tr>
              </thead>
              <tbody class="fw-semibold text-gray-600">
                @foreach ($data as $sumberdana)
                  <tr>
                    <td>
                      <div class="form-check form-check-sm form-check-custom form-check-solid">
                        <input class="form-check-input sumberdana-checkbox" type="checkbox" value="{{ $sumberdana->id }}" />
                      </div>
                    </td>
                    <td class="fw-bold">{{ $sumberdana->kode_dana }}</td>
                    <td>{{ $sumberdana->nama_dana }}</td>
                    <td>{{ $sumberdana->sumber_dana ?? '-' }}</td>
                    <td>
                      @if ($sumberdana->set_input == 'Ya')
                        <span class="badge badge-success">Aktif</span>
                      @else
                        <span class="badge badge-secondary">Tidak Aktif</span>
                      @endif
                    </td>
                    <td>
                      <div class="d-flex">
                        <a href="{{ route('sumber-dana.edit', $sumberdana->id) }}" class="btn btn-sm btn-light-primary me-2">
                          <i class="ki-outline ki-pencil fs-5"></i>
                        </a>
                        <button class="btn btn-sm btn-light-danger" onclick="deleteSumberDana({{ $sumberdana->id }})">
                          <i class="ki-outline ki-trash fs-5"></i>
                        </button>
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

  <!-- Add Modal -->
  <div class="modal fade" id="kt_modal_add_sumberdana" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="fw-bold">Tambah Sumber Dana</h2>
          <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
            <i class="ki-outline ki-cross fs-1"></i>
          </div>
        </div>
        <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
          <form id="kt_modal_add_sumberdana_form" action="{{ route('sumber-dana.store') }}" method="POST">
            @csrf
            <div class="d-flex flex-column scroll-y me-n7 pe-7">
              <div class="fv-row mb-7">
                <label class="required fw-semibold fs-6 mb-2">Kode Dana</label>
                <input type="text" name="kode_dana" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Masukkan Kode Dana" />
                <div class="invalid-feedback"></div>
              </div>
              <div class="fv-row mb-7">
                <label class="required fw-semibold fs-6 mb-2">Nama Dana</label>
                <input type="text" name="nama_dana" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Masukkan Nama Dana" />
                <div class="invalid-feedback"></div>
              </div>
              <div class="fv-row mb-7">
                <label class="fw-semibold fs-6 mb-2">Sumber Dana</label>
                <textarea name="sumber_dana" class="form-control form-control-solid" rows="3" placeholder="Masukkan Sumber Dana (opsional)"></textarea>
              </div>
            </div>
            <div class="text-center pt-10">
              <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary">
                <span class="indicator-label">Simpan</span>
                <span class="indicator-progress">Please wait...
                  <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>


  <script>
    document.addEventListener("DOMContentLoaded", function() {
      // Initialize DataTable
      var table = $('#kt_sumberdana_table').DataTable({
        responsive: true,
        searchDelay: 500,
        processing: true,
        serverSide: false,
        columnDefs: [{
          orderable: false,
          targets: [0, 5]
        }]
      });

      // Search functionality
      $('#kt_datatable_search_input').keyup(function() {
        table.search(this.value).draw();
      });

      // Master checkbox functionality
      $('#kt_sumberdana_select_all').on('change', function() {
        $('.sumberdana-checkbox').prop('checked', this.checked);
        toggleBulkDeleteButton();
      });

      // Individual checkbox functionality
      $(document).on('change', '.sumberdana-checkbox', function() {
        var allChecked = $('.sumberdana-checkbox:checked').length === $('.sumberdana-checkbox').length;
        $('#kt_sumberdana_select_all').prop('checked', allChecked);
        toggleBulkDeleteButton();
      });

      // Toggle bulk delete button
      function toggleBulkDeleteButton() {
        var checkedBoxes = $('.sumberdana-checkbox:checked').length;
        if (checkedBoxes > 0) {
          $('#kt_sumberdana_bulk_delete').show();
        } else {
          $('#kt_sumberdana_bulk_delete').hide();
        }
      }

      // Bulk delete functionality
      $('#kt_sumberdana_bulk_delete').on('click', function() {
        var selectedIds = [];
        $('.sumberdana-checkbox:checked').each(function() {
          selectedIds.push($(this).val());
        });

        if (selectedIds.length === 0) {
          Swal.fire({
            title: 'Peringatan!',
            text: 'Pilih data yang akan dihapus',
            icon: 'warning'
          });
          return;
        }

        Swal.fire({
          title: 'Konfirmasi Hapus',
          text: `Yakin ingin menghapus ${selectedIds.length} data terpilih?`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Ya, Hapus!',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: "{{ route('sumber-dana.bulk-delete') }}",
              method: 'POST',
              data: {
                _token: "{{ csrf_token() }}",
                ids: selectedIds
              },
              success: function(response) {
                if (response.success) {
                  Swal.fire({
                    title: 'Berhasil!',
                    text: response.message,
                    icon: 'success'
                  }).then(() => {
                    location.reload();
                  });
                }
              },
              error: function(xhr) {
                Swal.fire({
                  title: 'Error!',
                  text: 'Terjadi kesalahan saat menghapus data',
                  icon: 'error'
                });
              }
            });
          }
        });
      });

      // Form submission
      $('#kt_modal_add_sumberdana_form').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var submitButton = form.find('[type="submit"]');

        // Show loading state
        submitButton.attr('data-kt-indicator', 'on');
        submitButton.prop('disabled', true);

        // Clear previous errors
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').text('');

        $.ajax({
          url: form.attr('action'),
          method: form.attr('method'),
          data: form.serialize(),
          success: function(response) {
            if (response.success) {
              $('#kt_modal_add_sumberdana').modal('hide');
              form[0].reset();
              Swal.fire({
                title: 'Berhasil!',
                text: response.message,
                icon: 'success'
              }).then(() => {
                location.reload();
              });
            }
          },
          error: function(xhr) {
            if (xhr.status === 422) {
              var errors = xhr.responseJSON.errors;
              $.each(errors, function(field, messages) {
                var input = form.find('[name="' + field + '"]');
                input.addClass('is-invalid');
                input.siblings('.invalid-feedback').text(messages[0]);
              });
            } else {
              Swal.fire({
                title: 'Error!',
                text: 'Terjadi kesalahan saat menyimpan data',
                icon: 'error'
              });
            }
          },
          complete: function() {
            // Hide loading state
            submitButton.removeAttr('data-kt-indicator');
            submitButton.prop('disabled', false);
          }
        });
      });

      // Show session messages
      @if (session('success'))
        Swal.fire({
          title: 'Berhasil!',
          text: "{{ session('success') }}",
          icon: 'success'
        });
      @endif

      @if (session('error'))
        Swal.fire({
          title: 'Error!',
          text: "{{ session('error') }}",
          icon: 'error'
        });
      @endif
    });

    // Delete single item function
    function deleteSumberDana(id) {
      Swal.fire({
        title: 'Konfirmasi Hapus',
        text: 'Yakin ingin menghapus data ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: `/sumber-dana/${id}`,
            method: 'DELETE',
            data: {
              _token: "{{ csrf_token() }}"
            },
            success: function(response) {
              if (response.success) {
                Swal.fire({
                  title: 'Berhasil!',
                  text: response.message,
                  icon: 'success'
                }).then(() => {
                  location.reload();
                });
              }
            },
            error: function(xhr) {
              Swal.fire({
                title: 'Error!',
                text: 'Terjadi kesalahan saat menghapus data',
                icon: 'error'
              });
            }
          });
        }
      });
    }
  </script>
@endsection
