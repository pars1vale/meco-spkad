<script>
  document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById('kt_modal_add_sub_kegiatan_form');
    const submitButton = document.getElementById('kt_modal_add_sub_kegiatan_submit');

    if (form && submitButton) {
      form.addEventListener('submit', function(e) {
        const idKegiatan = form.querySelector('select[name="id_kegiatan"]').value;
        const kodeSubKegiatan = form.querySelector('input[name="kode_sub_kegiatan"]').value.trim();
        const namaSubKegiatan = form.querySelector('textarea[name="nama_sub_kegiatan"]').value.trim();

        if (!idKegiatan || !kodeSubKegiatan || !namaSubKegiatan) {
          e.preventDefault();
          Swal.fire({
            icon: 'error',
            title: 'Validasi gagal',
            text: 'Semua field wajib diisi!',
            confirmButtonText: 'OK',
            buttonsStyling: false,
            customClass: {
              confirmButton: "btn btn-primary"
            }
          });
          return;
        }

        submitButton.setAttribute('data-kt-indicator', 'on');
        submitButton.disabled = true;
      });
    }

    @if ($errors->any() && old('_token'))
      $('#kt_modal_add_sub_kegiatan').modal('show');
    @endif
  });
</script>
