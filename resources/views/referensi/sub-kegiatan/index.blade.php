@extends('layouts.master')
@section('content')
  <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
      <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
          <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">Subkegiatan</h1>
          <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
            <li class="breadcrumb-item text-muted">
              <a href="{{ url('/') }}" class="text-muted text-hover-primary">Home</a>
            </li>
            <li class="breadcrumb-item">
              <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">Referensi</li>
            <li class="breadcrumb-item">
              <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">Subkegiatan</li>
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
              <input type="text" id="kt_datatable_search_input" class="form-control form-control-solid w-250px ps-12" placeholder="Cari Subkegiatan">
            </div>
          </div>
        </div>

        <div class="card-body pt-0">
          @if ($data->isEmpty())
            <div class="alert alert-warning d-flex align-items-center p-5 rounded">
              <i class="ki-outline ki-information fs-2hx me-3 text-warning"></i>
              <div class="d-flex flex-column">
                <h4 class="mb-1 text-warning">Tidak ada data</h4>
                <span>Tidak ada data Subkegiatan yang ditemukan.</span>
              </div>
            </div>
          @else
            <table id="kt_subkegiatan_table" class="table align-middle table-row-dashed fs-6 gy-5">
              <thead>
                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                  <th class="min-w-100px">Kode</th>
                  <th class="min-w-300px">Nama Subkegiatan</th>
                  <th class="d-none">Urusan Group</th>
                  <th class="d-none">Bidang Group</th>
                  <th class="d-none">Program Group</th>
                  <th class="d-none">Kegiatan Group</th>
                </tr>
              </thead>
              <tbody class="fw-semibold text-gray-600">
                @foreach ($data as $urusanGroup)
                  @php
                    $urusan = $urusanGroup->first();
                    $bidangGrouped = $urusanGroup->groupBy('id_bidang_urusan');
                  @endphp

                  @foreach ($bidangGrouped as $bidangGroup)
                    @php
                      $bidang = $bidangGroup->first();
                      $programGrouped = $bidangGroup->groupBy('id_program');
                    @endphp

                    @foreach ($programGrouped as $programGroup)
                      @php
                        $program = $programGroup->first();
                        $kegiatanGrouped = $programGroup->groupBy('id_giat');
                      @endphp

                      @foreach ($kegiatanGrouped as $kegiatanGroup)
                        @php
                          $kegiatan = $kegiatanGroup->first();
                        @endphp

                        @foreach ($kegiatanGroup as $subkegiatan)
                          <tr>
                            <td class="fw-bold">{{ $subkegiatan->kode_sub_giat }}</td>
                            <td>{{ $subkegiatan->nama_sub_giat }}</td>
                            <td class="d-none">[URUSAN] {{ $urusan->nama_urusan }}</td>
                            <td class="d-none">[BIDANG URUSAN] {{ $bidang->nama_bidang_urusan }}</td>
                            <td class="d-none">[PROGRAM] {{ $program->nama_program }}</td>
                            <td class="d-none">[KEGIATAN] {{ $kegiatan->nama_giat }}</td>
                          </tr>
                        @endforeach
                      @endforeach
                    @endforeach
                  @endforeach
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
      var table = $('#kt_subkegiatan_table').DataTable({
        responsive: true,
        searchDelay: 500,
        processing: true,
        serverSide: false,
        order: [
          [2, 'asc'],
          [3, 'asc'],
          [4, 'asc'],
          [5, 'asc'],
          [0, 'asc']
        ],
        columnDefs: [{
            targets: [2, 3, 4, 5],
            visible: false
          },
          {
            targets: [0, 1],
            className: 'fs-6'
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

          api.column(2, {
            page: 'current'
          }).data().each(function(urusan, i) {
            var bidang = api.cell(rows[i], 3).data();
            var program = api.cell(rows[i], 4).data();
            var kegiatan = api.cell(rows[i], 5).data();

            if (lastUrusan !== urusan) {
              $(rows[i]).before(
                '<tr class="group bg-light-primary">' +
                '<td colspan="2" class="fw-bold fs-6 px-1 py-3">' + urusan + '</td></tr>'
              );
              lastUrusan = urusan;
              lastBidang = null;
              lastProgram = null;
              lastKegiatan = null;
            }

            if (lastBidang !== bidang) {
              $(rows[i]).before(
                '<tr class="group bg-secondary">' +
                '<td colspan="2" class="fw-bold fs-6 px-1 py-2">' + bidang + '</td></tr>'
              );
              lastBidang = bidang;
              lastProgram = null;
              lastKegiatan = null;
            }

            if (lastProgram !== program) {
              $(rows[i]).before(
                '<tr class="group bg-light">' +
                '<td colspan="2" class="fw-bold fs-6 px-1 py-2">' + program + '</td></tr>'
              );
              lastProgram = program;
              lastKegiatan = null;
            }

            if (lastKegiatan !== kegiatan) {
              $(rows[i]).before(
                '<tr class="group bg-light-warning">' +
                '<td colspan="2" class="fw-bold fs-6 px-1 py-2">' + kegiatan + '</td></tr>'
              );
              lastKegiatan = kegiatan;
            }
          });
        }
      });

      $('#kt_datatable_search_input').keyup(function() {
        table.search(this.value).draw();
      });

      $('#kt_subkegiatan_table').on('click', 'tr.group', function() {
        var colIdx;
        if ($(this).hasClass('bg-light-primary')) colIdx = 2;
        else if ($(this).hasClass('bg-secondary')) colIdx = 3;
        else if ($(this).hasClass('bg-light')) colIdx = 4;
        else colIdx = 5;

        var currentOrder = table.order()[0];
        if (currentOrder[0] === colIdx && currentOrder[1] === 'asc') {
          table.order([colIdx, 'desc']).draw();
        } else {
          table.order([colIdx, 'asc']).draw();
        }
      });
    });
  </script>
@endsection
