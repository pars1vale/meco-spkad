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

          <div id="cetak_rincian_ttd_loading" class="text-muted fs-7 mb-3 d-none">
            Memeriksa data penandatangan default...
          </div>

          <div id="cetak_rincian_ttd_fields" class="d-none">
            <div class="alert alert-secondary py-2 px-3 mb-4 fs-7" id="cetak_rincian_ttd_info"></div>

            <div class="mb-5">
              <label class="required form-label">Nama Penandatangan</label>
              <input type="text" class="form-control form-control-solid" id="cetak_rincian_nama" placeholder="Nama lengkap">
            </div>

            <div class="mb-0">
              <label class="required form-label">NIP Penandatangan</label>
              <input type="text" class="form-control form-control-solid" id="cetak_rincian_nip" placeholder="NIP" inputmode="numeric"
                maxlength="18">
              <div class="form-text">Maksimal 18 digit angka.</div>
            </div>
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
      $('#form_cetak_rincian').data('has-default', false);
      document.getElementById('cetak_rincian_tanggal')._flatpickr?.clear();
      $('#cetak_rincian_nama').val('');
      $('#cetak_rincian_nip').val('');

      $('#cetak_rincian_ttd_fields').addClass('d-none');
      $('#cetak_rincian_ttd_loading').removeClass('d-none');
      $('#btn_confirm_cetak_rincian').prop('disabled', true);

      const modal = new bootstrap.Modal(document.getElementById('kt_modal_cetak_rincian'));
      modal.show();

      $.get($(this).data('ttd-url'))
        .done(function(res) {
          const hasDefault = !!res.has_default;
          $('#form_cetak_rincian').data('has-default', hasDefault);

          if (hasDefault) {
            $('#cetak_rincian_ttd_fields').addClass('d-none');
          } else {
            $('#cetak_rincian_ttd_info').text('Data penandatangan default tidak ditemukan, silakan isi manual.');
            $('#cetak_rincian_ttd_fields').removeClass('d-none');
          }
        })
        .fail(function() {
          // Gagal cek default -> aman, minta isi manual daripada blokir total
          $('#form_cetak_rincian').data('has-default', false);
          $('#cetak_rincian_ttd_info').text('Gagal memeriksa data default, silakan isi manual.');
          $('#cetak_rincian_ttd_fields').removeClass('d-none');
        })
        .always(function() {
          $('#cetak_rincian_ttd_loading').addClass('d-none');
          $('#btn_confirm_cetak_rincian').prop('disabled', false);
        });
    });

    $('#btn_confirm_cetak_rincian').on('click', function() {
      const baseUrl = $('#form_cetak_rincian').data('base-url');
      const hasDefault = $('#form_cetak_rincian').data('has-default');
      const tanggal = $('#cetak_rincian_tanggal').val().trim();
      const nama = $('#cetak_rincian_nama').val().trim();
      const nip = $('#cetak_rincian_nip').val().trim();

      if (!baseUrl || !tanggal) {
        Swal.fire({
          icon: 'warning',
          title: 'Lengkapi data',
          text: 'Tanggal wajib diisi.',
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

      if (!hasDefault) {
        if (!nama || !nip) {
          Swal.fire({
            icon: 'warning',
            title: 'Lengkapi data',
            text: 'Nama dan NIP penandatangan wajib diisi.',
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
      }

      const paramsObj = {
        tanggal: tanggal
      };
      if (!hasDefault) {
        paramsObj.nama_ttd = nama;
        paramsObj.nip_ttd = nip;
      }

      const params = new URLSearchParams(paramsObj);

      window.open(baseUrl + '?' + params.toString(), '_blank');

      const modalEl = document.getElementById('kt_modal_cetak_rincian');
      bootstrap.Modal.getInstance(modalEl)?.hide();
    });
  });
</script>
