<div class="modal fade" id="kt_modal_cetak_rka_pendapatan" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="modal_cetak_rka_pendapatan_nama_skpd">Cetak RKA Pendapatan</h2>
        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
          <i class="ki-outline ki-cross fs-1"></i>
        </div>
      </div>
      <div class="modal-body">
        <form id="form_cetak_rka_pendapatan">
          <div class="mb-5">
            <label class="required form-label">Tanggal Pengesahan</label>
            <input type="text" id="input_tanggal_ttd_rka_pendapatan" class="form-control" placeholder="dd-mm-yyyy" autocomplete="off" required>
          </div>

          <div class="mb-5">
            <label class="required form-label">Nama Penandatangan</label>
            <input type="text" id="input_nama_ttd_rka_pendapatan" class="form-control" maxlength="150">
            <div class="form-text" id="note_nama_ttd_rka_pendapatan"></div>
          </div>

          <div class="mb-5">
            <label class="required form-label">NIP Penandatangan</label>
            <input type="text" id="input_nip_ttd_rka_pendapatan" class="form-control" maxlength="18">
            <div class="form-text" id="note_nip_ttd_rka_pendapatan"></div>
            <div class="text-danger fs-8" id="error_nip_ttd_rka_pendapatan" style="display:none;">
              NIP hanya boleh berisi angka, maksimal 18 digit.
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="btn_submit_cetak_rka_pendapatan">
          Lihat Cetakan
        </button>
      </div>
    </div>
  </div>
</div>
