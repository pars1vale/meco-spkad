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
                      <span class="badge badge-light-{{ $akun->pendapatan == 'Ya' ? 'success' : 'secondary' }}">
                        {{ ucfirst($akun->pendapatan) }}
                      </span>
                    </td>
                    <td>
                      <span class="badge badge-light-{{ $akun->belanja == 'Ya' ? 'success' : 'secondary' }}">
                        {{ ucfirst($akun->belanja) }}
                      </span>
                    </td>
                    <td>
                      <span class="badge badge-light-{{ $akun->pembiayaan == 'Ya' ? 'success' : 'secondary' }}">
                        {{ ucfirst($akun->pembiayaan) }}
                      </span>
                    </td>
                    <td>
                      <div class="d-flex justify-content-end">
                        <a href="{{ route('akun.edit', $akun->id) }}" class="btn btn-sm btn-light-primary me-2" title="Edit Akun">
                          <i class="ki-outline ki-pencil fs-2"></i>
                        </a>
                        <form action="{{ route('akun.destroy', $akun->id) }}" method="POST" class="d-inline delete-form">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-sm btn-light-danger delete-btn" title="Hapus Akun" data-name="{{ $akun->nama_akun }}">
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

  {{-- Include Modal Create --}}
  @include('referensi.akun.partials.modal-create')

  {{-- Include Scripts --}}
  @include('referensi.akun.partials.scripts-table')

  {{-- Session Messages Script --}}
  <script>
    document.addEventListener("DOMContentLoaded", function() {
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
@endsection
