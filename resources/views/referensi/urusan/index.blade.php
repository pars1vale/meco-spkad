@extends('layouts.master')

@section('content')
  <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
      <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
          <h1 class="page-heading text-dark fw-bold fs-3 m-0">Urusan</h1>
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
            <li class="breadcrumb-item text-muted">Urusan</li>
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
              <input type="text" id="kt_datatable_search_input" class="form-control form-control-solid w-250px ps-12" placeholder="Cari Urusan">
            </div>
          </div>
        </div>

        <div class="card-body pt-0">
          @if ($data->isEmpty())
            <div class="alert alert-warning d-flex align-items-center p-5 rounded">
              <i class="ki-outline ki-information fs-2hx me-3 text-warning"></i>
              <div class="d-flex flex-column">
                <h4 class="mb-1 text-warning">Tidak ada data</h4>
                <span>Tidak ada data Urusan yang ditemukan.</span>
              </div>
            </div>
          @else
            <table id="kt_datatable_column_rendering" class="table table-striped table-row-bordered gy-5 gs-7">
              <thead>
                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                  <th class="min-w-100px">Kode</th>
                  <th class="min-w-300px">Nama Urusan</th>
                  <th class="min-w-100px">Aksi</th>
                </tr>
              </thead>
              <tbody class="fw-semibold text-gray-600">
                @foreach ($data as $item)
                  <tr>
                    <td class="fw-bold">{{ $item->kode_urusan }}</td>
                    <td>{{ $item->nama_urusan }}</td>
                    <td>
                      <div class="d-flex justify-content-end">
                        <a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title="Edit Urusan">
                          <i class="ki-outline ki-pencil fs-2"></i>
                        </a>
                        <form action="#" method="POST" class="d-inline">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm" title="Hapus Urusan">
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

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      var table = $('#kt_datatable_column_rendering').DataTable({
        responsive: true,
        searchDelay: 500,
        processing: true,
        serverSide: false,
        order: [
          [0, 'asc']
        ],
        columnDefs: [{
          targets: [0, 1],
          className: 'fs-6'
        }],
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
    });
  </script>
@endsection
