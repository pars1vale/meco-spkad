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
              <input type="text" id="kt_datatable_search_input" class="form-control form-control-solid w-250px ps-12" placeholder="Cari Akun">
            </div>
          </div>
          <div class="card-toolbar">
            <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
              <div class="w-150px me-3"></div>
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_akun">Tambah Akun</button>
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
                <span>Tidak ada data Akun yang ditemukan.</span>
              </div>
            </div>
          @else
            <table id="kt_akun_table" class="table table-striped align-middle table-row-dashed fs-6 gy-5">
              <thead>
                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                  <th class="w-10px pe-2">
                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                      <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_akun_table .form-check-input"
                        value="1" />
                    </div>
                  </th>
                  <th class="min-w-100px">Kode</th>
                  <th class="min-w-300px">Nama Akun</th>
                  <th class="min-w-130px">Pendapatan</th>
                  <th class="min-w-130px">Belanja</th>
                  <th class="min-w-130px">Pembiayaan</th>
                  <th class="min-w-200px">Keterangan</th>
                  <th class="min-w-100px">Aksi</th>
                </tr>
              </thead>
              <tbody class="fw-semibold text-gray-600">
                @foreach ($data as $akun)
                  <tr>
                    <td>
                      <div class="form-check form-check-sm form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" value="{{ $akun->id }}" />
                      </div>
                    </td>
                    <td class="fw-bold">{{ $akun->kode_akun }}</td>
                    <td>{{ $akun->nama_akun }}</td>
                    <td>
                      <span class="badge badge-light-{{ $akun->pendapatan == 'ya' ? 'success' : 'secondary' }}">
                        {{ ucfirst($akun->pendapatan) }}
                      </span>
                    </td>
                    <td>
                      <span class="badge badge-light-{{ $akun->belanja == 'ya' ? 'success' : 'secondary' }}">
                        {{ ucfirst($akun->belanja) }}
                      </span>
                    </td>
                    <td>
                      <span class="badge badge-light-{{ $akun->pembiayaan == 'ya' ? 'success' : 'secondary' }}">
                        {{ ucfirst($akun->pembiayaan) }}
                      </span>
                    </td>
                    <td>{{ $akun->keterangan_akun ?: '-' }}</td>
                    <td>
                      <div class="d-flex justify-content-end">
                        <a href="{{ route('akun.edit', $akun->id) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1"
                          title="Edit Akun">
                          <i class="ki-outline ki-pencil fs-2"></i>
                        </a>
                        <form action="{{ route('akun.destroy', $akun->id) }}" method="POST" class="d-inline delete-form">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm delete-btn" title="Hapus Akun"
                            data-name="{{ $akun->nama_akun }}">
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

  <!-- Modal Tambah Akun -->
  <div class="modal fade" id="kt_modal_add_akun" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
      <div class="modal-content">
        <form class="form" action="{{ route('akun.store') }}" method="POST" id="kt_modal_add_akun_form">
          @csrf
          <div class="modal-header" id="kt_modal_add_akun_header">
            <h2 class="fw-bold">Tambah Akun</h2>
            <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
              <i class="ki-outline ki-cross fs-1"></i>
            </div>
          </div>

          <div class="modal-body py-10 px-lg-17">
            <div class="scroll-y me-n7 pe-7" id="kt_modal_add_akun_scroll">

              <!-- Kode Akun -->
              <div class="fv-row mb-7">
                <label class="required fs-6 fw-semibold mb-2">Kode Akun</label>
                <input type="text" class="form-control form-control-solid @error('kode_akun') is-invalid @enderror"
                  placeholder="Masukkan kode akun" name="kode_akun" value="{{ old('kode_akun') }}" maxlength="255" required />
                @error('kode_akun')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">Kode akun harus unik</div>
              </div>

              <!-- Nama Akun -->
              <div class="fv-row mb-7">
                <label class="required fs-6 fw-semibold mb-2">Nama Akun</label>
                <textarea class="form-control form-control-solid @error('nama_akun') is-invalid @enderror" rows="3" placeholder="Masukkan nama akun"
                  name="nama_akun" required>{{ old('nama_akun') }}</textarea>
                @error('nama_akun')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Keterangan Akun -->
              <div class="fv-row mb-7">
                <label class="fs-6 fw-semibold mb-2">Keterangan Akun</label>
                <textarea class="form-control form-control-solid @error('keterangan_akun') is-invalid @enderror" rows="3"
                  placeholder="Masukkan keterangan akun (opsional)" name="keterangan_akun">{{ old('keterangan_akun') }}</textarea>
                @error('keterangan_akun')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="fv-row mb-7">
                <label class="required fs-6 fw-semibold mb-2">Tipe Akun</label>
                <div class="form-text mb-3">Pilih salah satu tipe akun</div>

                <div class="row">
                  <!-- Pendapatan Switch -->
                  <div class="col-md-4 mb-3">
                    <div class="form-check form-switch form-check-custom form-check-solid">
                      <input class="form-check-input akun-type-switch" type="checkbox" value="1" id="pendapatanSwitch" name="is_pendapatan"
                        {{ old('is_pendapatan') ? 'checked' : '' }} />
                      <label class="form-check-label" for="pendapatanSwitch">
                        Pendapatan
                      </label>
                    </div>
                  </div>

                  <!-- Belanja Switch -->
                  <div class="col-md-4 mb-3">
                    <div class="form-check form-switch form-check-custom form-check-solid">
                      <input class="form-check-input akun-type-switch" type="checkbox" value="1" id="belanjaSwitch" name="is_belanja"
                        {{ old('is_belanja') ? 'checked' : '' }} />
                      <label class="form-check-label" for="belanjaSwitch">
                        Belanja
                      </label>
                    </div>
                  </div>

                  <!-- Pembiayaan Switch -->
                  <div class="col-md-4 mb-3">
                    <div class="form-check form-switch form-check-custom form-check-solid">
                      <input class="form-check-input akun-type-switch" type="checkbox" value="1" id="pembiayaanSwitch" name="is_pembiayaan"
                        {{ old('is_pembiayaan') ? 'checked' : '' }} />
                      <label class="form-check-label" for="pembiayaanSwitch">
                        Pembiayaan
                      </label>
                    </div>
                  </div>
                </div>

                <div class="invalid-feedback d-none" id="tipe-akun-error">
                  Anda harus memilih salah satu tipe akun
                </div>
                @error('tipe_akun')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>

            </div>
          </div>

          <div class="modal-footer flex-center">
            <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
            <button type="submit" id="kt_modal_add_akun_submit" class="btn btn-primary">
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
      var table = $('#kt_akun_table').DataTable({
        responsive: true,
        searchDelay: 500,
        processing: true,
        serverSide: false,
        columnDefs: [{
            targets: [0],
            orderable: false,
            className: 'text-center'
          },
          {
            targets: [7],
            orderable: false,
            className: 'text-end'
          }
        ]
      });

      $('#kt_datatable_search_input').keyup(function() {
        table.search(this.value).draw();
      });

      // === Switch logic untuk modal tambah ===
      const akunTypeSwitches = document.querySelectorAll('.akun-type-switch');
      akunTypeSwitches.forEach(switchEl => {
        switchEl.addEventListener('change', function() {
          if (this.checked) {
            // Matikan switch lainnya
            akunTypeSwitches.forEach(otherSwitch => {
              if (otherSwitch !== this) {
                otherSwitch.checked = false;
              }
            });
          }
          // Reset error message
          document.getElementById('tipe-akun-error').classList.add('d-none');
        });
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
            form.submit();
          }
        });
      });

      // === Bulk selection ===
      const checkboxes = document.querySelectorAll('#kt_akun_table tbody input[type="checkbox"]');
      const masterCheckbox = document.querySelector('#kt_akun_table thead input[type="checkbox"]');
      const selectedToolbar = document.querySelector('[data-kt-customer-table-toolbar="selected"]');
      const baseToolbar = document.querySelector('[data-kt-customer-table-toolbar="base"]');
      const selectedCount = document.querySelector('[data-kt-customer-table-select="selected_count"]');

      function updateToolbar() {
        const checkedBoxes = document.querySelectorAll('#kt_akun_table tbody input[type="checkbox"]:checked');

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
          const checkedBoxes = document.querySelectorAll('#kt_akun_table tbody input[type="checkbox"]:checked');
          if (masterCheckbox) {
            masterCheckbox.checked = checkedBoxes.length === checkboxes.length;
            masterCheckbox.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < checkboxes.length;
          }
        });
      });

      // === Bulk delete confirmation pakai SweetAlert2 ===
      document.getElementById('bulk_delete_btn')?.addEventListener('click', function() {
        const checkedBoxes = document.querySelectorAll('#kt_akun_table tbody input[type="checkbox"]:checked');

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
            form.action = '{{ route('akun.bulk-delete') }}';

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
      const addForm = document.getElementById('kt_modal_add_akun_form');
      const addSubmitButton = document.getElementById('kt_modal_add_akun_submit');

      if (addForm && addSubmitButton) {
        addForm.addEventListener('submit', function(e) {
          const kodeAkun = addForm.querySelector('input[name="kode_akun"]').value.trim();
          const namaAkun = addForm.querySelector('textarea[name="nama_akun"]').value.trim();

          // Check if at least one switch is selected
          const pendapatanChecked = addForm.querySelector('#pendapatanSwitch').checked;
          const belanjaChecked = addForm.querySelector('#belanjaSwitch').checked;
          const pembiayaanChecked = addForm.querySelector('#pembiayaanSwitch').checked;

          const hasTypeSelected = pendapatanChecked || belanjaChecked || pembiayaanChecked;

          if (!kodeAkun || !namaAkun || !hasTypeSelected) {
            e.preventDefault();

            if (!hasTypeSelected) {
              document.getElementById('tipe-akun-error').classList.remove('d-none');
            }

            Swal.fire({
              icon: 'error',
              title: 'Validasi gagal',
              text: !hasTypeSelected ? 'Anda harus memilih salah satu tipe akun!' : 'Semua field wajib diisi!',
              confirmButtonText: 'OK',
              buttonsStyling: false,
              customClass: {
                confirmButton: "btn btn-primary"
              }
            });
            return;
          }

          addSubmitButton.setAttribute('data-kt-indicator', 'on');
          addSubmitButton.disabled = true;
        });
      }

      // === Auto show modal if validation errors exist ===
      @if ($errors->any() && old('_token'))
        $('#kt_modal_add_akun').modal('show');
      @endif
    });
  </script>
@endsection
