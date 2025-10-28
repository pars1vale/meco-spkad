@extends('layouts.master')

@section('content')
  <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
      <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
          <h1 class="page-heading text-dark fw-bold fs-3 m-0">Perangkat Daerah</h1>
          <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
            <li class="breadcrumb-item text-muted">
              <a href="{{ url('/home') }}" class="text-muted text-hover-primary">Home</a>
            </li>
            <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
            <li class="breadcrumb-item text-muted">Pengaturan</li>
            <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
            <li class="breadcrumb-item text-muted">Profil</li>
            <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
            <li class="breadcrumb-item text-muted">Perangkat Daerah</li>
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
              <input type="text" id="kt_datatable_search_input"
                class="form-control form-control-solid w-250px ps-12" placeholder="Cari Perangkat Daerah">
            </div>
          </div>

          <div class="card-toolbar">
            <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
              <!-- Dropdown Tambah -->
              <div class="btn-group">
                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown"
                  aria-expanded="false">
                  Tambah
                </button>
                <ul class="dropdown-menu">
                  <li>
                    <!-- Tambah SKPD -->
                    <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#kt_modal_add_pd">
                      Tambah SKPD
                    </a>
                  </li>
                  <li>
                    <!-- Tambah Unit SKPD -->
                    <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#kt_modal_add_unit_skpd">
                      Tambah Unit SKPD
                    </a>
                  </li>
                </ul>
              </div>
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
                <span>Tidak ada data Perangkat Daerah yang ditemukan.</span>
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
                  <th class="min-w-100px">Kode SKPD</th>
                  <th class="min-w-300px">Nama SKPD</th>
                  <th class="min-w-300px">Status</th>
                  <th class="min-w-300px">Posisi</th>
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
                    <td class="fw-bold">{{ $item->kode_skpd }}</td>
                    <td>{{ $item->nama_skpd }}</td>
                    <td>{{ $item->status }}</td>
                    <td>{{ $item->posisi }}</td>
                    <td>
                      <div class="d-flex justify-content-end">
                        <a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1"
                          title="Edit"><i class="ki-outline ki-pencil fs-2"></i></a>
                        <form method="POST" class="d-inline delete-form"
                          action="{{-- {{ route('perangkat-daerah.destroy', $item->id ?? '#') }} --}}">
                          @csrf
                          @method('DELETE')
                          <button type="submit"
                            class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm delete-btn"
                            title="Hapus" data-name="{{ $item->nama_skpd }}">
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

  <!-- Modal Tambah SKPD -->
  <div class="modal fade" id="kt_modal_add_pd" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
      <div class="modal-content">
        <form class="form" action="{{ route('perangkat-daerah.store') }}" method="POST" id="kt_modal_add_pd_form">
          @csrf
          <div class="modal-header">
            <h2 class="fw-bold">Tambah SKPD</h2>
            <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
              <i class="ki-outline ki-cross fs-1"></i>
            </div>
          </div>

          <div class="modal-body py-10 px-lg-17">
            <div class="scroll-y me-n7 pe-7">

              <!-- Bidur 1 -->
              <div class="fv-row mb-7">
                <label class="required fs-6 fw-semibold mb-2">Bidang Urusan 1</label>
                <select class="form-select form-select-solid" name="bidur1" required>
                  <option value="">Pilih Bidang Urusan 1</option>
                  @foreach ($data_bidur as $bidur)
                    <option value="{{ $bidur->id }}">{{ $bidur->kode_bidang_urusan }} - {{ $bidur->nama_bidang_urusan }}</option>
                  @endforeach
                </select>
              </div>

              <!-- Bidur 2 -->
              <div class="fv-row mb-7">
                <label class="fs-6 fw-semibold mb-2">Bidang Urusan 2</label>
                <select class="form-select form-select-solid" name="bidur2">
                  <option value="">Pilih Bidang Urusan 2</option>
                  @foreach ($data_bidur as $bidur)
                    <option value="{{ $bidur->id }}">{{ $bidur->kode_bidang_urusan }} - {{ $bidur->nama_bidang_urusan }}</option>
                  @endforeach
                </select>
              </div>

              <!-- Bidur 3 -->
              <div class="fv-row mb-7">
                <label class="fs-6 fw-semibold mb-2">Bidang Urusan 3</label>
                <select class="form-select form-select-solid" name="bidur3">
                  <option value="">Pilih Bidang Urusan 3</option>
                  @foreach ($data_bidur as $bidur)
                    <option value="{{ $bidur->id }}">{{ $bidur->kode_bidang_urusan }} - {{ $bidur->nama_bidang_urusan }}</option>
                  @endforeach
                </select>
              </div>

              <!-- Kode SKPD -->
              <div class="fv-row mb-7">
                <label class="required fs-6 fw-semibold mb-2">Kode SKPD</label>
                <input type="text" class="form-control form-control-solid @error('kode_skpd_1') is-invalid @enderror"
                  placeholder="Masukkan Kode SKPD" name="kode_skpd_1" value="{{ old('kode_skpd_1') }}" maxlength="255"
                  required />
                @error('kode_skpd_1')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text text-muted">
                  Urutan Kode ditulis 2 digit, Contoh: <strong>01 s.d 99</strong>
                </div>
              </div>

              <!-- Nama SKPD -->
              <div class="fv-row mb-7">
                <label class="required fs-6 fw-semibold mb-2">Nama SKPD</label>
                <input type="text" class="form-control form-control-solid @error('nama_skpd') is-invalid @enderror"
                  placeholder="Masukkan Nama SKPD" name="nama_skpd" value="{{ old('nama_skpd') }}" maxlength="255"
                  required />
                @error('nama_skpd')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- NIP Kepala -->
              <div class="fv-row mb-7">
                <label class="required fs-6 fw-semibold mb-2">NIP Kepala</label>
                <input type="text" class="form-control form-control-solid @error('nipkepala') is-invalid @enderror"
                  placeholder="Masukkan NIP Kepala" name="nipkepala" value="{{ old('nipkepala') }}" maxlength="255"
                  required />
                @error('nipkepala')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Nama Kepala -->
              <div class="fv-row mb-7">
                <label class="required fs-6 fw-semibold mb-2">Nama Kepala</label>
                <input type="text" class="form-control form-control-solid @error('namakepala') is-invalid @enderror"
                  placeholder="Masukkan Nama Kepala" name="namakepala" value="{{ old('namakepala') }}" maxlength="255"
                  required />
                @error('namakepala')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Pangkat Kepala -->
              <div class="fv-row mb-7">
                <label class="fs-6 fw-semibold mb-2">Pangkat Kepala</label>
                <select class="form-select form-select-solid" name="pangkatkepala">
                  <option value="">Pilih Pangkat</option>
                  @foreach ($pangkat as $p)
                    <option value="{{ $p->id }}">{{ $p->nama }}</option>
                  @endforeach
                </select>
              </div>

              <!-- Status Kepala -->
              <div class="fv-row mb-7">
                <label class="fs-6 fw-semibold mb-2">Status Kepala</label>
                <select class="form-select form-select-solid" name="statuskepala">
                  <option value="">Pilih Status</option>
                  <option value="PA">Pengguna Anggaran</option>
                  <option value="KPA">Kuasa Pengguna Anggaran</option>
                  <option value="PLT">Pelaksana Tugas</option>
                  <option value="PLH">Pelaksana Harian</option>
                </select>
              </div>

              <!-- Pengaturan Akses -->
              <div class="fv-row mb-7">
                <label class="fs-6 fw-semibold mb-2 d-block">Pengaturan Akses</label>
                <div class="form-check form-switch form-check-custom form-check-solid d-flex align-items-center">
                  <input class="form-check-input akun-type-switch me-3" type="checkbox" value="1"
                    id="pendapatanSwitch" name="ispendapatan" />
                  <label class="form-check-label fs-6 fw-semibold" for="pendapatanSwitch">
                    Input Semua Akun Pendapatan Daerah
                  </label>
                </div>
                <div class="form-text text-muted">
                  Catatan: <strong>Apabila SKPD dapat menginput semua akun/rekening pendapatan daerah</strong>
                </div>
              </div>

            </div>
          </div>

          <div class="modal-footer flex-center">
            <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
            <button type="submit" id="kt_modal_add_pd_submit" class="btn btn-primary">
              <span class="indicator-label">Simpan</span>
              <span class="indicator-progress" style="display:none;">Menyimpan...
                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
              </span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- ==================== MODAL TAMBAH UNIT SKPD ==================== --}}
  <div class="modal fade" id="kt_modal_add_unit_skpd" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
      <div class="modal-content">
        <form class="form" action="{{ route('unit-skpd.store') }}" method="POST" id="kt_modal_add_unit_skpd_form">
          @csrf
          <div class="modal-header">
            <h2 class="fw-bold">Tambah Unit SKPD</h2>
            <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
              <i class="ki-outline ki-cross fs-1"></i>
            </div>
          </div>

          <div class="modal-body py-10 px-lg-17">
            <div class="scroll-y me-n7 pe-7">

              <div class="fv-row mb-7">
                <label class="required fs-6 fw-semibold mb-2">Pilih SKPD Induk</label>
                <select class="form-select form-select-solid" name="skpd_id" required>
                  <option value="">Pilih SKPD Induk</option>
                  @foreach ($data as $skpd)
                    <option value="{{ $skpd->id }}">{{ $skpd->kode_skpd }} - {{ $skpd->nama_skpd }}</option>
                  @endforeach
                </select>
                <div class="form-text text-muted">Pilih SKPD induk dari unit ini</div>
              </div>

              <div class="fv-row mb-7">
                <label class="required fs-6 fw-semibold mb-2">Kode Unit</label>
                <input type="text" class="form-control form-control-solid" placeholder="Masukkan Kode Unit"
                  name="kode_unit" maxlength="10" required />
                <div class="form-text text-muted">Urutan kode di tulis dalam 4 digit, Contoh : <strong>0001 s.d 9999</strong></div>
              </div>

              <div class="fv-row mb-7">
                <label class="required fs-6 fw-semibold mb-2">Nama Unit SKPD</label>
                <input type="text" class="form-control form-control-solid" placeholder="Masukkan Nama Unit SKPD"
                  name="nama_unit" maxlength="255" required />
              </div>

              <div class="fv-row mb-7">
                <label class="fs-6 fw-semibold mb-2">NIP Kepala Unit</label>
                <input type="text" class="form-control form-control-solid" placeholder="Masukkan NIP Kepala Unit"
                  name="nip_kepala_unit" />
              </div>

              <div class="fv-row mb-7">
                <label class="fs-6 fw-semibold mb-2">Nama Kepala Unit</label>
                <input type="text" class="form-control form-control-solid" placeholder="Masukkan Nama Kepala Unit"
                  name="nama_kepala_unit" />
              </div>

              <div class="fv-row mb-7">
                <label class="fs-6 fw-semibold mb-2">Pangkat Kepala Unit</label>
                <select class="form-select form-select-solid" name="pangkat_kepala_unit">
                  <option value="">Pilih Pangkat</option>
                  @foreach ($pangkat as $p)
                    <option value="{{ $p->id }}">{{ $p->nama }}</option>
                  @endforeach
                </select>
              </div>

              <div class="fv-row mb-7">
                <label class="fs-6 fw-semibold mb-2">Status Kepala Unit</label>
                <select class="form-select form-select-solid" name="status_kepala_unit">
                  <option value="">Pilih Status</option>
                  <option value="PA">Pengguna Anggaran</option>
                  <option value="KPA">Kuasa Pengguna Anggaran</option>
                  <option value="PLT">Pelaksana Tugas</option>
                  <option value="PLH">Pelaksana Harian</option>
                </select>
              </div>

            </div>
          </div>

          <div class="modal-footer flex-center">
            <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
            <button type="submit" id="kt_modal_add_unit_skpd_submit" class="btn btn-primary">
              <span class="indicator-label">Simpan</span>
              <span class="indicator-progress" style="display:none;">
                Menyimpan...
                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
              </span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <style>
    #kt_modal_add_pd .modal-body {
      max-height: 70vh;
      overflow-y: auto;
      padding-right: 1rem;
    }

    #kt_modal_add_pd .scroll-y {
      padding-bottom: 3rem;
    }
  </style>

  {{-- jQuery --}}
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

  <script>
  $(function() {
    const table = $('#kt_datatable_column_rendering').DataTable({
      searching: true,
      ordering: true,
      paging: true,
      language: {
        search: "Cari SKPD:",
        zeroRecords: "Data tidak ditemukan",
        info: "Menampilkan _START_–_END_ dari _TOTAL_ data"
      }
    });

    $('#kt_datatable_search_input').on('keyup', function() {
      table.search(this.value).draw();
    });
  });
</script>


  <script>
    $(document).ready(function() {
      const $modal = $('#kt_modal_add_pd');
      const $form = $('#kt_modal_add_pd_form');
      const $submitBtn = $('#kt_modal_add_pd_submit');

      $modal.on('shown.bs.modal', function() {
        $submitBtn.prop('disabled', false);
        $submitBtn.find('.indicator-progress').hide();
        $submitBtn.find('.indicator-label').show();
      });

      $modal.on('hidden.bs.modal', function() {
        $form[0].reset();
        $form.find('.is-invalid').removeClass('is-invalid');
      });

      $form.on('submit', function() {
        $submitBtn.prop('disabled', true);
        $submitBtn.find('.indicator-label').hide();
        $submitBtn.find('.indicator-progress').show();
      });
    });
  </script>
@endsection
