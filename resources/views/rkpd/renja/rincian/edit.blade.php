@extends('layouts.master')

@section('content')
  <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
      <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
          <h1 class="page-heading text-dark fw-bold fs-3 m-0">Edit Rincian Belanja</h1>
          <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
            <li class="breadcrumb-item text-muted">
              <a href="{{ url('/home') }}" class="text-muted text-hover-primary">Home</a>
            </li>
            <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
            <li class="breadcrumb-item text-muted">RKPD</li>
            <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
            <li class="breadcrumb-item text-muted">
              <a href="{{ route('rkpd.renja.index') }}" class="text-muted text-hover-primary">Renja</a>
            </li>
            <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
            <li class="breadcrumb-item text-muted">
              <a href="{{ route('renja.rincian', $rincian->idsubbl) }}" class="text-muted text-hover-primary">Rincian Belanja</a>
            </li>
            <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
            <li class="breadcrumb-item text-muted">Edit</li>
          </ul>
        </div>
        <div class="d-flex align-items-center gap-2">
          <a href="{{ route('renja.rincian', $rincian->idsubbl) }}" class="btn btn-sm btn-light">
            <i class="ki-outline ki-arrow-left fs-3"></i>
            Kembali
          </a>
        </div>
      </div>
    </div>
  </div>

  <div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-fluid">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Form Edit Rincian Belanja</h3>
        </div>
        <div class="card-body">
          <form id="form_edit_rincian" method="POST" action="{{ route('rincian.update', $rincian->id) }}">
            @csrf
            @method('PUT')

            <!-- Hidden Fields - Data yang tidak bisa diubah -->
            <input type="hidden" name="id_rinci_sub_bl" value="{{ $subKegiatan->id }}">
            <input type="hidden" name="tahun_anggaran" value="{{ $rincian->tahun_anggaran ?? 2025 }}">
            <input type="hidden" name="jenis_bl" value="{{ $rincian->jenis_bl }}">
            <input type="hidden" name="kode_rekening" value="{{ $rincian->kode_akun }}">
            <input type="hidden" name="nama_rekening" value="{{ $rincian->nama_akun }}">
            <input type="hidden" name="tipe_paket" value="{{ $rincian->is_paket }}">
            <input type="hidden" name="id_paket_belanja" value="{{ $rincian->idsubtitle }}">
            <input type="hidden" name="kategori_belanja" value="{{ $rincian->ket_bl_teks }}">

            <!-- INFORMASI PAKET & REKENING (READ-ONLY) -->
            <div class="card mb-5 shadow-sm bg-light-primary">
              <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                  <span class="card-label fw-bold text-dark">
                    <i class="ki-outline ki-information-4 fs-2 text-primary me-2"></i>
                    Informasi Paket & Rekening
                  </span>
                  <span class="text-muted mt-1 fw-semibold fs-7">Data ini tidak dapat diubah</span>
                </h3>
              </div>
              <div class="card-body py-3">
                <div class="row g-5">
                  <!-- Tipe Paket -->
                  <div class="col-md-6">
                    <div class="d-flex align-items-center mb-2">
                      <i class="ki-outline ki-package fs-3 text-primary me-3"></i>
                      <div class="flex-grow-1">
                        <div class="text-gray-600 fs-7 fw-semibold">Tipe Paket</div>
                        <div class="text-gray-800 fs-6 fw-bold">
                          {{ $rincian->is_paket == 1 ? 'Pemaketan Kerja' : 'Pengelompokan Belanja' }}
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Uraian Paket -->
                  @if ($rincian->subtitle_teks)
                    <div class="col-md-6">
                      <div class="d-flex align-items-center mb-2">
                        <i class="ki-outline ki-notepad fs-3 text-primary me-3"></i>
                        <div class="flex-grow-1">
                          <div class="text-gray-600 fs-7 fw-semibold">Paket Belanja</div>
                          <div class="text-gray-800 fs-6 fw-bold">{{ $rincian->subtitle_teks }}</div>
                        </div>
                      </div>
                    </div>
                  @endif

                  <!-- Kategori Belanja -->
                  @if ($rincian->ket_bl_teks)
                    <div class="col-md-6">
                      <div class="d-flex align-items-center mb-2">
                        <i class="ki-outline ki-tag fs-3 text-primary me-3"></i>
                        <div class="flex-grow-1">
                          <div class="text-gray-600 fs-7 fw-semibold">Kategori Belanja</div>
                          <div class="text-gray-800 fs-6 fw-bold">
                            {{ preg_replace('/^\[\-\]\s*/', '', $rincian->ket_bl_teks) }}
                          </div>
                        </div>
                      </div>
                    </div>
                  @endif

                  <!-- Objek Belanja -->
                  <div class="col-md-6">
                    <div class="d-flex align-items-center mb-2">
                      <i class="ki-outline ki-category fs-3 text-primary me-3"></i>
                      <div class="flex-grow-1">
                        <div class="text-gray-600 fs-7 fw-semibold">Objek Belanja</div>
                        <div class="text-gray-800 fs-6 fw-bold">{{ $rincian->jenis_bl }}</div>
                      </div>
                    </div>
                  </div>

                  <!-- Rekening -->
                  <div class="col-md-12">
                    <div class="d-flex align-items-center mb-2">
                      <i class="ki-outline ki-wallet fs-3 text-primary me-3"></i>
                      <div class="flex-grow-1">
                        <div class="text-gray-600 fs-7 fw-semibold">Rekening Belanja</div>
                        <div class="text-gray-800 fs-6 fw-bold">
                          {{ $rincian->kode_akun }} - {{ $rincian->nama_akun }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- SECTION: DETAIL RINCIAN (EDITABLE) -->
            <div class="card mb-5 shadow-sm">
              <div class="card-header bg-light-info">
                <h3 class="card-title text-info">
                  <i class="ki-outline ki-document fs-3 me-2"></i>
                  Detail Rincian Belanja
                </h3>
              </div>
              <div class="card-body">

                <!-- Jenis Standar Harga -->
                <div class="mb-5">
                  <label class="form-label fw-bold">Jenis Standar Harga</label>
                  <select class="form-select form-select-solid" id="select_jenis_standar_harga" name="jenis_standar_harga">
                    <option value="">Pilih Standar Harga...</option>
                    <option value="1" {{ old('jenis_standar_harga', $rincian->jenis_standar_harga ?? '') == '1' ? 'selected' : '' }}>
                      SSH (Standar Satuan Harga)
                    </option>
                    <option value="2" {{ old('jenis_standar_harga', $rincian->jenis_standar_harga ?? '') == '2' ? 'selected' : '' }}>
                      SBU (Standar Biaya Umum)
                    </option>
                    <option value="3" {{ old('jenis_standar_harga', $rincian->jenis_standar_harga ?? '') == '3' ? 'selected' : '' }}>
                      HSPK (Harga Satuan Pokok Kegiatan)
                    </option>
                    <option value="4" {{ old('jenis_standar_harga', $rincian->jenis_standar_harga ?? '') == '4' ? 'selected' : '' }}>
                      ASB (Analisa Standar Belanja)
                    </option>
                  </select>
                </div>

                <!-- Komponen (Uraian) -->
                <div class="mb-5">
                  <label class="required form-label fw-bold">Komponen</label>

                  <!-- Wrapper untuk toggle antara textarea manual dan select SSH -->
                  <div id="wrapper_komponen_manual">
                    <textarea class="form-control form-control-solid" id="textarea_uraian" name="uraian" rows="2"
                      placeholder="Masukkan uraian/nama komponen..." required>{{ old('uraian', $rincian->nama_komponen ?? $rincian->spek) }}</textarea>
                    <div class="form-text">
                      Atau <a href="#" id="btn_pilih_dari_ssh" class="text-primary">Pilih dari SSH Database</a>
                    </div>
                  </div>

                  <div id="wrapper_komponen_ssh" style="display: none;">
                    <select class="form-select form-select-solid" id="select_komponen_ssh" style="width: 100%;">
                      <option value="">Ketik untuk mencari komponen...</option>
                    </select>
                    <div class="form-text">
                      <a href="#" id="btn_input_manual" class="text-danger">Kembali ke input manual</a>
                    </div>
                  </div>

                  <input type="hidden" id="id_standar_harga_selected" name="id_standar_harga"
                    value="{{ old('id_standar_harga', $rincian->id_standar_harga ?? '') }}">
                  @error('uraian')
                    <div class="text-danger mt-1">{{ $message }}</div>
                  @enderror
                </div>

                <!-- TKDN -->
                <div class="mb-5">
                  <label class="form-label fw-bold">TKDN</label>
                  <input type="text" class="form-control form-control-solid bg-light" id="input_tkdn" name="tkdn" placeholder="Contoh: 40%"
                    value="{{ old('tkdn', $rincian->tkdn ?? '') }}" readonly>
                  <div class="form-text">Tingkat Komponen Dalam Negeri (otomatis dari SSH)</div>
                </div>

                <!-- Spesifikasi Komponen -->
                <div class="mb-5">
                  <label class="form-label fw-bold">Spesifikasi Komponen</label>
                  <textarea class="form-control form-control-solid bg-light" id="textarea_spek" name="spesifikasi_komponen" rows="3"
                    placeholder="Masukkan spesifikasi detail komponen..." readonly>{{ old('spesifikasi_komponen', $rincian->spek_komponen ?? '') }}</textarea>
                </div>

                <div class="separator separator-dashed my-7"></div>

                <!-- Koefisien (Perkalian) -->
                <div class="mb-5">
                  <label class="form-label fw-bold fs-5 text-primary">
                    <i class="ki-outline ki-calculator fs-3 me-2"></i>
                    Koefisien (Perkalian)
                  </label>
                  <div class="alert alert-light-primary d-flex align-items-center p-3 mb-4">
                    <i class="ki-outline ki-information-5 fs-2x text-primary me-3"></i>
                    <div class="text-gray-700">
                      <strong>Contoh:</strong> 12 Bulan × 20 Orang = 240 (Volume otomatis dihitung)
                    </div>
                  </div>

                  <div id="koefisien_container">
                    <?php
                    // Get koefisien data dari database
                    $koefisienList = [];

                    // Cek volum1 sampai volum4 dan sat1 sampai sat4
                    for ($i = 1; $i <= 4; $i++) {
                        $volumField = 'volum'.$i;
                        $satField = 'sat'.$i;

                        // Cek apakah volume terisi (tidak null dan tidak 0)
                        if (isset($rincian->$volumField) && $rincian->$volumField !== null && $rincian->$volumField != 0) {
                            $koefisienList[] = [
                                'volume' => $rincian->$volumField,
                                'satuan' => $rincian->$satField ?? '',
                            ];
                        }
                    }

                    // Jika tidak ada koefisien, tampilkan 1 row kosong
                    if (empty($koefisienList)) {
                        $koefisienList = [
                            [
                                'volume' => '',
                                'satuan' => '',
                            ],
                        ];
                    }
                    ?>

                    @foreach ($koefisienList as $index => $koef)
                      <div class="row mb-3 koefisien-row">
                        <div class="col-md-1 d-flex align-items-center justify-content-center">
                          <span class="badge badge-light-primary fs-6">{{ $index + 1 }}</span>
                        </div>
                        <div class="col-md-5">
                          <input type="number" step="0.01" class="form-control form-control-solid koefisien-input" name="koefisien[]"
                            placeholder="Nilai koefisien {{ $index + 1 }}" value="{{ old('koefisien.' . $index, $koef['volume']) }}">
                        </div>
                        <div class="col-md-5">
                          <input type="text" class="form-control form-control-solid" name="satuan_koefisien[]"
                            placeholder="Satuan (Bulan, Orang, Unit, dll)" value="{{ old('satuan_koefisien.' . $index, $koef['satuan']) }}">
                        </div>
                        <div class="col-md-1 d-flex align-items-center">
                          <button type="button" class="btn btn-icon btn-sm btn-light-danger btn-remove-koef"
                            style="display: {{ $index > 0 ? 'block' : 'none' }};">
                            <i class="ki-outline ki-cross fs-3"></i>
                          </button>
                        </div>
                      </div>
                    @endforeach
                  </div>

                  <button type="button" class="btn btn-sm btn-light-primary" id="btn_add_koefisien">
                    <i class="ki-outline ki-plus fs-4"></i>
                    Tambah Koefisien
                  </button>
                </div>

                <div class="separator separator-dashed my-7"></div>

                <!-- Volume & Satuan -->
                <div class="row mb-5">
                  <div class="col-md-6">
                    <label class="required form-label fw-bold">Volume (Hasil Perkalian)</label>
                    <input type="text" class="form-control form-control-solid bg-light-success" name="volume" id="input_volume" placeholder="0"
                      value="{{ old('volume', $rincian->volume) }}" readonly required>
                    <div class="form-text text-success fw-bold">Otomatis dihitung dari koefisien</div>
                    @error('volume')
                      <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="col-md-6">
                    <label class="required form-label fw-bold">Satuan</label>
                    <input type="text" class="form-control form-control-solid bg-light" id="input_satuan" name="satuan"
                      placeholder="Paket, Unit, Bulan, dll" value="{{ old('satuan', $rincian->satuan) }}" readonly required>
                    <div class="form-text">Otomatis dari SSH (bisa diubah manual)</div>
                    @error('satuan')
                      <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                  </div>
                </div>

                <!-- Harga Satuan -->
                <div class="mb-5">
                  <label class="required form-label fw-bold">Harga Satuan</label>
                  <input type="text" class="form-control form-control-solid input-currency bg-light" id="input_harga_satuan" name="harga_satuan"
                    placeholder="0" value="{{ old('harga_satuan', $rincian->harga_satuan) }}" readonly required>
                  <div class="form-text">Otomatis dari SSH (bisa diubah manual)</div>
                  @error('harga_satuan')
                    <div class="text-danger mt-1">{{ $message }}</div>
                  @enderror
                </div>

              </div>
            </div>

            <!-- TOTAL BELANJA -->
            <div class="card shadow-sm mb-5">
              <div class="card-body bg-light-success">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <span class="fw-bold fs-6 text-gray-800">Total Belanja:</span>
                    <div class="text-muted fs-7">Volume × Harga Satuan</div>
                  </div>
                  <span class="fw-bold fs-2 text-success" id="total_display">Rp
                    {{ number_format(old('harga_satuan', $rincian->harga_satuan) * old('volume', $rincian->volume), 0, ',', '.') }}</span>
                </div>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-end gap-2 pt-5 border-top">
              <a href="{{ route('renja.rincian', $rincian->idsubbl) }}" class="btn btn-light">
                <i class="ki-outline ki-cross fs-2"></i>
                Batal
              </a>
              <button type="submit" class="btn btn-primary" id="btn_submit">
                <span class="indicator-label">
                  <i class="ki-outline ki-check fs-2"></i>
                  Simpan Perubahan
                </span>
                <span class="indicator-progress" style="display: none;">
                  Menyimpan... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    $(document).ready(function() {

      // INITIALIZE SELECT2
      $('#select_jenis_standar_harga').select2({
        placeholder: 'Pilih Standar Harga...',
        allowClear: true,
        width: '100%'
      });

      // ============================================================
      // SSH KOMPONEN
      // ============================================================

      // TOGGLE KOMPONEN: Manual vs SSH
      $('#btn_pilih_dari_ssh').on('click', function(e) {
        e.preventDefault();
        $('#wrapper_komponen_manual').hide();
        $('#wrapper_komponen_ssh').show();

        // Initialize Select2 for SSH if not already done
        if (!$('#select_komponen_ssh').hasClass('select2-hidden-accessible')) {
          $('#select_komponen_ssh').select2({
            placeholder: 'Ketik untuk mencari komponen...',
            allowClear: true,
            width: '100%',
            ajax: {
              url: '{{ route('rincian.search-komponen') }}',
              dataType: 'json',
              delay: 250,
              data: function(params) {
                return {
                  keyword: params.term,
                  jenis_bl: '{{ $rincian->jenis_bl }}'
                };
              },
              processResults: function(response) {
                if (response.success && response.data) {
                  return {
                    results: response.data.map(function(item) {
                      return {
                        id: item.id,
                        text: item.nama_komponen + ' - ' + item.spek_komponen,
                        data: item
                      };
                    })
                  };
                }
                return {
                  results: []
                };
              },
              cache: true
            },
            minimumInputLength: 3
          });
        }

        $('#select_komponen_ssh').select2('open');
      });

      $('#btn_input_manual').on('click', function(e) {
        e.preventDefault();
        $('#wrapper_komponen_ssh').hide();
        $('#wrapper_komponen_manual').show();
        $('#select_komponen_ssh').val(null).trigger('change');
      });

      // SSH KOMPONEN SELECTED
      $('#select_komponen_ssh').on('select2:select', function(e) {
        const data = e.params.data.data;

        // Fill form with SSH data
        $('#textarea_uraian').val(data.nama_komponen || '');
        $('#id_standar_harga_selected').val(data.id);
        $('#input_tkdn').val(data.tkdn || '');
        $('#textarea_spek').val(data.spek_komponen || '');
        $('#input_satuan').val(data.satuan || '');
        $('#input_harga_satuan').val(data.harga_satuan || 0);

        // Allow editing satuan and harga
        $('#input_satuan').prop('readonly', false);
        $('#input_harga_satuan').prop('readonly', false);

        // Calculate total
        calculateVolume();
        calculateTotal();

        toastr.success('Komponen SSH berhasil dipilih', 'Berhasil');
      });

      // ============================================================
      // KOEFISIEN
      // ============================================================

      // Hitung jumlah koefisien yang sudah ada dari database
      let koefisienCount = $('#koefisien_container .koefisien-row').length;
      console.log('Koefisien count from database:', koefisienCount);

      // ADD ROW
      $('#btn_add_koefisien').on('click', function() {
        if (koefisienCount >= 4) {
          toastr.warning('Maksimal 4 koefisien', 'Peringatan');
          return;
        }

        koefisienCount++;

        const newRow = `
          <div class="row mb-3 koefisien-row">
            <div class="col-md-1 d-flex align-items-center justify-content-center">
              <span class="badge badge-light-primary fs-6">${koefisienCount}</span>
            </div>
            <div class="col-md-5">
              <input type="number" step="0.01" class="form-control form-control-solid koefisien-input" 
                name="koefisien[]" placeholder="Nilai koefisien ${koefisienCount}">
            </div>
            <div class="col-md-5">
              <input type="text" class="form-control form-control-solid" name="satuan_koefisien[]"
                placeholder="Satuan (Bulan, Orang, Unit, dll)">
            </div>
            <div class="col-md-1 d-flex align-items-center">
              <button type="button" class="btn btn-icon btn-sm btn-light-danger btn-remove-koef">
                <i class="ki-outline ki-cross fs-3"></i>
              </button>
            </div>
          </div>
        `;

        $('#koefisien_container').append(newRow);

        if (koefisienCount > 1) {
          $('.btn-remove-koef').show();
        }

        toastr.success(`Koefisien ${koefisienCount} ditambahkan`, 'Berhasil');
      });

      // REMOVE ROW
      $(document).on('click', '.btn-remove-koef', function() {
        $(this).closest('.koefisien-row').remove();
        koefisienCount--;

        // Renumber badges
        $('#koefisien_container .koefisien-row').each(function(index) {
          $(this).find('.badge').text(index + 1);
          $(this).find('.koefisien-input').attr('placeholder', 'Nilai koefisien ' + (index + 1));
        });

        if (koefisienCount <= 1) {
          $('.btn-remove-koef').hide();
        }

        calculateVolume();
        toastr.info('Koefisien dihapus', 'Info');
      });

      // CALCULATE VOLUME FROM KOEFISIEN
      $(document).on('input', '.koefisien-input', function() {
        calculateVolume();
      });

      function calculateVolume() {
        let volume = 1;
        let hasValue = false;

        $('.koefisien-input').each(function() {
          const val = parseFloat($(this).val());
          if (!isNaN(val) && val > 0) {
            volume *= val;
            hasValue = true;
          }
        });

        if (hasValue) {
          $('#input_volume').val(volume.toFixed(2));
        } else {
          $('#input_volume').val('0');
        }

        calculateTotal();
      }

      // ============================================================
      // CALCULATE TOTAL
      // ============================================================

      function calculateTotal() {
        const volume = parseFloat($('#input_volume').val()) || 0;
        const hargaSatuan = parseFloat($('#input_harga_satuan').val().replace(/[^\d]/g, '')) || 0;
        const total = volume * hargaSatuan;

        $('#total_display').text('Rp ' + number_format(total));
      }

      $('#input_volume, #input_harga_satuan').on('input', calculateTotal);

      // HELPER: NUMBER FORMAT
      function number_format(number) {
        return new Intl.NumberFormat('id-ID').format(number);
      }

      // ============================================================
      // FORM SUBMIT
      // ============================================================

      $('#form_edit_rincian').on('submit', function(e) {
        e.preventDefault();

        const btn = $('#btn_submit');
        btn.find('.indicator-label').hide();
        btn.find('.indicator-progress').show();
        btn.prop('disabled', true);

        const formData = $(this).serialize();

        $.ajax({
          url: $(this).attr('action'),
          type: 'POST',
          data: formData,
          success: function(response) {
            if (response.success) {
              toastr.success(response.message, 'Berhasil');
              setTimeout(function() {
                window.location.href = '{{ route('renja.rincian', $rincian->idsubbl) }}';
              }, 1000);
            } else {
              toastr.error(response.message, 'Error');
              btn.find('.indicator-label').show();
              btn.find('.indicator-progress').hide();
              btn.prop('disabled', false);
            }
          },
          error: function(xhr) {
            const message = xhr.responseJSON?.message || 'Terjadi kesalahan saat menyimpan';
            toastr.error(message, 'Error');

            // Show validation errors
            if (xhr.responseJSON?.errors) {
              $.each(xhr.responseJSON.errors, function(key, value) {
                toastr.error(value[0], 'Error Validasi');
              });
            }

            btn.find('.indicator-label').show();
            btn.find('.indicator-progress').hide();
            btn.prop('disabled', false);
          }
        });
      });

      // ============================================================
      // CALCULATE VOLUME ON PAGE LOAD
      // ============================================================

      setTimeout(function() {
        calculateVolume();
      }, 300);
    });
  </script>
@endsection
