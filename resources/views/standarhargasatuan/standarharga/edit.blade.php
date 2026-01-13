@extends('layouts.master')
@section('content')
  <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
      <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
          <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">Edit Standar Harga</h1>
          <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
            <li class="breadcrumb-item text-muted">
              <a href="{{ url('/home') }}" class="text-muted text-hover-primary">Home</a>
            </li>
            <li class="breadcrumb-item">
              <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">
              <a href="{{ route('standar_harga.index') }}" class="text-muted text-hover-primary">Standar Harga</a>
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
            <h2>Edit Standar Harga: {{ $standarHarga->kode_standar_harga }}</h2>
          </div>
          <div class="card-toolbar">
            <a href="{{ route('standar_harga.index') }}" class="btn btn-secondary">
              <i class="ki-outline ki-arrow-left fs-2"></i>
              Kembali
            </a>
          </div>
        </div>

        <form action="{{ route('standar_harga.update', $standarHarga->id) }}" method="POST" class="form" id="edit_standar_harga_form">
          @csrf
          @method('PUT')

          <div class="card-body">
            @if (session('success'))
              <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
              <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="row">
              <div class="col-md-6">
                <!-- Kode Standar Harga -->
                <div class="fv-row mb-7">
                  <label class="required fs-6 fw-semibold mb-2">Kode Standar Harga</label>
                  <input type="text" class="form-control form-control-solid @error('kode_standar_harga') is-invalid @enderror"
                    name="kode_standar_harga" value="{{ old('kode_standar_harga', $standarHarga->kode_standar_harga) }}" maxlength="50" required />
                  @error('kode_standar_harga')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <!-- Tipe Standar Harga -->
                <div class="fv-row mb-7">
                  <label class="required fs-6 fw-semibold mb-2">Tipe Standar Harga</label>
                  <div class="form-text mb-3">Tipe standar harga saat ini</div>

                  <div class="d-flex flex-wrap gap-3">
                    <div class="form-check form-check-custom form-check-solid">
                      <input class="form-check-input tipe-standar-harga-radio-edit @error('tipe_standar_harga') is-invalid @enderror" type="radio"
                        value="SSH" id="tipeSSHEdit" name="tipe_standar_harga"
                        {{ old('tipe_standar_harga', $standarHarga->tipe_standar_harga) == 'SSH' ? 'checked' : '' }} required />
                      <label class="form-check-label fw-bold" for="tipeSSHEdit">SSH</label>
                    </div>

                    <div class="form-check form-check-custom form-check-solid">
                      <input class="form-check-input tipe-standar-harga-radio-edit @error('tipe_standar_harga') is-invalid @enderror" type="radio"
                        value="HSPK" id="tipeHSPKEdit" name="tipe_standar_harga"
                        {{ old('tipe_standar_harga', $standarHarga->tipe_standar_harga) == 'HSPK' ? 'checked' : '' }} />
                      <label class="form-check-label fw-bold" for="tipeHSPKEdit">HSPK</label>
                    </div>

                    <div class="form-check form-check-custom form-check-solid">
                      <input class="form-check-input tipe-standar-harga-radio-edit @error('tipe_standar_harga') is-invalid @enderror" type="radio"
                        value="ASB" id="tipeASBEdit" name="tipe_standar_harga"
                        {{ old('tipe_standar_harga', $standarHarga->tipe_standar_harga) == 'ASB' ? 'checked' : '' }} />
                      <label class="form-check-label fw-bold" for="tipeASBEdit">ASB</label>
                    </div>

                    <div class="form-check form-check-custom form-check-solid">
                      <input class="form-check-input tipe-standar-harga-radio-edit @error('tipe_standar_harga') is-invalid @enderror" type="radio"
                        value="SBU" id="tipeSBUEdit" name="tipe_standar_harga"
                        {{ old('tipe_standar_harga', $standarHarga->tipe_standar_harga) == 'SBU' ? 'checked' : '' }} />
                      <label class="form-check-label fw-bold" for="tipeSBUEdit">SBU</label>
                    </div>
                  </div>
                  @error('tipe_standar_harga')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror
                </div>

                <!-- Kelompok Standar Harga -->
                <div class="fv-row mb-7">
                  <label class="required fs-6 fw-semibold mb-2">Kelompok Standar Harga</label>
                  <select class="form-select form-select-solid @error('id_kelompok_standar_harga') is-invalid @enderror"
                    name="id_kelompok_standar_harga" id="kelompok_select_edit" required>
                    <option value="">Pilih Kelompok</option>
                    @foreach ($kelompok as $kel)
                      <option value="{{ $kel->id }}"
                        {{ old('id_kelompok_standar_harga', $standarHarga->id_kelompok_standar_harga) == $kel->id ? 'selected' : '' }}>
                        {{ $kel->kode_kelompok_standar_harga }} - {{ $kel->nama_kelompok_standar_harga }}
                      </option>
                    @endforeach
                  </select>
                  @error('id_kelompok_standar_harga')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                  <div class="form-text">Kelompok akan difilter sesuai tipe yang dipilih</div>
                </div>

                <!-- Satuan -->
                <div class="fv-row mb-7">
                  <label class="required fs-6 fw-semibold mb-2">Satuan</label>
                  <select class="form-select form-select-solid @error('id_satuan') is-invalid @enderror" name="id_satuan" required>
                    <option value="">Pilih Satuan</option>
                    @foreach ($satuan as $sat)
                      <option value="{{ $sat->id }}" {{ old('id_satuan', $standarHarga->id_satuan) == $sat->id ? 'selected' : '' }}>
                        {{ $sat->nama_satuan }}
                      </option>
                    @endforeach
                  </select>
                  @error('id_satuan')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <!-- Harga -->
                <div class="fv-row mb-7">
                  <label class="required fs-6 fw-semibold mb-2">Harga</label>
                  <input type="number" class="form-control form-control-solid @error('harga') is-invalid @enderror" name="harga"
                    value="{{ old('harga', $standarHarga->harga) }}" step="0.01" min="0" required />
                  @error('harga')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <div class="col-md-6">
                <!-- Nilai TKDN -->
                <div class="fv-row mb-7">
                  <label class="fs-6 fw-semibold mb-2">Nilai TKDN (%)</label>
                  <input type="number" class="form-control form-control-solid @error('nilai_tkdn') is-invalid @enderror" name="nilai_tkdn"
                    value="{{ old('nilai_tkdn', $standarHarga->nilai_tkdn) }}" step="0.01" min="0" max="100" />
                  @error('nilai_tkdn')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <!-- Is PDN -->
                <div class="fv-row mb-7">
                  <div class="form-check form-switch form-check-custom form-check-solid">
                    <input class="form-check-input" type="checkbox" value="1" id="isPdnSwitchEdit" name="is_pdn"
                      {{ old('is_pdn', $standarHarga->is_pdn) ? 'checked' : '' }} />
                    <label class="form-check-label" for="isPdnSwitchEdit">
                      Produk Dalam Negeri (PDN)
                    </label>
                  </div>
                </div>

                <!-- Info -->
                <div class="alert alert-info">
                  <h6 class="mb-2"><strong>Informasi Saat Ini:</strong></h6>
                  <div class="d-flex flex-column gap-1">
                    <span><strong>Tipe:</strong> <span class="badge badge-light-info">{{ $standarHarga->tipe_standar_harga }}</span></span>
                    <span><strong>Kelompok:</strong> {{ $standarHarga->kelompokStandarHarga->nama_kelompok_standar_harga }}</span>
                    <span><strong>Satuan:</strong> {{ $standarHarga->satuan->nama_satuan }}</span>
                    <span><strong>Harga:</strong> Rp {{ number_format($standarHarga->harga, 2, ',', '.') }}</span>
                  </div>
                </div>

                @if ($standarHarga->updated_at)
                  <div class="alert alert-success">
                    <strong>Terakhir diupdate:</strong> {{ $standarHarga->updated_at->format('d/m/Y H:i:s') }}
                  </div>
                @endif
              </div>
            </div>

            <!-- Nama Standar Harga -->
            <div class="fv-row mb-7">
              <label class="required fs-6 fw-semibold mb-2">Nama Standar Harga</label>
              <textarea class="form-control form-control-solid @error('nama_standar_harga') is-invalid @enderror" rows="3" name="nama_standar_harga"
                required>{{ old('nama_standar_harga', $standarHarga->nama_standar_harga) }}</textarea>
              @error('nama_standar_harga')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <!-- Spesifikasi -->
            <div class="fv-row mb-7">
              <label class="fs-6 fw-semibold mb-2">Spesifikasi</label>
              <textarea class="form-control form-control-solid @error('spesifikasi') is-invalid @enderror" rows="3" name="spesifikasi">{{ old('spesifikasi', $standarHarga->spesifikasi) }}</textarea>
              @error('spesifikasi')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="separator my-7"></div>

            <!-- Rekening Belanja dengan Repeater -->
            <div class="fv-row mb-7">
              <label class="required fs-6 fw-semibold mb-2">Rekening Belanja (Akun)</label>
              <div class="form-text mb-3">Kelola rekening belanja (minimal satu)</div>

              <!--begin::Repeater-->
              <div id="kt_rekening_repeater_edit">
                <!--begin::Form group-->
                <div class="form-group">
                  <div data-repeater-list="rekening_belanja">
                    @php
                      $selectedRekening = old(
                          'rekening_belanja',
                          $standarHarga->rekeningBelanja
                              ->map(function ($r) {
                                  return ['id_akun' => $r->id];
                              })
                              ->toArray(),
                      );
                    @endphp

                    @foreach ($selectedRekening as $rekening)
                      <div data-repeater-item>
                        <div class="form-group row align-items-center mb-5">
                          <div class="col-md-10">
                            <select class="form-select form-select-solid" data-kt-repeater="select2" data-placeholder="Pilih rekening belanja"
                              name="id_akun" required>
                              <option value="">Pilih Rekening</option>
                              @foreach ($akun as $ak)
                                <option value="{{ $ak->id }}" {{ $rekening['id_akun'] == $ak->id ? 'selected' : '' }}>
                                  {{ $ak->kode_akun }} - {{ $ak->nama_akun }}
                                </option>
                              @endforeach
                            </select>
                          </div>
                          <div class="col-md-2">
                            <a href="javascript:;" data-repeater-delete class="btn btn-sm btn-icon btn-light-danger">
                              <i class="ki-outline ki-trash fs-3"></i>
                            </a>
                          </div>
                        </div>
                      </div>
                    @endforeach
                  </div>
                </div>
                <!--end::Form group-->

                <!--begin::Form group-->
                <div class="form-group">
                  <a href="javascript:;" data-repeater-create class="btn btn-sm btn-light-primary">
                    <i class="ki-outline ki-plus fs-3"></i>
                    Tambah Rekening
                  </a>
                </div>
                <!--end::Form group-->
              </div>
              <!--end::Repeater-->

              <div class="invalid-feedback d-none" id="rekening-error-edit">
                Minimal satu rekening belanja harus dipilih
              </div>
              @error('rekening_belanja')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="card-footer">
            <div class="d-flex justify-content-end">
              <a href="{{ route('standar_harga.index') }}" class="btn btn-light me-3">Batal</a>
              <button type="submit" class="btn btn-primary" id="update_standar_harga_btn">
                <i class="ki-outline ki-check fs-2"></i>
                Update
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const editForm = document.getElementById('edit_standar_harga_form');
      const updateButton = document.getElementById('update_standar_harga_btn');
      const rekeningError = document.getElementById('rekening-error-edit');
      const tipeRadios = document.querySelectorAll('.tipe-standar-harga-radio-edit');
      const kelompokSelect = document.getElementById('kelompok_select_edit');

      // Initialize Repeater with existing data
      $('#kt_rekening_repeater_edit').repeater({
        initEmpty: false,

        defaultValues: {
          'id_akun': ''
        },

        show: function() {
          $(this).slideDown();

          // Re-init select2 untuk row baru
          $(this).find('[data-kt-repeater="select2"]').select2({
            placeholder: "Pilih rekening belanja",
            allowClear: true
          });
        },

        hide: function(deleteElement) {
          // Cek jika hanya ada 1 row tersisa
          const rowCount = $('#kt_rekening_repeater_edit [data-repeater-item]').length;

          if (rowCount <= 1) {
            Swal.fire({
              icon: 'warning',
              title: 'Tidak dapat menghapus',
              text: 'Minimal harus ada satu rekening belanja',
              confirmButtonText: 'OK',
              buttonsStyling: false,
              customClass: {
                confirmButton: "btn btn-primary"
              }
            });
            return;
          }

          $(this).slideUp(deleteElement);
        },

        ready: function() {
          // Init select2 untuk existing rows
          $('[data-kt-repeater="select2"]').select2({
            placeholder: "Pilih rekening belanja",
            allowClear: true
          });
        }
      });

      // AJAX load kelompok berdasarkan tipe (untuk edit)
      tipeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
          const tipe = this.value;
          const currentKelompokId = '{{ $standarHarga->id_kelompok_standar_harga }}';

          if (tipe) {
            kelompokSelect.disabled = true;
            kelompokSelect.innerHTML = '<option value="">Loading...</option>';

            $.ajax({
              url: "{{ route('kelompok_satuan_harga.get-by-tipe') }}",
              method: 'GET',
              data: {
                tipe: tipe
              },
              success: function(response) {
                if (response.success && response.data) {
                  kelompokSelect.innerHTML = '<option value="">Pilih Kelompok</option>';

                  response.data.forEach(function(kelompok) {
                    const option = document.createElement('option');
                    option.value = kelompok.id;
                    option.textContent = kelompok.kode_kelompok_standar_harga + ' - ' + kelompok.nama_kelompok_standar_harga;

                    // Keep current selection if available
                    if (kelompok.id == currentKelompokId) {
                      option.selected = true;
                    }

                    kelompokSelect.appendChild(option);
                  });

                  kelompokSelect.disabled = false;

                  if (response.data.length === 0) {
                    kelompokSelect.innerHTML = '<option value="">Tidak ada kelompok untuk tipe ' + tipe + '</option>';

                    Swal.fire({
                      icon: 'info',
                      title: 'Tidak ada kelompok',
                      text: 'Belum ada kelompok standar harga untuk tipe ' + tipe,
                      confirmButtonText: 'OK',
                      buttonsStyling: false,
                      customClass: {
                        confirmButton: "btn btn-primary"
                      }
                    });
                  }
                }
              },
              error: function() {
                kelompokSelect.innerHTML = '<option value="">Gagal memuat data</option>';

                Swal.fire({
                  icon: 'error',
                  title: 'Gagal',
                  text: 'Gagal memuat data kelompok',
                  confirmButtonText: 'OK',
                  buttonsStyling: false,
                  customClass: {
                    confirmButton: "btn btn-primary"
                  }
                });
              }
            });
          }
        });
      });

      // Form validation
      if (editForm && updateButton) {
        editForm.addEventListener('submit', function(e) {
          // Check if at least one rekening is selected from repeater
          const rekeningSelects = document.querySelectorAll('[data-kt-repeater="select2"]');
          let hasValidRekening = false;

          rekeningSelects.forEach(select => {
            if (select.value && select.value !== '') {
              hasValidRekening = true;
            }
          });

          if (!hasValidRekening) {
            e.preventDefault();
            rekeningError?.classList.remove('d-none');

            Swal.fire({
              icon: 'error',
              title: 'Validasi gagal',
              text: 'Minimal satu rekening belanja harus dipilih!',
              confirmButtonText: 'OK',
              buttonsStyling: false,
              customClass: {
                confirmButton: "btn btn-primary"
              }
            });
            return;
          }

          updateButton.setAttribute('data-kt-indicator', 'on');
          updateButton.disabled = true;
        });
      }

      // SweetAlert2 Session Messages
      @if (session('success'))
        Swal.fire({
          icon: 'success',
          title: 'Berhasil',
          text: '{{ session('success') }}',
          confirmButtonText: 'OK',
          buttonsStyling: false,
          customClass: {
            confirmButton: "btn btn-primary"
          }
        });
      @endif

      @if (session('error'))
        Swal.fire({
          icon: 'error',
          title: 'Gagal',
          text: '{{ session('error') }}',
          confirmButtonText: 'OK',
          buttonsStyling: false,
          customClass: {
            confirmButton: "btn btn-primary"
          }
        });
      @endif
    });
  </script>
@endsection
