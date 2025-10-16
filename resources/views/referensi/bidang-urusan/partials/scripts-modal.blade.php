<script>
  document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById('kt_modal_add_bidang_urusan_form');
    const submitButton = document.getElementById('kt_modal_add_bidang_urusan_submit');

    if (form && submitButton) {
      form.addEventListener('submit', function(e) {
        const idUrusan = form.querySelector('select[name="id_urusan"]').value;
        const kodeBidangUrusan = form.querySelector('input[name="kode_bidang_urusan"]').value.trim();
        const namaBidangUrusan = form.querySelector('input[name="nama_bidang_urusan"]').value.trim();

        if (!idUrusan || !kodeBidangUrusan || !namaBidangUrusan) {
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
      $('#kt_modal_add_bidang_urusan').modal('show');
    @endif
  });
</script>
