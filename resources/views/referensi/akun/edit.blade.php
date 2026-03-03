@extends('layouts.master')
@section('content')
  <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
      <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
          <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">Edit Akun</h1>
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
            <li class="breadcrumb-item text-muted">
              <a href="{{ route('referensi.akun.index') }}" class="text-muted text-hover-primary">Akun</a>
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
            <h2>Edit Akun: {{ $akun->kode_akun }}</h2>
          </div>
          <div class="card-toolbar">
            <a href="{{ route('referensi.akun.index') }}" class="btn btn-primary">
              <i class="ki-outline ki-arrow-left fs-2"></i>
              Kembali
            </a>
          </div>
        </div>

        <form action="{{ route('referensi.akun.update', $akun->id) }}" method="POST" class="form" id="edit_akun_form">
          @csrf
          @method('PUT')

          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <!-- Kode Akun -->
                <div class="fv-row mb-7">
                  <label class="required fs-6 fw-semibold mb-2">Kode Akun</label>
                  <input type="text" class="form-control form-control-solid @error('kode_akun') is-invalid @enderror" name="kode_akun"
                    value="{{ old('kode_akun', $akun->kode_akun) }}" maxlength="50" required />
                  @error('kode_akun')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <!-- Level -->
                <div class="fv-row mb-7">
                  <label class="fs-6 fw-semibold mb-2">Level Akun</label>
                  <input type="text" class="form-control form-control-solid @error('level') is-invalid @enderror" name="level"
                    value="{{ old('level', $akun->level) }}" maxlength="50" disabled />
                  @error('level')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <!-- Nama Akun -->
                <div class="fv-row mb-7">
                  <label class="required fs-6 fw-semibold mb-2">Nama Akun</label>
                  <textarea class="form-control form-control-solid @error('nama_akun') is-invalid @enderror" rows="3" name="nama_akun" required>{{ old('nama_akun', $akun->nama_akun) }}</textarea>
                  @error('nama_akun')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <!-- Keterangan Akun -->
                <div class="fv-row mb-7">
                  <label class="fs-6 fw-semibold mb-2">Keterangan Akun</label>
                  <textarea class="form-control form-control-solid @error('keterangan_akun') is-invalid @enderror" rows="5" name="keterangan_akun">{{ old('keterangan_akun', $akun->ket_akun) }}</textarea>
                  @error('keterangan_akun')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <div class="col-md-6">
                <!-- Tipe Akun Utama -->
                <div class="fv-row mb-7">
                  <label class="required fs-6 fw-semibold mb-2">Tipe Akun Utama</label>
                  <div class="form-text mb-3">Pilih salah satu tipe akun utama</div>

                  <div class="row">
                    <!-- Pendapatan Switch -->
                    <div class="col-md-4 mb-3">
                      <div class="form-check form-switch form-check-custom form-check-solid">
                        <input class="form-check-input akun-type-switch" type="checkbox" value="1" id="pendapatanSwitchEdit" name="is_pendapatan"
                          {{ old('is_pendapatan', $akun->is_pendapatan) ? 'checked' : '' }} />
                        <label class="form-check-label fw-bold" for="pendapatanSwitchEdit">
                          Pendapatan
                        </label>
                      </div>
                    </div>

                    <!-- Belanja Switch -->
                    <div class="col-md-4 mb-3">
                      <div class="form-check form-switch form-check-custom form-check-solid">
                        <input class="form-check-input akun-type-switch" type="checkbox" value="1" id="belanjaSwitchEdit" name="is_bl"
                          {{ old('is_bl', $akun->is_bl) ? 'checked' : '' }} />
                        <label class="form-check-label fw-bold" for="belanjaSwitchEdit">
                          Belanja
                        </label>
                      </div>
                    </div>

                    <!-- Pembiayaan Switch -->
                    <div class="col-md-4 mb-3">
                      <div class="form-check form-switch form-check-custom form-check-solid">
                        <input class="form-check-input akun-type-switch" type="checkbox" value="1" id="pembiayaanSwitchEdit" name="is_pembiayaan"
                          {{ old('is_pembiayaan', $akun->is_pembiayaan) ? 'checked' : '' }} />
                        <label class="form-check-label fw-bold" for="pembiayaanSwitchEdit">
                          Pembiayaan
                        </label>
                      </div>
                    </div>
                  </div>

                  <div class="invalid-feedback d-none" id="tipe-akun-error-edit">
                    Anda harus memilih salah satu tipe akun utama
                  </div>
                  @error('tipe_akun')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror
                </div>

                <!-- Separator -->
                <div class="separator separator-dashed my-5"></div>

                <!-- Kategori Khusus -->
                <div class="fv-row mb-7">
                  <label class="fs-6 fw-semibold mb-3">Kategori Khusus (Opsional)</label>
                  <div class="form-text mb-4">Pilih satu atau lebih kategori khusus yang sesuai</div>

                  <div class="d-flex flex-wrap gap-2">
                    <!-- BOS -->
                    <button type="button"
                      class="btn btn-sm kategori-toggle {{ old('is_bos', $akun->is_bos) ? 'btn-primary' : 'btn-light-primary' }}"
                      data-field="is_bos">
                      <i class="ki-duotone ki-check-circle fs-3 {{ old('is_bos', $akun->is_bos) ? '' : 'd-none' }}">
                        <span class="path1"></span>
                        <span class="path2"></span>
                      </i>
                      BOS
                    </button>
                    <input type="hidden" name="is_bos" value="{{ old('is_bos', $akun->is_bos) ? '1' : '0' }}" class="kategori-input"
                      data-field="is_bos">

                    <!-- Gaji ASN -->
                    <button type="button"
                      class="btn btn-sm kategori-toggle {{ old('is_gaji_asn', $akun->is_gaji_asn) ? 'btn-primary' : 'btn-light-primary' }}"
                      data-field="is_gaji_asn">
                      <i class="ki-duotone ki-check-circle fs-3 {{ old('is_gaji_asn', $akun->is_gaji_asn) ? '' : 'd-none' }}">
                        <span class="path1"></span>
                        <span class="path2"></span>
                      </i>
                      Gaji ASN
                    </button>
                    <input type="hidden" name="is_gaji_asn" value="{{ old('is_gaji_asn', $akun->is_gaji_asn) ? '1' : '0' }}"
                      class="kategori-input" data-field="is_gaji_asn">

                    <!-- Barang & Jasa -->
                    <button type="button"
                      class="btn btn-sm kategori-toggle {{ old('is_barjas', $akun->is_barjas) ? 'btn-primary' : 'btn-light-primary' }}"
                      data-field="is_barjas">
                      <i class="ki-duotone ki-check-circle fs-3 {{ old('is_barjas', $akun->is_barjas) ? '' : 'd-none' }}">
                        <span class="path1"></span>
                        <span class="path2"></span>
                      </i>
                      Barang & Jasa
                    </button>
                    <input type="hidden" name="is_barjas" value="{{ old('is_barjas', $akun->is_barjas) ? '1' : '0' }}" class="kategori-input"
                      data-field="is_barjas">

                    <!-- BTT -->
                    <button type="button"
                      class="btn btn-sm kategori-toggle {{ old('is_btt', $akun->is_btt) ? 'btn-primary' : 'btn-light-primary' }}"
                      data-field="is_btt">
                      <i class="ki-duotone ki-check-circle fs-3 {{ old('is_btt', $akun->is_btt) ? '' : 'd-none' }}">
                        <span class="path1"></span>
                        <span class="path2"></span>
                      </i>
                      BTT
                    </button>
                    <input type="hidden" name="is_btt" value="{{ old('is_btt', $akun->is_btt) ? '1' : '0' }}" class="kategori-input"
                      data-field="is_btt">

                    <!-- Hibah Uang -->
                    <button type="button"
                      class="btn btn-sm kategori-toggle {{ old('is_hibah_uang', $akun->is_hibah_uang) ? 'btn-warning' : 'btn-light-warning' }}"
                      data-field="is_hibah_uang">
                      <i class="ki-duotone ki-check-circle fs-3 {{ old('is_hibah_uang', $akun->is_hibah_uang) ? '' : 'd-none' }}">
                        <span class="path1"></span>
                        <span class="path2"></span>
                      </i>
                      Hibah Uang
                    </button>
                    <input type="hidden" name="is_hibah_uang" value="{{ old('is_hibah_uang', $akun->is_hibah_uang) ? '1' : '0' }}"
                      class="kategori-input" data-field="is_hibah_uang">

                    <!-- Hibah Barang -->
                    <button type="button"
                      class="btn btn-sm kategori-toggle {{ old('is_hibah_brg', $akun->is_hibah_brg) ? 'btn-warning' : 'btn-light-warning' }}"
                      data-field="is_hibah_brg">
                      <i class="ki-duotone ki-check-circle fs-3 {{ old('is_hibah_brg', $akun->is_hibah_brg) ? '' : 'd-none' }}">
                        <span class="path1"></span>
                        <span class="path2"></span>
                      </i>
                      Hibah Barang
                    </button>
                    <input type="hidden" name="is_hibah_brg" value="{{ old('is_hibah_brg', $akun->is_hibah_brg) ? '1' : '0' }}"
                      class="kategori-input" data-field="is_hibah_brg">

                    <!-- Bansos Uang -->
                    <button type="button"
                      class="btn btn-sm kategori-toggle {{ old('is_sosial_uang', $akun->is_sosial_uang) ? 'btn-success' : 'btn-light-success' }}"
                      data-field="is_sosial_uang">
                      <i class="ki-duotone ki-check-circle fs-3 {{ old('is_sosial_uang', $akun->is_sosial_uang) ? '' : 'd-none' }}">
                        <span class="path1"></span>
                        <span class="path2"></span>
                      </i>
                      Bansos Uang
                    </button>
                    <input type="hidden" name="is_sosial_uang" value="{{ old('is_sosial_uang', $akun->is_sosial_uang) ? '1' : '0' }}"
                      class="kategori-input" data-field="is_sosial_uang">

                    <!-- Bansos Barang -->
                    <button type="button"
                      class="btn btn-sm kategori-toggle {{ old('is_sosial_brg', $akun->is_sosial_brg) ? 'btn-success' : 'btn-light-success' }}"
                      data-field="is_sosial_brg">
                      <i class="ki-duotone ki-check-circle fs-3 {{ old('is_sosial_brg', $akun->is_sosial_brg) ? '' : 'd-none' }}">
                        <span class="path1"></span>
                        <span class="path2"></span>
                      </i>
                      Bansos Barang
                    </button>
                    <input type="hidden" name="is_sosial_brg" value="{{ old('is_sosial_brg', $akun->is_sosial_brg) ? '1' : '0' }}"
                      class="kategori-input" data-field="is_sosial_brg">

                    <!-- Subsidi -->
                    <button type="button"
                      class="btn btn-sm kategori-toggle {{ old('is_subsidi', $akun->is_subsidi) ? 'btn-info' : 'btn-light-info' }}"
                      data-field="is_subsidi">
                      <i class="ki-duotone ki-check-circle fs-3 {{ old('is_subsidi', $akun->is_subsidi) ? '' : 'd-none' }}">
                        <span class="path1"></span>
                        <span class="path2"></span>
                      </i>
                      Subsidi
                    </button>
                    <input type="hidden" name="is_subsidi" value="{{ old('is_subsidi', $akun->is_subsidi) ? '1' : '0' }}" class="kategori-input"
                      data-field="is_subsidi">

                    <!-- Bagi Hasil -->
                    <button type="button"
                      class="btn btn-sm kategori-toggle {{ old('is_bagi_hasil', $akun->is_bagi_hasil) ? 'btn-info' : 'btn-light-info' }}"
                      data-field="is_bagi_hasil">
                      <i class="ki-duotone ki-check-circle fs-3 {{ old('is_bagi_hasil', $akun->is_bagi_hasil) ? '' : 'd-none' }}">
                        <span class="path1"></span>
                        <span class="path2"></span>
                      </i>
                      Bagi Hasil
                    </button>
                    <input type="hidden" name="is_bagi_hasil" value="{{ old('is_bagi_hasil', $akun->is_bagi_hasil) ? '1' : '0' }}"
                      class="kategori-input" data-field="is_bagi_hasil">

                    <!-- Bunga -->
                    <button type="button"
                      class="btn btn-sm kategori-toggle {{ old('is_bunga', $akun->is_bunga) ? 'btn-secondary' : 'btn-light-secondary' }}"
                      data-field="is_bunga">
                      <i class="ki-duotone ki-check-circle fs-3 {{ old('is_bunga', $akun->is_bunga) ? '' : 'd-none' }}">
                        <span class="path1"></span>
                        <span class="path2"></span>
                      </i>
                      Bunga
                    </button>
                    <input type="hidden" name="is_bunga" value="{{ old('is_bunga', $akun->is_bunga) ? '1' : '0' }}" class="kategori-input"
                      data-field="is_bunga">

                    <!-- Modal Tanah -->
                    <button type="button"
                      class="btn btn-sm kategori-toggle {{ old('is_modal_tanah', $akun->is_modal_tanah) ? 'btn-danger' : 'btn-light-danger' }}"
                      data-field="is_modal_tanah">
                      <i class="ki-duotone ki-check-circle fs-3 {{ old('is_modal_tanah', $akun->is_modal_tanah) ? '' : 'd-none' }}">
                        <span class="path1"></span>
                        <span class="path2"></span>
                      </i>
                      Modal Tanah
                    </button>
                    <input type="hidden" name="is_modal_tanah" value="{{ old('is_modal_tanah', $akun->is_modal_tanah) ? '1' : '0' }}"
                      class="kategori-input" data-field="is_modal_tanah">

                    <!-- Bankeu Umum -->
                    <button type="button"
                      class="btn btn-sm kategori-toggle {{ old('is_bankeu_umum', $akun->is_bankeu_umum) ? 'btn-primary' : 'btn-light-primary' }}"
                      data-field="is_bankeu_umum">
                      <i class="ki-duotone ki-check-circle fs-3 {{ old('is_bankeu_umum', $akun->is_bankeu_umum) ? '' : 'd-none' }}">
                        <span class="path1"></span>
                        <span class="path2"></span>
                      </i>
                      Bankeu Umum
                    </button>
                    <input type="hidden" name="is_bankeu_umum" value="{{ old('is_bankeu_umum', $akun->is_bankeu_umum) ? '1' : '0' }}"
                      class="kategori-input" data-field="is_bankeu_umum">

                    <!-- Bankeu Khusus -->
                    <button type="button"
                      class="btn btn-sm kategori-toggle {{ old('is_bankeu_khusus', $akun->is_bankeu_khusus) ? 'btn-primary' : 'btn-light-primary' }}"
                      data-field="is_bankeu_khusus">
                      <i class="ki-duotone ki-check-circle fs-3 {{ old('is_bankeu_khusus', $akun->is_bankeu_khusus) ? '' : 'd-none' }}">
                        <span class="path1"></span>
                        <span class="path2"></span>
                      </i>
                      Bankeu Khusus
                    </button>
                    <input type="hidden" name="is_bankeu_khusus" value="{{ old('is_bankeu_khusus', $akun->is_bankeu_khusus) ? '1' : '0' }}"
                      class="kategori-input" data-field="is_bankeu_khusus">
                  </div>
                </div>

                <!-- Info Current Values -->
                <div class="alert alert-info mt-5">
                  <h6 class="mb-2"><strong>Status Akun Saat Ini:</strong></h6>
                  <div class="d-flex flex-wrap gap-2">
                    <span class="badge badge-light-{{ $akun->is_pendapatan ? 'success' : 'secondary' }}">
                      Pendapatan: {{ $akun->is_pendapatan ? 'Ya' : 'Tidak' }}
                    </span>
                    <span class="badge badge-light-{{ $akun->is_bl ? 'success' : 'secondary' }}">
                      Belanja: {{ $akun->is_bl ? 'Ya' : 'Tidak' }}
                    </span>
                    <span class="badge badge-light-{{ $akun->is_pembiayaan ? 'success' : 'secondary' }}">
                      Pembiayaan: {{ $akun->is_pembiayaan ? 'Ya' : 'Tidak' }}
                    </span>
                  </div>
                </div>

                @if ($akun->updated_at)
                  <div class="alert alert-warning ">
                    <strong>Terakhir diupdate:</strong> {{ $akun->updated_at->format('d/m/Y H:i:s') }}
                  </div>
                @endif
              </div>
            </div>
          </div>

          <div class="card-footer">
            <div class="d-flex justify-content-end">
              <a href="{{ route('referensi.akun.index') }}" class="btn btn-light me-3">Batal</a>
              <button type="submit" class="btn btn-success" id="update_akun_btn">
                <i class="ki-outline ki-check fs-2"></i>
                Update Akun
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  @include('referensi.akun.partials.scripts-edit')
@endsection
