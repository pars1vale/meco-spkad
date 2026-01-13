@extends('layouts.master')
@section('content')
  <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
      <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
          <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">Edit Data SSH</h1>
          <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
            <li class="breadcrumb-item text-muted">
              <a href="{{ url('/home') }}" class="text-muted text-hover-primary">Home</a>
            </li>
            <li class="breadcrumb-item">
              <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">
              <a href="{{ route('data_ssh.index') }}" class="text-muted text-hover-primary">Data SSH</a>
            </li>
            <li class="breadcrumb-item">
              <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">Edit</li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-fluid">
      <div class="card">
        <div class="card-header">
          <div class="card-title">
            <h2>Edit Data SSH: {{ $ssh->kode_standar_harga }}</h2>
          </div>
          <div class="card-toolbar">
            <a href="{{ route('data_ssh.index') }}" class="btn btn-secondary">
              <i class="ki-outline ki-arrow-left fs-2"></i>
              Kembali
            </a>
          </div>
        </div>

        @if ($ssh->is_locked)
          <div class="card-body">
            <div class="alert alert-warning d-flex align-items-center p-5">
              <i class="ki-outline ki-lock fs-2hx me-3 text-warning"></i>
              <div class="d-flex flex-column">
                <h4 class="mb-1 text-warning">Data Terkunci</h4>
                <span>Data ini dalam status terkunci dan tidak dapat diubah. Silakan hubungi administrator untuk membuka kunci.</span>
              </div>
            </div>
          </div>
        @else
          <form action="{{ route('data_ssh.update', $ssh->id_standar_harga) }}" method="POST" class="form" id="edit_ssh_form">
            @csrf
            @method('PUT')

            <div class="card-body">
              @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                  <i class="ki-outline ki-check-circle fs-2 text-success me-3"></i>
                  {{ session('success') }}
                  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
              @endif

              @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                  <i class="ki-outline ki-cross-circle fs-2 text-danger me-3"></i>
                  {{ session('error') }}
                  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
              @endif

              <div class="row">
                <div class="col-md-6">
                  <!-- Tipe Standar Harga -->
                  <div class="fv-row mb-7">
                    <label class="required fs-6 fw-semibold mb-2">Tipe Standar Harga</label>
                    <div class="d-flex flex-column gap-2">
                      @foreach (['SSH' => 'SSH - Standar Satuan Harga', 'HSPK' => 'HSPK - Harga Satuan Pokok Kegiatan', 'ASB' => 'ASB - Analisa Standar Belanja', 'SBU' => 'SBU - Standar Biaya Umum'] as $value => $label)
                        <div class="form-check form-check-custom form-check-solid">
                          <input class="form-check-input @error('tipe_standar_harga') is-invalid @enderror" type="radio" value="{{ $value }}"
                            id="tipe{{ $value }}Edit" name="tipe_standar_harga"
                            {{ old('tipe_standar_harga', $ssh->tipe_standar_harga) == $value ? 'checked' : '' }} required />
                          <label class="form-check-label fw-bold" for="tipe{{ $value }}Edit">{{ $label }}</label>
                        </div>
                      @endforeach
                    </div>
                    @error('tipe_standar_harga')
                      <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                  </div>

                  <!-- Kelompok -->
                  <div class="fv-row mb-7">
                    <label class="required fs-6 fw-semibold mb-2">Kelompok Standar Harga</label>
                    <select class="form-select form-select-solid @error('id_kel_standar_harga') is-invalid @enderror" name="id_kel_standar_harga"
                      required>
                      <option value="">Pilih Kelompok</option>
                      @foreach ($kelompokList as $kel)
                        <option value="{{ $kel->id_kategori }}"
                          {{ old('id_kel_standar_harga', $ssh->id_kel_standar_harga) == $kel->id_kategori ? 'selected' : '' }}>
                          {{ $kel->kode_kategori }} - {{ $kel->uraian_kategori }}
                        </option>
                      @endforeach
                    </select>
                    @error('id_kel_standar_harga')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>

                  <!-- Kode -->
                  <div class="fv-row mb-7">
                    <label class="required fs-6 fw-semibold mb-2">Kode Standar Harga</label>
                    <input type="text" class="form-control form-control-solid @error('kode_standar_harga') is-invalid @enderror"
                      name="kode_standar_harga" value="{{ old('kode_standar_harga', $ssh->kode_standar_harga) }}" maxlength="50" required />
                    @error('kode_standar_harga')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>

                  <!-- Satuan -->
                  <div class="fv-row mb-7">
                    <label class="required fs-6 fw-semibold mb-2">Satuan</label>
                    <input type="text" class="form-control form-control-solid @error('satuan') is-invalid @enderror" name="satuan"
                      value="{{ old('satuan', $ssh->satuan) }}" maxlength="50" required />
                    @error('satuan')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>

                  <!-- Harga -->
                  <div class="fv-row mb-7">
                    <label class="required fs-6 fw-semibold mb-2">Harga</label>
                    <input type="number" class="form-control form-control-solid @error('harga') is-invalid @enderror" name="harga"
                      value="{{ old('harga', $ssh->harga) }}" step="0.01" min="0" required />
                    @error('harga')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>

                <div class="col-md-6">
                  <!-- Tahun -->
                  <div class="fv-row mb-7">
                    <label class="required fs-6 fw-semibold mb-2">Tahun Anggaran</label>
                    <input type="number" class="form-control form-control-solid @error('tahun') is-invalid @enderror" name="tahun"
                      value="{{ old('tahun', $ssh->tahun) }}" min="2000" max="2100" required />
                    @error('tahun')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>

                  <!-- ID Daerah -->
                  <div class="fv-row mb-7">
                    <label class="required fs-6 fw-semibold mb-2">ID Daerah</label>
                    <input type="number" class="form-control form-control-solid @error('id_daerah') is-invalid @enderror" name="id_daerah"
                      value="{{ old('id_daerah', $ssh->id_daerah) }}" required />
                    @error('id_daerah')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>

                  <!-- TKDN -->
                  <div class="fv-row mb-7">
                    <label class="fs-6 fw-semibold mb-2">Nilai TKDN (%)</label>
                    <input type="number" class="form-control form-control-solid @error('nilai_tkdn') is-invalid @enderror" name="nilai_tkdn"
                      value="{{ old('nilai_tkdn', $ssh->nilai_tkdn) }}" step="0.01" min="0" max="100" />
                    @error('nilai_tkdn')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>

                  <!-- PDN -->
                  <div class="fv-row mb-7">
                    <div class="form-check form-switch form-check-custom form-check-solid">
                      <input class="form-check-input" type="checkbox" value="1" id="isPdnEdit" name="is_pdn"
                        {{ old('is_pdn', $ssh->is_pdn) ? 'checked' : '' }} />
                      <label class="form-check-label fw-bold" for="isPdnEdit">Produk Dalam Negeri (PDN)</label>
                    </div>
                  </div>

                  <!-- Info -->
                  <div class="alert alert-info">
                    <h6 class="mb-2"><strong>Informasi:</strong></h6>
                    <div class="d-flex flex-column gap-1">
                      <span><strong>ID Standar Harga:</strong> {{ $ssh->id_standar_harga }}</span>
                      <span><strong>ID Unik:</strong> <code>{{ $ssh->id_unik }}</code></span>
                      <span><strong>Status:</strong>
                        @if ($ssh->is_locked)
                          <span class="badge badge-warning">Terkunci</span>
                        @else
                          <span class="badge badge-success">Tidak Terkunci</span>
                        @endif
                      </span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Nama -->
              <div class="fv-row mb-7">
                <label class="required fs-6 fw-semibold mb-2">Nama Standar Harga</label>
                <textarea class="form-control form-control-solid @error('nama_standar_harga') is-invalid @enderror" rows="3" name="nama_standar_harga"
                  maxlength="255" required>{{ old('nama_standar_harga', $ssh->nama_standar_harga) }}</textarea>
                @error('nama_standar_harga')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Spek -->
              <div class="fv-row mb-7">
                <label class="fs-6 fw-semibold mb-2">Spesifikasi</label>
                <textarea class="form-control form-control-solid @error('spek') is-invalid @enderror" rows="3" name="spek">{{ old('spek', $ssh->spek) }}</textarea>
                @error('spek')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Keterangan -->
              <div class="fv-row mb-7">
                <label class="fs-6 fw-semibold mb-2">Keterangan</label>
                <textarea class="form-control form-control-solid @error('ket_teks') is-invalid @enderror" rows="2" name="ket_teks">{{ old('ket_teks', $ssh->ket_teks) }}</textarea>
                @error('ket_teks')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="card-footer">
              <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('data_ssh.index') }}" class="btn btn-light">Batal</a>
                <button type="submit" class="btn btn-primary" id="update_ssh_btn">
                  <i class="ki-outline ki-check fs-2"></i>
                  Update Data
                </button>
              </div>
            </div>
          </form>
        @endif
      </div>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const editForm = document.getElementById('edit_ssh_form');
      const updateButton = document.getElementById('update_ssh_btn');

      if (editForm && updateButton) {
        editForm.addEventListener('submit', function() {
          updateButton.setAttribute('data-kt-indicator', 'on');
          updateButton.disabled = true;
        });
      }
    });
  </script>
@endsection
