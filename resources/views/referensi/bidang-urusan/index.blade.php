@extends('layouts.master')
@section('content')
  <x-toolbar title="Bidang Urusan" :breadcrumbs="[['label' => 'Home', 'url' => url('/')], ['label' => 'Referensi'], ['label' => 'Bidang Urusan']]" />

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
                placeholder="Cari Bidang Urusan">
            </div>
          </div>
          <div class="card-toolbar">
            <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
              <div class="w-150px me-3"></div>
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_bidang_urusan">
                Tambah Bidang Urusan
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
          @if ($data->isEmpty())
            <div class="alert alert-warning d-flex align-items-center p-5 rounded">
              <i class="ki-outline ki-information fs-2hx me-3 text-warning"></i>
              <div class="d-flex flex-column">
                <h4 class="mb-1 text-warning">Tidak ada data</h4>
                <span>Tidak ada data Bidang Urusan yang ditemukan.</span>
              </div>
            </div>
          @else
            <table id="kt_bidang_urusan_table" class="table table-striped table-row-bordered gy-5 gs-7">
              <thead>
                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                  <th class="w-10px pe-2">
                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                      <input class="form-check-input" type="checkbox" data-kt-check="true"
                        data-kt-check-target="#kt_bidang_urusan_table .form-check-input" value="1" />
                    </div>
                  </th>
                  <th class="min-w-100px">Kode</th>
                  <th class="min-w-300px">Nama Bidang Urusan</th>
                  <th class="d-none">Urusan Group</th>
                  <th class="min-w-100px">Aksi</th>
                </tr>
              </thead>
              <tbody class="fw-semibold text-gray-600">
                @foreach ($data as $bidangUrusanList)
                  @foreach ($bidangUrusanList as $bidangUrusan)
                    <tr>
                      <td>
                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                          <input class="form-check-input" type="checkbox" value="{{ $bidangUrusan->id }}" />
                        </div>
                      </td>
                      <td class="fw-bold">{{ $bidangUrusan->kode_bidang_urusan }}</td>
                      <td>{{ $bidangUrusan->nama_bidang_urusan }}</td>
                      <td class="d-none">[URUSAN] {{ $bidangUrusan->urusan->kode_urusan }} {{ $bidangUrusan->urusan->nama_urusan }}</td>
                      <td>
                        <div class="d-flex justify-content-end">
                          <a href="{{ route('bidang-urusan.edit', $bidangUrusan->id) }}"
                            class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title="Edit Bidang Urusan">
                            <i class="ki-outline ki-pencil fs-2"></i>
                          </a>
                          <form action="{{ route('bidang-urusan.destroy', $bidangUrusan->id) }}" method="POST" class="d-inline delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm delete-btn"
                              title="Hapus Bidang Urusan" data-name="{{ $bidangUrusan->nama_bidang_urusan }}">
                              <i class="ki-outline ki-trash fs-2"></i>
                            </button>
                          </form>
                        </div>
                      </td>
                    </tr>
                  @endforeach
                @endforeach
              </tbody>
            </table>
          @endif
        </div>
      </div>
    </div>
  </div>

  @include('referensi.bidang-urusan.partials.modal-add', ['listUrusan' => $listUrusan])

  @include('referensi.bidang-urusan.partials.scripts-table')
  @include('referensi.bidang-urusan.partials.scripts-modal')

  <script>
    document.addEventListener("DOMContentLoaded", function() {
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
    });
  </script>
@endsection
