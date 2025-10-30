<script>
  document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById('kt_modal_add_program_form');
    const submitButton = document.getElementById('kt_modal_add_program_submit');

    if (form && submitButton) {
      form.addEventListener('submit', function(e) {
        const idBidangUrusan = form.querySelector('select[name="id_bidang_urusan"]').value;
        const kodeProgram = form.querySelector('input[name="kode_program"]').value.trim();
        const namaProgram = form.querySelector('input[name="nama_program"]').value.trim();

        if (!idBidangUrusan || !kodeProgram || !namaProgram) {
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
      $('#kt_modal_add_program').modal('show');
    @endif
  });
</script>
