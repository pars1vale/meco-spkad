<div class="modal fade" id="kt_modal_add_kelompok" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered mw-650px">
    <div class="modal-content">
      <form class="form" id="kt_modal_add_kelompok_form">
        @csrf
        <div class="modal-header">
          <h2 class="fw-bold">Tambah Kelompok Standar Harga</h2>
          <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
            <i class="ki-outline ki-cross fs-1"></i>
          </div>
        </div>

        <div class="modal-body py-10 px-lg-17">
          <div class="scroll-y me-n7 pe-7">

            <!-- Kode Kelompok -->
            <div class="fv-row mb-7">
              <label class="required fs-6 fw-semibold mb-2">Kode Kelompok</label>
              <input type="text" class="form-control form-control-solid" placeholder="Contoh: 1.1.12.01.01" name="kode_kelompok_standar_harga"
                maxlength="30" required />
              <div class="invalid-feedback"></div>
              <div class="form-text">Kode kelompok harus unik</div>
            </div>

            <!-- Tipe Kelompok -->
            <div class="fv-row mb-7">
              <label class="required fs-6 fw-semibold mb-2">Tipe Kelompok</label>
              <div class="form-text mb-3">Pilih tipe kelompok standar harga</div>

              <div class="d-flex flex-wrap gap-3">
                <div class="form-check form-check-custom form-check-solid">
                  <input class="form-check-input" type="radio" value="SSH" id="tipe_SSH" name="tipe_kelompok" required />
                  <label class="form-check-label fw-bold" for="tipe_SSH">SSH</label>
                </div>

                <div class="form-check form-check-custom form-check-solid">
                  <input class="form-check-input" type="radio" value="HSPK" id="tipe_HSPK" name="tipe_kelompok" />
                  <label class="form-check-label fw-bold" for="tipe_HSPK">HSPK</label>
                </div>

                <div class="form-check form-check-custom form-check-solid">
                  <input class="form-check-input" type="radio" value="ASB" id="tipe_ASB" name="tipe_kelompok" />
                  <label class="form-check-label fw-bold" for="tipe_ASB">ASB</label>
                </div>

                <div class="form-check form-check-custom form-check-solid">
                  <input class="form-check-input" type="radio" value="SBU" id="tipe_SBU" name="tipe_kelompok" />
                  <label class="form-check-label fw-bold" for="tipe_SBU">SBU</label>
                </div>
              </div>
              <div class="invalid-feedback"></div>
            </div>

            <!-- Nama Kelompok -->
            <div class="fv-row mb-7">
              <label class="required fs-6 fw-semibold mb-2">Nama Kelompok</label>
              <textarea class="form-control form-control-solid" rows="3" placeholder="Masukkan nama kelompok standar harga" name="nama_kelompok_standar_harga"
                required></textarea>
              <div class="invalid-feedback"></div>
            </div>

          </div>
        </div>

        <div class="modal-footer flex-center">
          <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
          <button type="submit" id="kt_modal_add_kelompok_submit" class="btn btn-primary">
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
