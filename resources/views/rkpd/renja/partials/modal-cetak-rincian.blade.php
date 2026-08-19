<!-- resources/views/rkpd/renja/partials/modal-cetak-rincian.blade.php -->
<div class="modal fade" tabindex="-1" data-bs-focus="false" id="kt_modal_cetak_rincian">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Cetak Rincian Belanja</h5>

        <!--begin::Close-->
        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
          <i class="ki-outline ki-cross fs-2"></i>
        </div>
        <!--end::Close-->
      </div>

      <div class="modal-body">
        <form id="form_cetak_rincian">
          <input type="hidden" id="cetak_rincian_id" value="">

          <div class="mb-5">
            <label class="required form-label">Tanggal</label>
            <input class="form-control form-control-solid" placeholder="Pilih tanggal" id="cetak_rincian_tanggal" autocomplete="off" required>
          </div>

          <div class="mb-5">
            <label class="required form-label">Nama Penandatangan</label>
            <input type="text" class="form-control form-control-solid" id="cetak_rincian_nama" placeholder="Nama lengkap" required>
          </div>

          <div class="mb-0">
            <label class="required form-label">NIP Penandatangan</label>
            <input type="text" class="form-control form-control-solid" id="cetak_rincian_nip" placeholder="NIP" inputmode="numeric" maxlength="18"
              required>
            <div class="form-text">Maksimal 18 digit angka.</div>
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="btn_confirm_cetak_rincian">
          <i class="ki-outline ki-printer fs-4"></i>
          Cetak
        </button>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    $("#cetak_rincian_tanggal").flatpickr({
      dateFormat: 'd-m-Y',
      allowInput: true,
    });

    // NIP: hanya angka, maksimal 18 digit
    $('#cetak_rincian_nip').on('input', function() {
      this.value = this.value.replace(/\D/g, '').slice(0, 18);
    });

    $(document).on('click', '.btn-cetak-rincian', function(e) {
      e.preventDefault();

      $('#cetak_rincian_id').val($(this).data('id'));
      $('#form_cetak_rincian').data('base-url', $(this).data('url'));
      document.getElementById('cetak_rincian_tanggal')._flatpickr?.clear();
      $('#cetak_rincian_nama').val('');
      $('#cetak_rincian_nip').val('');

      const modal = new bootstrap.Modal(document.getElementById('kt_modal_cetak_rincian'));
      modal.show();
    });

    $('#btn_confirm_cetak_rincian').on('click', function() {
      const baseUrl = $('#form_cetak_rincian').data('base-url');
      const tanggal = $('#cetak_rincian_tanggal').val().trim();
      const nama = $('#cetak_rincian_nama').val().trim();
      const nip = $('#cetak_rincian_nip').val().trim();

      if (!baseUrl || !tanggal || !nama || !nip) {
        Swal.fire({
          icon: 'warning',
          title: 'Lengkapi data',
          text: 'Tanggal, nama, dan NIP penandatangan wajib diisi.',
        });
        return;
      }

      const tanggalPattern = /^\d{2}-\d{2}-\d{4}$/;
      if (!tanggalPattern.test(tanggal)) {
        Swal.fire({
          icon: 'warning',
          title: 'Format tanggal salah',
          text: 'Gunakan format dd-mm-yyyy.',
        });
        return;
      }

      const nipPattern = /^\d{1,18}$/;
      if (!nipPattern.test(nip)) {
        Swal.fire({
          icon: 'warning',
          title: 'NIP tidak valid',
          text: 'NIP hanya boleh berisi angka, maksimal 18 digit.',
        });
        return;
      }

      const params = new URLSearchParams({
        tanggal: tanggal,
        nama_ttd: nama,
        nip_ttd: nip,
      });

      window.open(baseUrl + '?' + params.toString(), '_blank');

      const modalEl = document.getElementById('kt_modal_cetak_rincian');
      bootstrap.Modal.getInstance(modalEl)?.hide();
    });
  });
</script>
