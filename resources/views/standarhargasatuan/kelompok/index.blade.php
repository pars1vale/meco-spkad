@extends('layouts.master')
@section('content')
  <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
      <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
          <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">Kelompok Satuan Harga</h1>
          <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
            <li class="breadcrumb-item text-muted">
              <a href="{{ url('/home') }}" class="text-muted text-hover-primary">Home</a>
            </li>
            <li class="breadcrumb-item">
              <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">Standar Harga</li>
            <li class="breadcrumb-item">
              <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">Kelompok Satuan Harga</li>
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
              <input type="text" id="kt_datatable_search_input" class="form-control form-control-solid w-250px ps-12" placeholder="Cari Kelompok">
            </div>
          </div>
          <div class="card-toolbar">
            <div class="d-flex justify-content-end gap-2" data-kt-customer-table-toolbar="base">
              <!-- Filter Tipe -->
              <select class="form-select form-select-solid w-150px" id="filter_tipe">
                <option value="">Semua Tipe</option>
                <option value="SSH">SSH</option>
                <option value="HSPK">HSPK</option>
                <option value="ASB">ASB</option>
                <option value="SBU">SBU</option>
              </select>

              <!-- Filter Tahun -->
              <select class="form-select form-select-solid w-150px" id="filter_tahun">
                <option value="">Semua Tahun</option>
                @foreach ($tahunList as $tahun)
                  <option value="{{ $tahun }}">{{ $tahun }}</option>
                @endforeach
              </select>

              <!-- Filter Status -->
              <select class="form-select form-select-solid w-150px" id="filter_status">
                <option value="">Semua Status</option>
                <option value="1">Aktif</option>
                <option value="0">Tidak Aktif</option>
              </select>

              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_kelompok">
                <i class="ki-outline ki-plus fs-2"></i>
                Tambah Kelompok
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
                <span>Tidak ada data Kelompok Satuan Harga yang ditemukan.</span>
              </div>
            </div>
          @else
            <table id="kt_kelompok_table" class="table align-middle table-row-dashed fs-6 gy-5">
              <thead>
                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                  <th class="w-10px pe-2">
                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                      <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_kelompok_table .form-check-input"
                        value="1" />
                    </div>
                  </th>
                  <th class="min-w-150px">Kode Kategori</th>
                  <th class="min-w-250px">Uraian Kategori</th>
                  <th class="min-w-100px">Tipe</th>
                  <th class="min-w-100px">Tahun</th>
                  <th class="min-w-100px">Status</th>
                  <th class="text-end min-w-100px">Aksi</th>
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
                    <td class="fw-bold text-primary">{{ $item->kode_kategori }}</td>
                    <td>{{ Str::limit($item->uraian_kategori, 80) }}</td>
                    <td>
                      @switch($item->tipe_kelompok)
                        @case('SSH')
                          <span class="badge badge-light-success">{{ $item->tipe_kelompok }}</span>
                        @break

                        @case('SBU')
                          <span class="badge badge-light-primary">{{ $item->tipe_kelompok }}</span>
                        @break

                        @case('HSPK')
                          <span class="badge badge-light-info">{{ $item->tipe_kelompok }}</span>
                        @break

                        @case('ASB')
                          <span class="badge badge-light-warning">{{ $item->tipe_kelompok }}</span>
                        @break

                        @default
                          <span class="badge badge-light-secondary">{{ $item->tipe_kelompok }}</span>
                      @endswitch
                    </td>
                    <td>
                      <span class="badge badge-light-dark">{{ $item->tahun_anggaran }}</span>
                    </td>
                    <td>
                      <div class="form-check form-switch form-check-custom form-check-solid">
                        <input class="form-check-input toggle-active" type="checkbox" value="{{ $item->id }}"
                          {{ $item->active ? 'checked' : '' }} data-id="{{ $item->id }}" />
                        <label class="form-check-label">
                          {{ $item->active ? 'Aktif' : 'Tidak Aktif' }}
                        </label>
                      </div>
                    </td>
                    <td class="text-end">
                      <div class="d-inline-flex gap-1">
                        <a href="{{ route('kelompok_satuan_harga.edit', $item->id) }}"
                          class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm" title="Edit">
                          <i class="ki-outline ki-pencil fs-2"></i>
                        </a>
                        <form action="{{ route('kelompok_satuan_harga.destroy', $item->id) }}" method="POST" class="d-inline delete-form">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm delete-btn" title="Hapus"
                            data-name="{{ $item->kode_kategori }}">
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

  @include('standarhargasatuan.kelompok.partials.modal-create')
  @include('standarhargasatuan.kelompok.partials.scripts')
@endsection
