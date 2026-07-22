@extends('layouts.master')

@section('content')
  <x-toolbar title="Tahap Penjadwalan" :breadcrumbs="[['label' => 'Home', 'url' => url('/')], ['label' => 'RKPD'], ['label' => 'Tahap Penjadwalan']]" />

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
              <input type="text" id="kt_datatable_search_input" class="form-control form-control-solid w-250px ps-12"
                placeholder="Cari Tahap Penjadwalan">
            </div>
          </div>
          <div class="card-toolbar">
            <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_tahap_penjadwalan">Tambah
                Tahap</button>
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
                <span>Tidak ada data Tahap Penjadwalan yang ditemukan.</span>
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
                  <th class="min-w-100px">Nama Tahap</th>
                  <th class="min-w-100px">Aksi</th>
                </tr>
              </thead>
              <tbody class="fw-semibold text-gray-600">
                @foreach ($data as $item)
                  <tr>
                    <td>
                      <div class="form-check form-check-sm form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" value="{{ $item->id_tahap }}" />
                      </div>
                    </td>
                    <td>{{ $item->nama_tahap }}</td>
                    <td>
                      <div class="d-flex justify-content-end">
                        <a href="{{ route('tahap-penjadwalan.edit', $item->id_tahap) }}"
                          class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title="Edit Tahap Penjadwalan">
                          <i class="ki-outline ki-pencil fs-2"></i>
                        </a>
                        <form action="{{ route('tahap-penjadwalan.destroy', $item->id_tahap) }}" method="POST" class="d-inline delete-form">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm delete-btn"
                            title="Hapus Tahap Penjadwalan" data-name="{{ $item->nama_tahap }}">
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

  <!-- Modal Tambah Penjadwalan -->
  <div class="modal fade" id="kt_modal_add_tahap_penjadwalan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
      <div class="modal-content">
        <form class="form" action="{{ route('tahap-penjadwalan.store') }}" method="POST" id="kt_modal_add_tahap_penjadwalan_form">
          @csrf
          <div class="modal-header" id="kt_modal_add_tahap_penjadwalan_header">
            <h2 class="fw-bold">Tambah Tahap Penjadwalan</h2>
            <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
              <i class="ki-outline ki-cross fs-1"></i>
            </div>
          </div>

          <div class="modal-body py-10 px-lg-17">
            <div class="scroll-y me-n7 pe-7" id="kt_modal_add_tahap_penjadwalan_scroll">
              <div class="fv-row mb-7">
                <label class="required fs-6 fw-semibold mb-2">Nama Tahap</label>
                <input type="text" class="form-control form-control-solid @error('nama_tahap') is-invalid @enderror"
                  placeholder="Masukkan nama tahap" name="nama_tahap" value="{{ old('nama_tahap') }}" maxlength="255" required />
                @error('nama_tahap')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">Maksimal 255 karakter</div>
              </div>
            </div>
          </div>

          <div class="modal-footer flex-center">
            <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
            <button type="submit" id="kt_modal_add_tahap_penjadwalan_submit" class="btn btn-primary">
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
            targets: [1],
            className: 'fs-6'
          },
          {
            targets: [2],
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

      $('#kt_datatable_search_input').keyup(function() {
        table.search(this.value).draw();
      });

      // === SweetAlert2 Session Messages ===
      const sessionMessages = document.querySelectorAll('#session-messages div');
      sessionMessages.forEach(msg => {
        Swal.fire({
          icon: msg.dataset.type,
          title: msg.dataset.type === 'success' ? 'Berhasil' : 'Gagal',
          text: msg.dataset.message,
          confirmButtonText: 'OK',
          buttonsStyling: false,
          customClass: {
            confirmButton: "btn btn-primary"
          }
        });
      });

      // === Delete confirmation ===
      $(document).on('click', '.delete-btn', function(e) {
        e.preventDefault();
        const form = $(this).closest('form');
        const name = $(this).data('name');

        Swal.fire({
          title: 'Apakah Anda yakin?',
          html: `Data tahap penjadwalan <strong>"${name}"</strong> akan dihapus!`,
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
          if (result.isConfirmed) form.submit();
        });
      });

      // === Bulk selection & delete ===
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

      if (masterCheckbox) {
        masterCheckbox.addEventListener('change', function() {
          checkboxes.forEach(cb => cb.checked = this.checked);
          updateToolbar();
        });
      }

      checkboxes.forEach(cb => {
        cb.addEventListener('change', function() {
          updateToolbar();
          const checkedBoxes = document.querySelectorAll('#kt_datatable_column_rendering tbody input[type="checkbox"]:checked');
          if (masterCheckbox) {
            masterCheckbox.checked = checkedBoxes.length === checkboxes.length;
            masterCheckbox.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < checkboxes.length;
          }
        });
      });

      document.getElementById('bulk_delete_btn')?.addEventListener('click', function() {
        const checkedBoxes = document.querySelectorAll('#kt_datatable_column_rendering tbody input[type="checkbox"]:checked');
        if (checkedBoxes.length === 0) {
          Swal.fire({
            icon: 'info',
            title: 'Tidak ada data dipilih',
            text: 'Pilih minimal satu tahap penjadwalan untuk dihapus.',
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
          html: `Anda akan menghapus <strong>${checkedBoxes.length}</strong> data tahap penjadwalan terpilih!`,
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
            form.action = '{{ route('tahap-penjadwalan.bulk-delete') }}';

            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);

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

      // Auto show modal if validation errors exist
      @if ($errors->any() && old('_token'))
        $('#kt_modal_add_tahap_penjadwalan').modal('show');
      @endif
    });
  </script>
@endsection
