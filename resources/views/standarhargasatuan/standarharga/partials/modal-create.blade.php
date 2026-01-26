<div class="modal fade" id="kt_modal_add_ssh" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered mw-900px">
    <div class="modal-content">
      <form class="form" id="kt_modal_add_ssh_form">
        @csrf
        <div class="modal-header">
          <h2 class="fw-bold">Tambah Data SSH</h2>
          <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
            <i class="ki-outline ki-cross fs-1"></i>
          </div>
        </div>

        <div class="modal-body py-10 px-lg-17">
          <div class="scroll-y me-n7 pe-7" style="max-height: 70vh">

            <div class="row">
              <div class="col-md-6">
                <!-- Tipe Standar Harga -->
                <div class="fv-row mb-7">
                  <label class="required fs-6 fw-semibold mb-2">Tipe Standar Harga</label>
                  <div class="d-flex flex-column gap-2">
                    <div class="form-check form-check-custom form-check-solid">
                      <input class="form-check-input tipe-ssh-radio" type="radio" value="SSH" id="tipeSSH" name="tipe_standar_harga"
                        required />
                      <label class="form-check-label fw-bold" for="tipeSSH">SSH - Standar Satuan Harga</label>
                    </div>
                    <div class="form-check form-check-custom form-check-solid">
                      <input class="form-check-input tipe-ssh-radio" type="radio" value="HSPK" id="tipeHSPK" name="tipe_standar_harga" />
                      <label class="form-check-label fw-bold" for="tipeHSPK">HSPK - Harga Satuan Pokok Kegiatan</label>
                    </div>
                    <div class="form-check form-check-custom form-check-solid">
                      <input class="form-check-input tipe-ssh-radio" type="radio" value="ASB" id="tipeASB" name="tipe_standar_harga" />
                      <label class="form-check-label fw-bold" for="tipeASB">ASB - Analisa Standar Belanja</label>
                    </div>
                    <div class="form-check form-check-custom form-check-solid">
                      <input class="form-check-input tipe-ssh-radio" type="radio" value="SBU" id="tipeSBU" name="tipe_standar_harga" />
                      <label class="form-check-label fw-bold" for="tipeSBU">SBU - Standar Biaya Umum</label>
                    </div>
                  </div>
                  <div class="invalid-feedback"></div>
                </div>

                <!-- Kelompok Standar Harga -->
                <div class="fv-row mb-7">
                  <label class="required fs-6 fw-semibold mb-2">Kelompok Standar Harga</label>
                  <select class="form-select form-select-solid" name="id_kel_standar_harga" id="kelompok_select" data-control="select2"
                    data-dropdown-parent="#kt_modal_add_ssh" data-placeholder="Pilih kelompok" required>
                    <option></option>
                    @foreach ($kelompokList as $kel)
                      <option value="{{ $kel->id_kategori }}" data-tipe="{{ $kel->tipe_kelompok }}">
                        {{ $kel->kode_kategori }} - {{ $kel->uraian_kategori }}
                      </option>
                    @endforeach
                  </select>
                  <div class="invalid-feedback"></div>
                  <div class="form-text">Kelompok akan difilter sesuai tipe yang dipilih</div>
                </div>

                <!-- Kode Standar Harga -->
                <div class="fv-row mb-7">
                  <label class="required fs-6 fw-semibold mb-2">Kode Standar Harga</label>
                  <input type="text" class="form-control form-control-solid" placeholder="Contoh: 1.1.12.01.01.0001" name="kode_standar_harga"
                    maxlength="50" required />
                  <div class="invalid-feedback"></div>
                </div>

                <!-- Satuan -->
                <div class="fv-row mb-7">
                  <label class="required fs-6 fw-semibold mb-2">Satuan</label>
                  <input type="text" class="form-control form-control-solid" placeholder="Contoh: Unit, M2, Buah" name="satuan" maxlength="50"
                    required />
                  <div class="invalid-feedback"></div>
                </div>

                <!-- Harga -->
                <div class="fv-row mb-7">
                  <label class="required fs-6 fw-semibold mb-2">Harga</label>
                  <input type="number" class="form-control form-control-solid" placeholder="Masukkan harga" name="harga" step="0.01"
                    min="0" required />
                  <div class="invalid-feedback"></div>
                </div>
              </div>

              <div class="col-md-6">
                <!-- Tahun -->
                <div class="fv-row mb-7">
                  <label class="required fs-6 fw-semibold mb-2">Tahun Anggaran</label>
                  <input type="number" class="form-control form-control-solid" placeholder="Contoh: {{ date('Y') }}" name="tahun"
                    min="2000" max="2100" value="{{ date('Y') }}" required />
                  <div class="invalid-feedback"></div>
                </div>

                <!-- ID Daerah -->
                <div class="fv-row mb-7">
                  <label class="required fs-6 fw-semibold mb-2">ID Daerah</label>
                  <input type="number" class="form-control form-control-solid" placeholder="Contoh: 1" name="id_daerah" required />
                  <div class="invalid-feedback"></div>
                  <div class="form-text">ID Daerah sesuai dengan wilayah</div>
                </div>

                <!-- Nilai TKDN -->
                <div class="fv-row mb-7">
                  <label class="fs-6 fw-semibold mb-2">Nilai TKDN (%)</label>
                  <input type="number" class="form-control form-control-solid" placeholder="0-100" name="nilai_tkdn" step="0.01"
                    min="0" max="100" value="0" />
                  <div class="invalid-feedback"></div>
                  <div class="form-text">Tingkat Komponen Dalam Negeri</div>
                </div>

                <!-- Is PDN -->
                <div class="fv-row mb-7">
                  <div class="form-check form-switch form-check-custom form-check-solid">
                    <input class="form-check-input" type="checkbox" value="1" id="isPdnSwitch" name="is_pdn" />
                    <label class="form-check-label fw-bold" for="isPdnSwitch">
                      Produk Dalam Negeri (PDN)
                    </label>
                  </div>
                </div>
              </div>
            </div>

            <!-- Nama Standar Harga -->
            <div class="fv-row mb-7">
              <label class="required fs-6 fw-semibold mb-2">Nama Standar Harga</label>
              <textarea class="form-control form-control-solid" rows="3" placeholder="Masukkan nama standar harga" name="nama_standar_harga"
                maxlength="255" required></textarea>
              <div class="invalid-feedback"></div>
            </div>

            <!-- Spesifikasi -->
            <div class="fv-row mb-7">
              <label class="fs-6 fw-semibold mb-2">Spesifikasi</label>
              <textarea class="form-control form-control-solid" rows="3" placeholder="Masukkan spesifikasi (opsional)" name="spek"></textarea>
              <div class="invalid-feedback"></div>
            </div>

            <!-- Keterangan -->
            <div class="fv-row mb-7">
              <label class="fs-6 fw-semibold mb-2">Keterangan</label>
              <textarea class="form-control form-control-solid" rows="2" placeholder="Keterangan tambahan (opsional)" name="ket_teks"></textarea>
              <div class="invalid-feedback"></div>
            </div>

          </div>
        </div>

        <div class="modal-footer flex-center">
          <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
          <button type="submit" id="kt_modal_add_ssh_submit" class="btn btn-primary">
            <span class="indicator-label">
              <i class="ki-outline ki-check fs-2"></i>
              Simpan
            </span>
            <span class="indicator-progress">Menyimpan...
              <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </span>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
