<!-- Modal Tambah Akun -->
<div class="modal fade" id="kt_modal_add_akun" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered mw-650px">
    <div class="modal-content">
      <form class="form" id="kt_modal_add_akun_form">
        @csrf
        <div class="modal-header" id="kt_modal_add_akun_header">
          <h2 class="fw-bold">Tambah Akun</h2>
          <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
            <i class="ki-outline ki-cross fs-1"></i>
          </div>
        </div>

        <div class="modal-body py-10 px-lg-17">
          <div class="scroll-y me-n7 pe-7" id="kt_modal_add_akun_scroll">

            <!-- Kode Akun -->
            <div class="fv-row mb-7">
              <label class="required fs-6 fw-semibold mb-2">Kode Akun</label>
              <input type="text" class="form-control form-control-solid" placeholder="Masukkan kode akun" name="kode_akun" maxlength="255"
                required />
              <div class="invalid-feedback"></div>
              <div class="form-text">Kode akun harus unik</div>
            </div>

            <!-- Nama Akun -->
            <div class="fv-row mb-7">
              <label class="required fs-6 fw-semibold mb-2">Nama Akun</label>
              <textarea class="form-control form-control-solid" rows="3" placeholder="Masukkan nama akun" name="nama_akun" required></textarea>
              <div class="invalid-feedback"></div>
            </div>

            <!-- Keterangan Akun -->
            <div class="fv-row mb-7">
              <label class="fs-6 fw-semibold mb-2">Keterangan Akun</label>
              <textarea class="form-control form-control-solid" rows="3" placeholder="Masukkan keterangan akun (opsional)" name="keterangan_akun"></textarea>
              <div class="invalid-feedback"></div>
            </div>

            <!-- Tipe Akun -->
            <div class="fv-row mb-7">
              <label class="required fs-6 fw-semibold mb-2">Tipe Akun</label>
              <div class="form-text mb-3">Pilih salah satu tipe akun</div>

              <div class="row">
                <!-- Pendapatan Switch -->
                <div class="col-md-4 mb-3">
                  <div class="form-check form-switch form-check-custom form-check-solid">
                    <input class="form-check-input akun-type-switch" type="checkbox" value="1" id="pendapatanSwitch" name="is_pendapatan" />
                    <label class="form-check-label" for="pendapatanSwitch">
                      Pendapatan
                    </label>
                  </div>
                </div>

                <!-- Belanja Switch -->
                <div class="col-md-4 mb-3">
                  <div class="form-check form-switch form-check-custom form-check-solid">
                    <input class="form-check-input akun-type-switch" type="checkbox" value="1" id="belanjaSwitch" name="is_belanja" />
                    <label class="form-check-label" for="belanjaSwitch">
                      Belanja
                    </label>
                  </div>
                </div>

                <!-- Pembiayaan Switch -->
                <div class="col-md-4 mb-3">
                  <div class="form-check form-switch form-check-custom form-check-solid">
                    <input class="form-check-input akun-type-switch" type="checkbox" value="1" id="pembiayaanSwitch" name="is_pembiayaan" />
                    <label class="form-check-label" for="pembiayaanSwitch">
                      Pembiayaan
                    </label>
                  </div>
                </div>
              </div>

              <div class="invalid-feedback d-none" id="tipe-akun-error">
                Anda harus memilih salah satu tipe akun
              </div>
            </div>

          </div>
        </div>

        <div class="modal-footer flex-center">
          <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
          <button type="submit" id="kt_modal_add_akun_submit" class="btn btn-primary">
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
