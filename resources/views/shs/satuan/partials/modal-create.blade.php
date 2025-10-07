<!-- Modal Tambah Satuan -->
<div class="modal fade" id="kt_modal_add_satuan" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered mw-650px">
    <div class="modal-content">
      <form class="form" id="kt_modal_add_satuan_form">
        @csrf
        <div class="modal-header" id="kt_modal_add_satuan_header">
          <h2 class="fw-bold">Tambah Satuan</h2>
          <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
            <i class="ki-outline ki-cross fs-1"></i>
          </div>
        </div>

        <div class="modal-body py-10 px-lg-17">
          <div class="scroll-y me-n7 pe-7" id="kt_modal_add_satuan_scroll">

            <!-- Nama Satuan -->
            <div class="fv-row mb-7">
              <label class="required fs-6 fw-semibold mb-2">Nama Satuan</label>
              <input type="text" class="form-control form-control-solid" placeholder="Contoh: Buah, Unit, Paket, dll" name="nama_satuan"
                maxlength="50" required />
              <div class="invalid-feedback"></div>
              <div class="form-text">Nama satuan harus unik dan maksimal 50 karakter</div>
            </div>

          </div>
        </div>

        <div class="modal-footer flex-center">
          <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
          <button type="submit" id="kt_modal_add_satuan_submit" class="btn btn-primary">
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
