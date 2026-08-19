<div class="modal fade" id="kt_modal_add_kegiatan" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered mw-900px">
    <div class="modal-content">
      <form class="form" action="{{ route('renja.store') }}" method="POST" id="kt_modal_add_kegiatan_form">
        @csrf
        <div class="modal-header" id="kt_modal_add_kegiatan_header">
          <h2 class="fw-bold">Tambah Sub Kegiatan Belanja</h2>
          <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
            <i class="ki-outline ki-cross fs-1"></i>
          </div>
        </div>

        <div class="modal-body py-10 px-lg-17">
          <div class="scroll-y me-n7 pe-7" id="kt_modal_add_kegiatan_scroll" style="max-height: 500px;">

            <div class="fv-row mb-7">
              <label class="required fs-6 fw-semibold mb-2">Pilih SKPD/Sub Unit</label>
              <select class="form-select form-select-solid @error('id_skpd') is-invalid @enderror" name="id_skpd" id="select_skpd"
                data-control="select2" data-dropdown-parent="#kt_modal_add_kegiatan" required>
                <option value="">Pilih SKPD</option>
                @foreach ($data_unit as $skpd)
                  <option value="{{ $skpd->id_skpd }}" {{ old('id_skpd') == $skpd->id_skpd ? 'selected' : '' }}>
                    {{ $skpd->kode_skpd }} - {{ $skpd->nama_skpd }}
                  </option>
                @endforeach
              </select>
              @error('id_skpd')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <!-- Loading indicator -->
            <div id="loading_sub_kegiatan" class="d-none">
              <div class="d-flex align-items-center">
                <span class="spinner-border spinner-border-sm me-2"></span>
                <span>Memuat sub kegiatan...</span>
              </div>
            </div>

            <!-- Sub Kegiatan List -->
            <div class="fv-row mb-7" id="sub_kegiatan_container" style="display: none;">
              <label class="required fs-6 fw-semibold mb-2">Sub Kegiatan</label>
              <select class="form-select form-select-solid @error('id_sub_kegiatan') is-invalid @enderror" name="id_sub_kegiatan"
                id="select_sub_kegiatan" data-control="select2" data-dropdown-parent="#kt_modal_add_kegiatan" required>
                <option value="">Pilih Sub Kegiatan</option>
              </select>
              @error('id_sub_kegiatan')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <div class="form-text">
                Total: <span id="total_sub_kegiatan">0</span> sub kegiatan
              </div>
            </div>

            <!-- Detail Sub Kegiatan yang dipilih -->
            <div id="detail_sub_kegiatan" class="alert alert-info d-none mt-5">
              <h5 class="mb-3">Detail Sub Kegiatan</h5>
              <table class="table table-sm table-borderless">
                <tr>
                  <td width="150px"><strong>Bidang Urusan:</strong></td>
                  <td id="detail_bidang_urusan">-</td>
                </tr>
                <tr>
                  <td><strong>Program:</strong></td>
                  <td id="detail_program">-</td>
                </tr>
                <tr>
                  <td><strong>Kegiatan:</strong></td>
                  <td id="detail_kegiatan">-</td>
                </tr>
                <tr>
                  <td><strong>Sub Kegiatan:</strong></td>
                  <td id="detail_sub_keg">-</td>
                </tr>
              </table>

              <!-- Indikator Section -->
              <div id="indikator_section" class="mt-5 d-none">
                <div class="separator separator-dashed my-4"></div>
                <div id="indikator_list">
                  <!-- Indikator akan dimuat di sini -->
                </div>
              </div>
            </div>

            <!-- Separator -->
            <div class="separator separator-dashed my-7"></div>

            <!-- Sumber Dana Section -->
            <div class="fv-row mb-7">
              <div class="d-flex justify-content-between align-items-center mb-5">
                <label class="fs-6 fw-semibold">Sumber Dana</label>
                <button type="button" class="btn btn-sm btn-light-primary" id="btn_add_sumber_dana">
                  <i class="ki-outline ki-plus fs-3"></i>
                  Sumber Dana
                </button>
              </div>

              <!-- Container untuk dynamic forms -->
              <div id="sumber_dana_container">
                <!-- Dynamic forms akan ditambahkan di sini -->
              </div>

              <!-- Info jika belum ada sumber dana -->
              <div id="no_sumber_dana_info" class="alert alert-light-warning d-flex align-items-center p-5">
                <i class="ki-outline ki-information-5 fs-2hx text-warning me-4"></i>
                <div class="d-flex flex-column">
                  <h4 class="mb-1 text-warning">Belum ada sumber dana</h4>
                  <span>Klik tombol "+ Sumber Dana" untuk menambahkan sumber dana dan pagu</span>
                </div>
              </div>

              <!-- Total Pagu Summary -->
              <div id="total_pagu_summary" class="card bg-light-primary d-none mt-5">
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

            <div class="fv-row mb-7">
              <label class="fs-6 fw-semibold mb-2">Rincian Lokasi</label>
              <div class="d-flex gap-3">
                {{-- Kabupaten --}}
                <select id="id_lokasi" class="form-select form-select-solid" name="id_lokasi" required>
                  <option value="">Pilih Daerah</option>
                  @foreach ($daerah as $kab)
                    <option value="{{ $kab->id_daerah }}">{{ $kab->nama_daerah }}</option>
                  @endforeach
                </select>

                {{-- Kecamatan --}}
                <select id="kecamatan" class="form-select form-select-solid" name="id_camat">
                  <option value="">Pilih Kecamatan</option>
                  @foreach ($kec as $kc)
                    <option value="{{ $kc->id_camat }}">{{ $kc->camat_teks }}</option>
                  @endforeach
                </select>

                {{-- Kelurahan --}}
                <select id="kelurahan" class="form-select form-select-solid" name="id_lurah">
                  <option value="">Pilih Kelurahan</option>
                  @foreach ($kel as $kl)
                    <option value="{{ $kl->id_lurah }}">{{ $kl->lurah_teks }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="fv-row mb-7">
              <label class="fs-6 fw-semibold mb-2">Waktu Pelaksana</label>
              <div class="d-flex gap-3">
                {{-- Waktu Awal --}}
                <select id="waktu_awal" class="form-select form-select-solid" name="waktu_awal" required>
                  <option value="">Pilih Waktu Awal</option>
                  @foreach ($bln as $bl)
                    <option value="{{ $bl->id }}">{{ $bl->nama }}</option>
                  @endforeach
                </select>
                <span>S/D</span>
                {{-- Waktu Akhir --}}
                <select id="waktu_akhir" class="form-select form-select-solid" name="waktu_akhir">
                  <option value="">Pilih Waktu Akhir</option>
                  @foreach ($bln as $bl)
                    <option value="{{ $bl->id }}">{{ $bl->nama }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="fv-row mb-7">
              <label class=" fs-6 fw-semibold mb-2">Anggaran N+1 Sub Kegiatan</label>
              <div class="input-group">
                <span class="input-group-text">Rp</span>
                <input type="text" class="form-control form-control-solid input-pagu" name="pagu_n_depan" placeholder="0">
              </div>
            </div>

          </div>
        </div>

        <div class="modal-footer flex-center">
          <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
          <button type="submit" id="kt_modal_add_kegiatan_submit" class="btn btn-primary">
            <span class="indicator-label">Simpan</span>
            <span class="indicator-progress">Menyimpan...
              <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </span>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
