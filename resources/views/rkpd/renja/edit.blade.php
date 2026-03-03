@extends('layouts.master')

@section('content')
  <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
      <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
          <h1 class="page-heading text-dark fw-bold fs-3 m-0">Edit Sub Kegiatan Belanja</h1>
          <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
            <li class="breadcrumb-item text-muted">
              <a href="{{ url('/home') }}" class="text-muted text-hover-primary">Home</a>
            </li>
            <li class="breadcrumb-item">
              <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">Rkpd</li>
            <li class="breadcrumb-item">
              <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">
              <a href="{{ route('rkpd.renja.index') }}" class="text-muted text-hover-primary">Renja</a>
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

      <!-- Info Sub Kegiatan -->
      <div class="card mb-5">
        <div class="card-header">
          <h3 class="card-title">Informasi Sub Kegiatan</h3>
        </div>
        <div class="card-body">
          <table class="table table-sm table-borderless">
            <tr>
              <td width="200px" class="fw-bold">SKPD</td>
              <td>{{ $subKegiatan->kode_skpd }} - {{ $subKegiatan->nama_skpd }}</td>
            </tr>
            <tr>
              <td class="fw-bold">Bidang Urusan</td>
              <td>{{ $subKegiatan->kode_bidang_urusan }} - {{ $subKegiatan->nama_bidang_urusan }}</td>
            </tr>
            <tr>
              <td class="fw-bold">Program</td>
              <td>{{ $subKegiatan->kode_program }} - {{ $subKegiatan->nama_program }}</td>
            </tr>
            <tr>
              <td class="fw-bold">Kegiatan</td>
              <td>{{ $subKegiatan->kode_giat }} - {{ $subKegiatan->nama_giat }}</td>
            </tr>
            <tr>
              <td class="fw-bold">Sub Kegiatan</td>
              <td>{{ $subKegiatan->kode_sub_giat }} - {{ $subKegiatan->nama_sub_giat }}</td>
            </tr>
          </table>
        </div>
      </div>

      <!-- Form Edit -->
      <form action="{{ route('renja.update', $subKegiatan->id_sub_bl) }}" method="POST" id="form_edit_renja">
        @csrf
        @method('PUT')

        <input type="hidden" name="id_skpd" value="{{ $subKegiatan->id_skpd }}">
        <input type="hidden" name="id_sub_kegiatan" value="{{ $subKegiatan->id_sub_giat }}">

        <div class="card mb-5">
          <div class="card-header">
            <h3 class="card-title">Edit Data</h3>
          </div>
          <div class="card-body">

            <!-- Sumber Dana -->
            <div class="mb-7">
              <div class="d-flex justify-content-between align-items-center mb-5">
                <label class="fs-6 fw-semibold">Sumber Dana</label>
                <button type="button" class="btn btn-sm btn-light-primary" id="btn_add_sumber_dana">
                  <i class="ki-outline ki-plus fs-3"></i> Tambah Sumber Dana
                </button>
              </div>

              <div id="sumber_dana_container">
                @foreach ($sumberDana as $dana)
                  <div class="card card-bordered mb-5 sumber-dana-item" data-id="{{ $loop->iteration }}">
                    <div class="card-body">
                      <div class="d-flex justify-content-between align-items-center mb-5">
                        <h5 class="card-title m-0">
                          <i class="ki-outline ki-wallet fs-2 text-primary me-2"></i>
                          <span class="sumber-dana-number">Sumber Dana #{{ $loop->iteration }}</span>
                        </h5>
                        <button type="button" class="btn btn-icon btn-sm btn-light-danger btn-remove-sumber-dana">
                          <i class="ki-outline ki-trash fs-2"></i>
                        </button>
                      </div>

                      <div class="row g-5">
                        <div class="col-md-6">
                          <label class="required fs-6 fw-semibold mb-2">Pilih Sumber Dana</label>
                          <select class="form-select form-select-solid select-sumber-dana-{{ $loop->iteration }}"
                            name="sumber_dana[{{ $loop->iteration }}][id_sumber_dana]" data-selected="{{ $dana->iddana }}"
                            data-iteration="{{ $loop->iteration }}" required>
                            <option value="">Pilih Sumber Dana</option>

                            @php
                              // ✅ Cek dengan id_dana (bukan id)
                              $currentExists = $sumberdana->where('id_dana', $dana->iddana)->first();
                            @endphp

                            @if (!$currentExists && $dana->iddana)
                              <option value="{{ $dana->iddana }}" selected class="bg-warning">
                                {{ $dana->kodedana ?? 'N/A' }} - {{ $dana->namadana ?? 'Sumber Dana Tidak Ditemukan' }} ⚠️
                              </option>
                            @endif

                            @foreach ($sumberdana as $sd)
                              <option value="{{ $sd->id_dana }}" {{ (string) $dana->iddana === (string) $sd->id_dana ? 'selected' : '' }}
                                data-kode="{{ $sd->kode_dana }}">
                                {{ $sd->kode_dana }} - {{ $sd->nama_dana }}
                              </option>
                            @endforeach
                          </select>
                          @if (!$currentExists && $dana->iddana)
                            <small class="text-danger">
                              <i class="ki-outline ki-information fs-6"></i>
                              Sumber dana ini tidak ada di master data (ID: {{ $dana->iddana }})
                            </small>
                          @else
                            <small class="text-muted">Selected ID: {{ $dana->iddana }}</small>
                          @endif
                        </div>

                        <div class="col-md-6">
                          <label class="required fs-6 fw-semibold mb-2">Pagu</label>
                          <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" class="form-control form-control-solid input-pagu" name="sumber_dana[{{ $loop->iteration }}][pagu]"
                              value="{{ number_format($dana->pagudana, 0, ',', '.') }}" data-raw-value="{{ $dana->pagudana }}" required>
                          </div>
                        </div>
                      </div>

                      <div class="mt-5 p-4 bg-light-primary rounded">
                        <div class="d-flex justify-content-between align-items-center">
                          <span class="fw-bold text-gray-800">Pagu Sumber Dana #{{ $loop->iteration }}:</span>
                          <span class="fs-4 fw-bold text-primary pagu-display-{{ $loop->iteration }}">
                            Rp {{ number_format($dana->pagudana, 0, ',', '.') }}
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>

              <div id="no_sumber_dana_info" class="alert alert-light-warning d-none">
                <i class="ki-outline ki-information-5 fs-2hx text-warning me-4"></i>
                <div>
                  <h4 class="mb-1 text-warning">Belum ada sumber dana</h4>
                  <span>Klik tombol "+ Tambah Sumber Dana" untuk menambahkan</span>
                </div>
              </div>

              <div class="card bg-light-primary mt-5">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center">
                    <div>
                      <h5 class="mb-1">Total Pagu Keseluruhan</h5>
                      <span class="text-gray-600 fs-7">Jumlah dari semua sumber dana</span>
                    </div>
                    <h2 class="mb-0 text-primary" id="grand_total_pagu">Rp 0</h2>
                  </div>
                </div>
              </div>
            </div>

            <!-- Indikator -->
            @if ($indikator->count() > 0)
              <div class="separator separator-dashed my-7"></div>

              <div class="mb-7">
                <label class="fs-6 fw-semibold mb-5">Indikator Kinerja</label>

                @foreach ($indikator as $index => $ind)
                  <div class="row align-items-center mb-3">
                    <div class="col-md-5">
                      <div class="fw-semibold text-gray-800">{{ $ind->outputteks }}</div>
                      <input type="hidden" name="indikator[{{ $index }}][id_indikator]" value="{{ $ind->idoutputbl ?? 0 }}">
                      <input type="hidden" name="indikator[{{ $index }}][indikator_text]" value="{{ $ind->outputteks }}">
                      <input type="hidden" name="indikator[{{ $index }}][satuan]" value="{{ $ind->satuanoutput }}">
                    </div>
                    <div class="col-md-5">
                      <input type="text" class="form-control form-control-solid input-target" name="indikator[{{ $index }}][target]"
                        value="{{ number_format($ind->targetoutput, 0, ',', '.') }}" required />
                    </div>
                    <div class="col-md-2">
                      <div class="text-gray-600">{{ $ind->satuanoutput }}</div>
                    </div>
                  </div>
                @endforeach
              </div>
            @endif

            <div class="separator separator-dashed my-7"></div>

            <!-- Lokasi & Waktu -->
            <div class="row mb-7">
              <div class="col-md-6">
                <label class="fs-6 fw-semibold mb-2">Waktu Awal</label>
                <select class="form-select form-select-solid" name="waktu_awal" required>
                  <option value="">Pilih Waktu Awal</option>
                  @foreach ($bln as $bl)
                    <option value="{{ $bl->id }}" {{ $subKegiatan->waktu_awal == $bl->id ? 'selected' : '' }}>
                      {{ $bl->nama }}
                    </option>
                  @endforeach
                </select>
              </div>

              <div class="col-md-6">
                <label class="fs-6 fw-semibold mb-2">Waktu Akhir</label>
                <select class="form-select form-select-solid" name="waktu_akhir">
                  <option value="">Pilih Waktu Akhir</option>
                  @foreach ($bln as $bl)
                    <option value="{{ $bl->id }}" {{ $subKegiatan->waktu_akhir == $bl->id ? 'selected' : '' }}>
                      {{ $bl->nama }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="mb-7">
              <label class="fs-6 fw-semibold mb-2">Anggaran N+1 Sub Kegiatan</label>
              <div class="input-group">
                <span class="input-group-text">Rp</span>
                <input type="text" class="form-control form-control-solid input-pagu-n1" name="pagu_n_depan"
                  value="{{ number_format($subKegiatan->pagu_n_depan ?? 0, 0, ',', '.') }}">
              </div>
              <small class="text-muted">
                <i class="ki-outline ki-information fs-6"></i>
                Anggaran untuk tahun berikutnya (tidak termasuk dalam total pagu)
              </small>
            </div>

          </div>

          <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('rkpd.renja.index') }}" class="btn btn-light">
              <i class="ki-outline ki-arrow-left fs-3"></i> Kembali
            </a>
            <button type="submit" class="btn btn-primary" id="btn_submit">
              <span class="indicator-label">
                <i class="ki-outline ki-check fs-3"></i> Simpan Perubahan
              </span>
              <span class="indicator-progress">
                Menyimpan... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
              </span>
            </button>
          </div>
        </div>
      </form>

    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      let sumberDanaCounter = {{ $sumberDana->count() }};
      const sumberDanaList = @json($sumberdana);

      // ✅ Initialize Select2 untuk SEMUA existing items
      initializeExistingSelect2();

      // Initialize currency format
      initializeCurrencyFormat();
      updateTotalPagu();

      // ✅ Function untuk initialize Select2 yang sudah ada
      function initializeExistingSelect2() {
        // Debug: tampilkan semua select yang ada
        console.log('=== INITIALIZING EXISTING SELECT2 ===');
        console.log('Total sumber dana items: ' + $('.sumber-dana-item').length);
        console.log('Total select elements: ' + $('[class*="select-sumber-dana-"]').length);

        // Delay sedikit untuk memastikan DOM ready
        setTimeout(function() {
          $('[class*="select-sumber-dana-"]').each(function(index) {
            const $select = $(this);
            const iteration = $select.data('iteration');
            const selectedValue = $select.data('selected');
            const currentHtmlValue = $select.find('option[selected]').val();
            const optionExists = $select.find('option[value="' + selectedValue + '"]').length > 0;

            console.log('---');
            console.log('Processing select #' + (index + 1));
            console.log('Iteration attr: ' + iteration);
            console.log('Data-selected: ' + selectedValue);
            console.log('HTML selected option: ' + currentHtmlValue);
            console.log('Option exists in dropdown: ' + (optionExists ? 'YES' : 'NO'));
            console.log('Total options: ' + $select.find('option').length);

            // Destroy dulu jika sudah ada
            if ($select.hasClass("select2-hidden-accessible")) {
              $select.select2('destroy');
            }

            // Initialize Select2
            $select.select2({
              placeholder: "Pilih Sumber Dana",
              allowClear: true,
              width: '100%',
              templateResult: formatSumberDana,
              templateSelection: formatSumberDanaSelection
            });

            // Set nilai yang sudah dipilih HANYA jika option ada
            if (selectedValue && optionExists) {
              $select.val(selectedValue).trigger('change.select2');

              // Verify
              const finalValue = $select.val();
              console.log('Final value after set: ' + finalValue);
              console.log('Match: ' + (finalValue == selectedValue ? 'YES ✓' : 'NO ✗'));
            } else if (selectedValue && !optionExists) {
              console.warn('⚠️ Cannot set value ' + selectedValue + ' - option not found in master data');
              // Tetap set value agar form bisa submit dengan warning
              $select.val(selectedValue).trigger('change.select2');
            }
          });

          console.log('=== ALL SELECT2 INITIALIZED ===');
        }, 100);
      }

      // ✅ Format untuk menampilkan warning pada option yang tidak valid
      function formatSumberDana(option) {
        if (!option.id) {
          return option.text;
        }

        var $option = $(option.element);
        if ($option.hasClass('bg-warning')) {
          return $('<span><i class="ki-outline ki-information text-warning me-2"></i>' + option.text + '</span>');
        }

        return option.text;
      }

      function formatSumberDanaSelection(option) {
        if (!option.id) {
          return option.text;
        }

        var $option = $(option.element);
        if ($option.hasClass('bg-warning')) {
          return $('<span class="text-warning">' + option.text + '</span>');
        }

        return option.text;
      }

      // Add sumber dana
      $('#btn_add_sumber_dana').on('click', function() {
        sumberDanaCounter++;
        addSumberDanaForm(sumberDanaCounter);
      });

      // Remove sumber dana
      $(document).on('click', '.btn-remove-sumber-dana', function() {
        const count = $('.sumber-dana-item').length;

        if (count <= 1) {
          Swal.fire({
            icon: 'warning',
            title: 'Perhatian',
            text: 'Minimal harus ada 1 sumber dana!',
            confirmButtonText: 'OK',
            buttonsStyling: false,
            customClass: {
              confirmButton: "btn btn-primary"
            }
          });
          return;
        }

        const card = $(this).closest('.sumber-dana-item');

        Swal.fire({
          title: 'Hapus Sumber Dana?',
          text: "Data sumber dana ini akan dihapus!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Ya, Hapus!',
          cancelButtonText: 'Batal',
          buttonsStyling: false,
          customClass: {
            confirmButton: "btn btn-danger",
            cancelButton: "btn btn-light"
          }
        }).then((result) => {
          if (result.isConfirmed) {
            card.fadeOut(300, function() {
              $(this).remove();
              reorderSumberDana();
              updateTotalPagu();
            });
          }
        });
      });

      function addSumberDanaForm(id) {
        let options = '<option value="">Pilih Sumber Dana</option>';
        sumberDanaList.forEach(item => {
          // ✅ Gunakan id_dana sebagai value
          options += `<option value="${item.id_dana}">${item.kode_dana} - ${item.nama_dana}</option>`;
        });

        const html = `
        <div class="card card-bordered mb-5 sumber-dana-item" data-id="${id}">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <h5 class="card-title m-0">
                        <i class="ki-outline ki-wallet fs-2 text-primary me-2"></i>
                        <span class="sumber-dana-number">Sumber Dana #${id}</span>
                    </h5>
                    <button type="button" class="btn btn-icon btn-sm btn-light-danger btn-remove-sumber-dana">
                        <i class="ki-outline ki-trash fs-2"></i>
                    </button>
                </div>
                <div class="row g-5">
                    <div class="col-md-6">
                        <label class="required fs-6 fw-semibold mb-2">Pilih Sumber Dana</label>
                        <select class="form-select form-select-solid select-sumber-dana-new-${id}" 
                                name="sumber_dana[${id}][id_sumber_dana]" 
                                required>
                            ${options}
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="required fs-6 fw-semibold mb-2">Pagu</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" class="form-control form-control-solid input-pagu" 
                                   name="sumber_dana[${id}][pagu]" placeholder="0" required>
                        </div>
                    </div>
                </div>
                <div class="mt-5 p-4 bg-light-primary rounded">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-gray-800">Pagu Sumber Dana #${id}:</span>
                        <span class="fs-4 fw-bold text-primary pagu-display-${id}">Rp 0</span>
                    </div>
                </div>
            </div>
        </div>`;

        $('#sumber_dana_container').append(html);

        // ✅ Initialize Select2 untuk item baru
        $(`.select-sumber-dana-new-${id}`).select2({
          placeholder: "Pilih Sumber Dana",
          allowClear: true
        });

        initializeCurrencyFormat();
        reorderSumberDana();
      }

      function reorderSumberDana() {
        $('.sumber-dana-item').each(function(index) {
          const newNumber = index + 1;
          $(this).find('.sumber-dana-number').text(`Sumber Dana #${newNumber}`);
        });
      }

      function initializeCurrencyFormat() {
        // ✅ Handler untuk input pagu sumber dana (yang dihitung dalam total)
        $('.input-pagu').off('input').on('input', function() {
          let value = $(this).val().replace(/[^\d]/g, '');
          if (value) {
            const formatted = new Intl.NumberFormat('id-ID').format(value);
            $(this).val(formatted);

            // Update display pagu per sumber dana
            const card = $(this).closest('.sumber-dana-item');
            const cardId = card.data('id');
            const display = card.find('.pagu-display-' + cardId);

            if (display.length) {
              display.text('Rp ' + formatted);
            } else {
              // Fallback jika class tidak ada ID
              card.find('[class*="pagu-display"]').text('Rp ' + formatted);
            }

            // Update total pagu keseluruhan
            updateTotalPagu();
          } else {
            $(this).val('');
            const card = $(this).closest('.sumber-dana-item');
            card.find('[class*="pagu-display"]').text('Rp 0');
            updateTotalPagu();
          }
        });

        // ✅ Handler untuk input pagu N+1 (TIDAK dihitung dalam total)
        $('.input-pagu-n1').off('input').on('input', function() {
          let value = $(this).val().replace(/[^\d]/g, '');
          if (value) {
            const formatted = new Intl.NumberFormat('id-ID').format(value);
            $(this).val(formatted);
          } else {
            $(this).val('');
          }
          // TIDAK memanggil updateTotalPagu()
        });

        // ✅ Handler untuk input target indikator
        $('.input-target').off('input').on('input', function() {
          let value = $(this).val().replace(/[^\d]/g, '');
          if (value) {
            const formatted = new Intl.NumberFormat('id-ID').format(value);
            $(this).val(formatted);
          }
        });
      }

      function updateTotalPagu() {
        let total = 0;

        // ✅ HANYA hitung dari input-pagu (sumber dana), TIDAK termasuk input-pagu-n1
        $('.input-pagu').each(function() {
          const value = $(this).val().replace(/[^\d]/g, '');
          total += parseInt(value) || 0;
        });

        const formattedTotal = new Intl.NumberFormat('id-ID').format(total);
        $('#grand_total_pagu').text('Rp ' + formattedTotal);

        console.log('Total Pagu Updated: Rp ' + formattedTotal + ' (from sumber dana only)');
      }

      // Form submit
      $('#form_edit_renja').on('submit', function(e) {
        const count = $('.sumber-dana-item').length;

        if (count === 0) {
          e.preventDefault();
          Swal.fire({
            icon: 'error',
            title: 'Validasi Gagal',
            text: 'Minimal harus ada 1 sumber dana!',
            confirmButtonText: 'OK',
            buttonsStyling: false,
            customClass: {
              confirmButton: "btn btn-primary"
            }
          });
          return;
        }

        // ✅ Remove formatting dari SEMUA input numeric (sumber dana + pagu N+1)
        $('.input-pagu, .input-pagu-n1, .input-target').each(function() {
          const plainValue = $(this).val().replace(/[^\d]/g, '');
          $(this).val(plainValue);
        });

        $('#btn_submit').attr('data-kt-indicator', 'on').prop('disabled', true);
      });
    });
  </script>
@endsection
