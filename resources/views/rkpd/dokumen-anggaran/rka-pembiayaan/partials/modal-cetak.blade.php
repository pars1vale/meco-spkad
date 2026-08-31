<div class="modal fade" id="modal-cetak-rka-pembiayaan" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">Cetak Rincian RKA Pembiayaan</h3>
        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
          <i class="ki-duotone ki-cross fs-1"></i>
        </div>
      </div>
      <div class="modal-body">
        <form id="form-cetak-rka-pembiayaan" data-url-cetak-base="{{ url('rkpd/dokumen-anggaran/rka-pembiayaan') }}">
          <input type="hidden" id="cetak-id-skpd" name="id_skpd">

          <div class="mb-5">
            <label class="required form-label">Tanggal Cetak</label>
            <input type="text" id="cetak-tanggal-ttd" name="tanggal_ttd" class="form-control" placeholder="dd-mm-yyyy" autocomplete="off" required>
            <div class="invalid-feedback" id="error-tanggal-ttd"></div>
          </div>

          <div class="mb-5">
            <label class="required form-label">Nama SKPD</label>
            <input type="text" id="cetak-nama-skpd" class="form-control" disabled>
          </div>

          <div class="mb-5">
            <label class="required form-label">Nama Penandatangan</label>
            <input type="text" id="cetak-nama-ttd" name="nama_ttd" class="form-control">
          </div>

          <div class="mb-5">
            <label class="required form-label">NIP Penandatangan</label>
            <input type="text" id="cetak-nip-ttd" name="nip_ttd" class="form-control" maxlength="18">
            <div class="invalid-feedback" id="error-nip-ttd"></div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="btn-submit-cetak-rka-pembiayaan">Cetak</button>
      </div>
    </div>
  </div>
</div>
