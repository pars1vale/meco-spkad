@extends('layouts.master')
@section('content')
  <!--begin::Toolbar-->
  <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <!--begin::Toolbar container-->
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
      <!--begin::Toolbar wrapper-->
      <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
        <!--begin::Page title-->
        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
          <!--begin::Title-->
          <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">Bidang Urusan</h1>
          <!--end::Title-->
          <!--begin::Breadcrumb-->
          <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
            <!--begin::Item-->
            <li class="breadcrumb-item text-muted">
              <a href="{{ url('/home') }}" class="text-muted text-hover-primary">Home</a>
            </li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="breadcrumb-item">
              <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="breadcrumb-item text-muted">Referensi</li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="breadcrumb-item">
              <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="breadcrumb-item text-muted">Bidang Urusan</li>
            <!--end::Item-->
          </ul>
          <!--end::Breadcrumb-->
        </div>
        <!--end::Page title-->
      </div>
      <!--end::Toolbar wrapper-->
    </div>
    <!--end::Toolbar container-->
  </div>
  <!--end::Toolbar-->

  <!--begin::Content-->
  <div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-fluid">
      <!--begin::Card-->
      <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
          <!--begin::Card title-->
          <div class="card-title">
            <!--begin::Search-->
            <div class="d-flex align-items-center position-relative my-1">
              <i class="ki-outline ki-magnifier fs-3 position-absolute ms-4"></i>
              <input type="text" id="kt_datatable_search_input" class="form-control form-control-solid w-250px ps-12"
                placeholder="Cari Bidang Urusan">
            </div>
            <!--end::Search-->
          </div>
          <!--end::Card title-->
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
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
            <table id="kt_datatable_row_grouping" class="table align-middle table-row-dashed fs-6 gy-5">
              <thead>
                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                  <th class="min-w-100px">Kode</th>
                  <th class="min-w-300px">Nama Bidang Urusan</th>
                  <th class="d-none">Urusan Group</th> <!-- Hidden column for grouping -->
                  <th class="min-w-100px">Aksi</th>
                </tr>
              </thead>
              <tbody class="fw-semibold text-gray-600">
                @foreach ($data as $namaUrusan => $items)
                  @if ($items && $items->count())
                    @foreach ($items as $item)
                      @if (!empty($item->kode_bidang_urusan) && !empty($item->nama_bidang_urusan))
                        <tr>
                          <td class="fw-bold">{{ $item->kode_bidang_urusan }}</td>
                          <td>{{ $item->nama_bidang_urusan }}</td>
                          <td class="d-none">{{ $namaUrusan }}</td>
                          <td>
                            <div class="d-flex justify-content-end">
                              <a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title="Edit Bidang Urusan">
                                <i class="ki-outline ki-pencil fs-2"></i>
                              </a>
                              <form action="#" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm" title="Hapus Bidang Urusan">
                                  <i class="ki-outline ki-trash fs-2"></i>
                                </button>
                              </form>
                            </div>
                        </tr>
                      @endif
                    @endforeach
                  @endif
                @endforeach
              </tbody>
            </table>
          @endif
        </div>
        <!--end::Card body-->
      </div>
      <!--end::Card-->
    </div>
    <!--end::Content container-->
  </div>
  <!--end::Content-->
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      var groupColumn = 2;

      var table = $('#kt_datatable_row_grouping').DataTable({
        responsive: true,
        searchDelay: 500,
        processing: true,
        serverSide: false,
        order: [
          [groupColumn, 'asc'],
          [0, 'asc']
        ],
        columnDefs: [{
          targets: groupColumn,
          visible: false
        }, {
          targets: [0, 1],
          className: 'fs-6'
        }],
        dom: "<'row'<'col-sm-12'tr>>" + // Baris untuk tabel
          "<'row mt-4'" + // Baris untuk footer (dengan margin top)
          "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-start'li>" + // Length menu di kiri
          "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-end'p>" + // Pagination di kanan
          ">",
        language: {
          paginate: {
            previous: '<i class="ki-outline ki-arrow-left fs-4"></i>',
            next: '<i class="ki-outline ki-arrow-right fs-4"></i>'
          }
        },
        drawCallback: function(settings) {
          var api = this.api();
          var rows = api.rows({
            page: 'current'
          }).nodes();
          var last = null;

          api.column(groupColumn, {
            page: 'current'
          }).data().each(function(group, i) {
            if (last !== group) {
              $(rows).eq(i).before(
                '<tr class="group bg-light-primary fw-bolder">' +
                '<td colspan="2" class="fw-bold fs-5 px-4 py-3">' +
                group +
                '</td></tr>'
              );
              last = group;
            }
          });
        }
      });

      // Search functionality untuk input manual
      $('#kt_datatable_search_input').keyup(function() {
        table.search(this.value).draw();
      });

      // Order by the grouping when clicking on group header
      $('#kt_datatable_row_grouping tbody').on('click', 'tr.group', function() {
        var currentOrder = table.order()[0];
        if (currentOrder[0] === groupColumn && currentOrder[1] === 'asc') {
          table.order([groupColumn, 'desc']).draw();
        } else {
          table.order([groupColumn, 'asc']).draw();
        }
      });
    });
  </script>
@endsection
