{{-- <script>
  document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById('kt_modal_add_sub_kegiatan_form');
    const submitButton = document.getElementById('kt_modal_add_sub_kegiatan_submit');
    // Initialize Select2
    // $('[data-control="select2"]').select2({
    //   placeholder: 'Pilih Kegiatan',
    //   allowClear: true,
    //   width: '100%'
    // });

    $('#kt_modal_add_sub_kegiatan').on('shown.bs.modal', function() {
      // Re-init semua select2 di dalam modal ini
      $(this).find('[data-control="select2"]').each(function() {
        if (!$(this).hasClass('select2-hidden-accessible')) {
          $(this).select2({
            placeholder: $(this).data('placeholder') || 'Pilih data',
            dropdownParent: $('#kt_modal_add_sub_kegiatan'), // penting agar dropdown muncul di dalam modal
            allowClear: true,
            width: '100%'
          });
        }
      });
    });

    // Optional: reset select2 saat modal ditutup
    $('#kt_modal_add_sub_kegiatan').on('hidden.bs.modal', function() {
      $(this).find('select[data-control="select2"]').val('').trigger('change');
    });

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
</script> --}}
