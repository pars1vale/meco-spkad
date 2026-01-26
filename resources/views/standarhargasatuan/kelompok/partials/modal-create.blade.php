<div class="modal fade" id="kt_modal_add_kelompok" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered mw-800px">
    <div class="modal-content">
      <form class="form" id="kt_modal_add_kelompok_form">
        @csrf
        <div class="modal-header">
          <h2 class="fw-bold">Tambah Kelompok Satuan Harga</h2>
          <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
            <i class="ki-outline ki-cross fs-1"></i>
          </div>
        </div>

        <div class="modal-body py-10 px-lg-17">
          <div class="scroll-y me-n7 pe-7" style="max-height: 70vh">

            <div class="row">
              <div class="col-md-6">

                <!-- Kode Kategori -->
                <div class="fv-row mb-7">
                  <label class="required fs-6 fw-semibold mb-2">Kode Kategori</label>
                  <input type="text" class="form-control form-control-solid" placeholder="Contoh: 1.1.12.01" name="kode_kategori" maxlength="50"
                    required />
                  <div class="invalid-feedback"></div>
                  <div class="form-text">Kode kategori harus unik</div>
                </div>

                <!-- Tahun Anggaran -->
                <div class="fv-row mb-7">
                  <label class="required fs-6 fw-semibold mb-2">Tahun Anggaran</label>
                  <input type="number" class="form-control form-control-solid" placeholder="Contoh: {{ date('Y') }}" name="tahun_anggaran"
                    min="2000" max="2100" value="{{ date('Y') }}" required />
                  <div class="invalid-feedback"></div>
                </div>
              </div>

              <div class="col-md-6">
                <!-- Tipe Kelompok -->
                <div class="fv-row mb-7">
                  <label class="required fs-6 fw-semibold mb-2">Tipe Kelompok</label>
                  <div class="form-text mb-3">Pilih tipe kelompok satuan harga</div>

                  <div class="d-flex flex-column gap-3">
                    <div class="form-check form-check-custom form-check-solid">
                      <input class="form-check-input" type="radio" value="SSH" id="tipe_SSH" name="tipe_kelompok" required />
                      <label class="form-check-label fw-bold" for="tipe_SSH">
                        SSH - Standar Satuan Harga
                      </label>
                    </div>

                    <div class="form-check form-check-custom form-check-solid">
                      <input class="form-check-input" type="radio" value="HSPK" id="tipe_HSPK" name="tipe_kelompok" />
                      <label class="form-check-label fw-bold" for="tipe_HSPK">
                        HSPK - Harga Satuan Pokok Kegiatan
                      </label>
                    </div>

                    <div class="form-check form-check-custom form-check-solid">
                      <input class="form-check-input" type="radio" value="ASB" id="tipe_ASB" name="tipe_kelompok" />
                      <label class="form-check-label fw-bold" for="tipe_ASB">
                        ASB - Analisa Standar Belanja
                      </label>
                    </div>

                    <div class="form-check form-check-custom form-check-solid">
                      <input class="form-check-input" type="radio" value="SBU" id="tipe_SBU" name="tipe_kelompok" />
                      <label class="form-check-label fw-bold" for="tipe_SBU">
                        SBU - Standar Biaya Umum
                      </label>
                    </div>
                  </div>
                  <div class="invalid-feedback"></div>
                </div>

                <!-- Status Active -->
                <div class="fv-row mb-7">
                  <div class="form-check form-switch form-check-custom form-check-solid">
                    <input class="form-check-input" type="checkbox" value="1" id="active_switch" name="active" checked />
                    <label class="form-check-label fw-bold" for="active_switch">
                      Status Aktif
                    </label>
                  </div>
                  <div class="form-text">Data yang aktif dapat digunakan dalam transaksi</div>
                </div>
              </div>
            </div>

            <!-- Uraian Kategori -->
            <div class="fv-row mb-7">
              <label class="required fs-6 fw-semibold mb-2">Uraian Kategori</label>
              <textarea class="form-control form-control-solid" rows="4" placeholder="Masukkan uraian lengkap kategori" name="uraian_kategori" required></textarea>
              <div class="invalid-feedback"></div>
            </div>

          </div>
        </div>

        <div class="modal-footer flex-center">
          <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
          <button type="submit" id="kt_modal_add_kelompok_submit" class="btn btn-primary">
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
