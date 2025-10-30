<script>
  document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById('kt_modal_add_kegiatan_form');
    const submitButton = document.getElementById('kt_modal_add_kegiatan_submit');

    if (form && submitButton) {
      form.addEventListener('submit', function(e) {
        const idProgram = form.querySelector('select[name="id_program"]').value;
        const kodeKegiatan = form.querySelector('input[name="kode_kegiatan"]').value.trim();
        const namaKegiatan = form.querySelector('textarea[name="nama_kegiatan"]').value.trim();

        if (!idProgram || !kodeKegiatan || !namaKegiatan) {
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
      $('#kt_modal_add_kegiatan').modal('show');
    @endif
  });
</script>
