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

              <div class="separator my-7"></div>

              {{-- Rekening Belanja Management --}}
              <div class="card-body">
                <h5 class="fw-bold mb-5">
                  <i class="ki-outline ki-wallet fs-2 text-primary me-2"></i>
                  Manajemen Rekening Belanja
                </h5>

                {{-- Existing Rekening --}}
                @if ($ssh->rekeningBelanja->count() > 0)
                  <div class="mb-7">
                    <h6 class="fw-bold mb-3 text-gray-800">
                      <i class="ki-outline ki-check-circle fs-3 text-success me-2"></i>
                      Rekening Terhubung ({{ $ssh->rekeningBelanja->count() }})
                    </h6>
                    <div class="table-responsive">
                      <table class="table table-row-bordered table-striped table-hover align-middle">
                        <thead class="bg-light">
                          <tr class="fw-bold fs-6 text-gray-800">
                            <th width="15%">Kode Akun</th>
                            <th width="45%">Nama Akun</th>
                            <th width="12%" class="text-center">Tahun</th>
                            <th width="15%" class="text-center">Status</th>
                            <th width="13%" class="text-end">Aksi</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($ssh->rekeningBelanja as $rekening)
                            <tr>
                              <td class="fw-bold text-primary">{{ $rekening->kode_akun }}</td>
                              <td>{{ $rekening->nama_akun }}</td>
                              <td class="text-center">
                                <span class="badge badge-light-dark">{{ $rekening->tahun_anggaran }}</span>
                              </td>
                              <td class="text-center">
                                <div class="form-check form-switch form-check-custom form-check-solid justify-content-center">
                                  <input class="form-check-input toggle-rekening-active" type="checkbox" {{ $rekening->active ? 'checked' : '' }}
                                    data-id="{{ $rekening->id }}" data-ssh-id="{{ $ssh->id_standar_harga }}" />
                                </div>
                              </td>
                              <td class="text-end">
                                <button type="button" class="btn btn-sm btn-light-danger btn-remove-rekening" data-id="{{ $rekening->id }}"
                                  data-nama="{{ $rekening->nama_akun }}">
                                  <i class="ki-outline ki-trash fs-4"></i> Hapus
                                </button>
                              </td>
                            </tr>
                          @endforeach
                        </tbody>
                      </table>
                    </div>
                  </div>
                @else
                  <div class="alert alert-warning mb-7">
                    <i class="ki-outline ki-information-5 fs-2 me-2"></i>
                    <strong>Belum ada rekening terhubung.</strong> Silakan tambahkan rekening baru di bawah.
                  </div>
                @endif

                {{-- Add New Rekening - SIMPLE APPROACH --}}
                <div class="card border border-primary border-dashed bg-light-primary">
                  <div class="card-body">
                    <h6 class="fw-bold mb-4 text-primary">
                      <i class="ki-outline ki-plus-circle fs-2 me-2"></i>
                      Tambah Rekening Baru
                    </h6>

                    <div class="row">
                      <div class="col-12">
                        <label class="fs-6 fw-semibold mb-3">Pilih Rekening Belanja (Akun)</label>
                        <select class="form-select form-select-solid" name="rekening_belanja[]" id="select_rekening_belanja" multiple
                          size="10">
                          @foreach ($akunList as $akun)
                            <option value="{{ $akun->id }}">{{ $akun->kode_akun }} - {{ $akun->nama_akun }}</option>
                          @endforeach
                        </select>
                        <div class="form-text mt-3">
                          <i class="ki-outline ki-information-5 fs-4 me-1"></i>
                          <strong>Cara Memilih:</strong> Tahan <kbd>Ctrl</kbd> (Windows) atau <kbd>Cmd</kbd> (Mac) lalu klik untuk memilih multiple
                          rekening.
                          <br>
                          <strong>Info:</strong> {{ $akunList->count() }} akun belanja tersedia untuk dipilih.
                        </div>
                      </div>
                    </div>

                    <div class="separator my-5"></div>

                    <div class="alert alert-light-info mb-0">
                      <h6 class="fw-bold mb-2">
                        <i class="ki-outline ki-information fs-3 me-2"></i>
                        Informasi Penting:
                      </h6>
                      <ul class="mb-0">
                        <li>Pilih satu atau lebih rekening dari list di atas</li>
                        <li>Sistem akan <strong>mengganti semua</strong> rekening lama dengan yang baru dipilih</li>
                        <li>Kosongkan pilihan jika tidak ingin menambah rekening baru</li>
                        <li>Kode dan nama akun akan otomatis tersimpan dari master data</li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="card-footer bg-light">
              <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('data_ssh.index') }}" class="btn btn-light">
                  <i class="ki-outline ki-cross fs-2"></i>
                  Batal
                </a>
                <button type="submit" class="btn btn-primary" id="update_ssh_btn">
                  <i class="ki-outline ki-check fs-2"></i>
                  Update Data & Rekening
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

      // Toastr config
      toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toastr-top-right",
        "timeOut": "5000"
      };

      if (editForm && updateButton) {
        editForm.addEventListener('submit', function(e) {
          // Get selected rekening
          const selectElement = document.getElementById('select_rekening_belanja');
          const selectedOptions = Array.from(selectElement.selectedOptions);
          const selectedCount = selectedOptions.length;

          console.log('Selected rekening count:', selectedCount);
          console.log('Selected rekening IDs:', selectedOptions.map(opt => opt.value));

          // Show confirmation if rekening selected
          if (selectedCount > 0) {
            console.log('Will add', selectedCount, 'new rekening');
          }

          updateButton.setAttribute('data-kt-indicator', 'on');
          updateButton.disabled = true;
        });
      }

      // Toggle rekening active
      $(document).on('change', '.toggle-rekening-active', function() {
        const checkbox = $(this);
        const idRekening = checkbox.data('id');
        const sshId = checkbox.data('ssh-id');

        $.ajax({
          url: `/standarHarga/data_ssh/${sshId}/rekening/${idRekening}/toggle-active`,
          method: 'POST',
          data: {
            _token: '{{ csrf_token() }}'
          },
          success: function(response) {
            if (response.success) {
              toastr.success(response.message, 'BERHASIL');
            }
          },
          error: function(xhr) {
            toastr.error(xhr.responseJSON?.message || 'Gagal mengubah status', 'GAGAL');
            checkbox.prop('checked', !checkbox.prop('checked'));
          }
        });
      });

      // Remove rekening
      $(document).on('click', '.btn-remove-rekening', function() {
        const id = $(this).data('id');
        const nama = $(this).data('nama');
        const sshId = '{{ $ssh->id_standar_harga }}';

        Swal.fire({
          title: 'Hapus Rekening?',
          html: `Rekening <strong>"${nama}"</strong> akan dihapus dari data SSH ini.`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Ya, hapus!',
          cancelButtonText: 'Batal',
          buttonsStyling: false,
          customClass: {
            confirmButton: "btn btn-danger",
            cancelButton: "btn btn-secondary"
          }
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = `/standarHarga/data_ssh/${sshId}/rekening/${id}/remove`;
          }
        });
      });

      // Initialize Select2 for better UX (optional)
      if (typeof $.fn.select2 !== 'undefined') {
        $('#select_rekening_belanja').select2({
          placeholder: "Pilih rekening belanja...",
          allowClear: true,
          width: '100%',
          templateResult: formatOption,
          templateSelection: formatSelection
        });

        function formatOption(option) {
          if (!option.id) return option.text;
          const parts = option.text.split(' - ');
          if (parts.length === 2) {
            return $('<div><strong>' + parts[0] + '</strong><br><small class="text-muted">' + parts[1] + '</small></div>');
          }
          return option.text;
        }

        function formatSelection(option) {
          if (!option.id) return option.text;
          const parts = option.text.split(' - ');
          return parts[0] || option.text;
        }
      }
    });
  </script>
@endsection
