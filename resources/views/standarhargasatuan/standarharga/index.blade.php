@extends('layouts.master')
@section('content')
  <x-toolbar title="Data SSH" :breadcrumbs="[['label' => 'Home', 'url' => url('/')], ['label' => 'Standar Harga'], ['label' => 'Data SSH']]" />

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
              <input type="text" id="kt_datatable_search_input" class="form-control form-control-solid w-250px ps-12" placeholder="Cari Data SSH">
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

              <!-- Filter Lock Status -->
              <select class="form-select form-select-solid w-150px" id="filter_lock">
                <option value="">Semua Status</option>
                <option value="0">Tidak Terkunci</option>
                <option value="1">Terkunci</option>
              </select>

              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_ssh">
                <i class="ki-outline ki-plus fs-2"></i>
                Tambah Data SSH
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
                <span>Tidak ada Data SSH yang ditemukan.</span>
              </div>
            </div>
          @else
            <table id="kt_ssh_table" class="table align-middle table-row-dashed fs-6 gy-5">
              <thead>
                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                  <th class="w-10px pe-2">
                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                      <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_ssh_table .form-check-input"
                        value="1" />
                    </div>
                  </th>
                  <th class="min-w-100px">Kode</th>
                  <th class="min-w-250px">Nama Standar Harga</th>
                  <th class="min-w-100px">Tipe</th>
                  <th class="min-w-150px">Kelompok</th>
                  <th class="min-w-80px">Satuan</th>
                  <th class="min-w-120px">Harga</th>
                  <th class="min-w-80px">Tahun</th>
                  <th class="min-w-100px">Status</th>
                  <th class="text-end min-w-100px">Aksi</th>
                </tr>
              </thead>
              <tbody class="fw-semibold text-gray-600">
                @foreach ($data as $item)
                  <tr>
                    <td>
                      <div class="form-check form-check-sm form-check-custom form-check-solid">
                        <input class="form-check-input row-checkbox" type="checkbox" value="{{ $item->id_standar_harga }}"
                          {{ $item->is_locked ? 'disabled' : '' }} />
                      </div>
                    </td>
                    <td class="fw-bold">{{ $item->kode_standar_harga }}</td>
                    <td>
                      <div class="fw-bold">{{ $item->nama_standar_harga }}</div>
                      @if ($item->spek)
                        <div class="text-muted fs-7">{{ Str::limit($item->spek, 50) }}</div>
                      @endif
                    </td>
                    <td>
                      @switch($item->tipe_standar_harga)
                        @case('SSH')
                          <span class="badge badge-light-success">{{ $item->tipe_standar_harga }}</span>
                        @break

                        @case('SBU')
                          <span class="badge badge-light-primary">{{ $item->tipe_standar_harga }}</span>
                        @break

                        @case('HSPK')
                          <span class="badge badge-light-info">{{ $item->tipe_standar_harga }}</span>
                        @break

                        @case('ASB')
                          <span class="badge badge-light-warning">{{ $item->tipe_standar_harga }}</span>
                        @break

                        @default
                          <span class="badge badge-light-secondary">{{ $item->tipe_standar_harga }}</span>
                      @endswitch
                    </td>
                    <td>
                      <div class="text-gray-800">{{ $item->kode_kel_standar_harga }}</div>
                      <div class="text-muted fs-7">{{ Str::limit($item->nama_kel_standar_harga, 30) }}</div>
                    </td>
                    <td>{{ $item->satuan }}</td>
                    <td>Rp {{ number_format($item->harga, 2, ',', '.') }}</td>
                    <td>
                      <span class="badge badge-light-dark">{{ $item->tahun }}</span>
                    </td>
                    <td>
                      <div class="form-check form-switch form-check-custom form-check-solid">
                        <input class="form-check-input toggle-lock" type="checkbox" value="{{ $item->id_standar_harga }}"
                          {{ $item->is_locked ? 'checked' : '' }} data-id="{{ $item->id_standar_harga }}" />
                        <label class="form-check-label">
                          {{ $item->is_locked ? 'Terkunci' : 'Tidak Terkunci' }}
                        </label>
                      </div>
                    </td>
                    <td class="text-end">
                      <div class="d-inline-flex gap-1">
                        <button type="button" class="btn btn-icon btn-bg-light btn-active-color-info btn-sm" data-bs-toggle="modal"
                          data-bs-target="#modal_detail_{{ $item->id_standar_harga }}" title="Detail">
                          <i class="ki-outline ki-information-5 fs-2"></i>
                        </button>
                        <a href="{{ route('data_ssh.edit', $item->id_standar_harga) }}"
                          class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm {{ $item->is_locked ? 'disabled' : '' }}" title="Edit">
                          <i class="ki-outline ki-pencil fs-2"></i>
                        </a>
                        <form action="{{ route('data_ssh.destroy', $item->id_standar_harga) }}" method="POST" class="d-inline delete-form">
                          @csrf
                          @method('DELETE')
                          <button type="submit"
                            class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm delete-btn {{ $item->is_locked ? 'disabled' : '' }}"
                            title="Hapus" data-name="{{ $item->nama_standar_harga }}" {{ $item->is_locked ? 'disabled' : '' }}>
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

  {{-- Include Modal Components --}}
  @include('standarhargasatuan.standarharga.partials.modal-detail')
  @include('standarhargasatuan.standarharga.partials.modal-create')
  @include('standarhargasatuan.standarharga.partials.scripts')
@endsection
