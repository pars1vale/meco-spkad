@extends('layouts.master')

@section('content')
  <x-toolbar title="Jadwal RKPD" :breadcrumbs="[['label' => 'Home', 'url' => url('/')], ['label' => 'RKPD'], ['label' => 'Jadwal RKPD']]" />

  <div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-fluid">

      <div id="session-messages" style="display:none;">
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
                placeholder="Cari Jadwal RKPD" />
            </div>
          </div>

          <div class="card-toolbar">
            <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahJadwal">
                Tambah Jadwal
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
                <span>Tidak ada data Jadwal RKPD ditemukan.</span>
              </div>
            </div>
          @else
            <table id="kt_jadwal_table" class="table table-striped align-middle table-row-dashed fs-6 gy-5">
              <thead>
                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                  <th class="w-10px pe-2">
                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                      <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_jadwal_table .form-check-input" />
                    </div>
                  </th>
                  <th class="min-w-150px">Tahap</th>
                  <th class="min-w-200px">Sub Tahap</th>
                  <th class="min-w-150px">Waktu Mulai</th>
                  <th class="min-w-150px">Waktu Selesai</th>
                  <th class="min-w-100px">Aksi</th>
                </tr>
              </thead>
              <tbody class="fw-semibold text-gray-600">
                @foreach ($data as $jadwal)
                  <tr>
                    <td>
                      <div class="form-check form-check-sm form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" value="{{ $jadwal->id_jadwal }}" />
                      </div>
                    </td>
                    <td>{{ $jadwal->subTahap->tahap->nama_tahap ?? '-' }}</td>
                    <td>{{ $jadwal->subTahap->nama_sub_tahap ?? '-' }}</td>
                    <td>{{ $jadwal->waktu_mulai ? \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('d M Y H:i') : '-' }}</td>
                    <td>{{ $jadwal->waktu_selesai ? \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('d M Y H:i') : '-' }}</td>
                    <td>
                      <div class="d-flex justify-content-end">
                        <a href="{{ route('rkpd.jadwal-rkpd.edit', $jadwal->id_jadwal) }}"
                          class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title="Edit Jadwal RKPD">
                          <i class="ki-outline ki-pencil fs-2"></i>
                        </a>

                        <form action="{{ route('rkpd.jadwal-rkpd.destroy', $jadwal->id_jadwal) }}" method="POST" class="d-inline delete-form">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm delete-btn"
                            data-name="{{ $jadwal->subTahap->nama_sub_tahap ?? 'Jadwal' }}" title="Hapus Jadwal">
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

  {{-- Modal Tambah Jadwal --}}
  <div class="modal fade" id="modalTambahJadwal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
      <div class="modal-content">
        <form class="form" action="{{ route('rkpd.jadwal-rkpd.store') }}" method="POST" id="formTambahJadwal">
          @csrf
          <div class="modal-header">
            <h2 class="fw-bold">Tambah Jadwal RKPD</h2>
            <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
              <i class="ki-outline ki-cross fs-1"></i>
            </div>
          </div>

          <div class="modal-body py-10 px-lg-17">
            <div class="scroll-y me-n7 pe-7">

              <div class="fv-row mb-7">
                <label class="required fs-6 fw-semibold mb-2">Sub Tahap</label>
                <select class="form-select form-select-solid" name="id_sub_tahap" required>
                  <option value="">Pilih Sub Tahap</option>
                  @foreach ($subTahap as $st)
                    <option value="{{ $st->id_sub_tahap }}">
                      {{ $st->tahap->nama_tahap ?? '-' }} — {{ $st->nama_sub_tahap }}
                    </option>
                  @endforeach
                </select>
              </div>

              <div class="fv-row mb-7">
                <label class="required fs-6 fw-semibold mb-2">Waktu Mulai</label>
                <input type="datetime-local" name="waktu_mulai" class="form-control form-control-solid" required />
              </div>

              <div class="fv-row mb-7">
                <label class="required fs-6 fw-semibold mb-2">Waktu Selesai</label>
                <input type="datetime-local" name="waktu_selesai" class="form-control form-control-solid" required />
              </div>

            </div>
          </div>

          <div class="modal-footer flex-center">
            <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
            <button type="submit" id="btnSimpanJadwal" class="btn btn-primary">
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
      const table = $('#kt_jadwal_table').DataTable({
        responsive: true,
        searchDelay: 500,
        columnDefs: [{
          targets: [0, 5],
          orderable: false
        }]
      });

      $('#kt_datatable_search_input').keyup(function() {
        table.search(this.value).draw();
      });

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

      $(document).on('click', '.delete-btn', function(e) {
        e.preventDefault();
        const form = $(this).closest('form');
        const name = $(this).data('name');
        Swal.fire({
          title: 'Apakah Anda yakin?',
          html: `Jadwal <strong>"${name}"</strong> akan dihapus!`,
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
    });
  </script>
@endsection
