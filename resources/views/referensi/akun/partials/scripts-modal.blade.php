<script>
  document.addEventListener("DOMContentLoaded", function() {
    // Cache DOM elements for modal
    const DOM = {
      akunTypeSwitches: document.querySelectorAll('.akun-type-switch'),
      tipeAkunError: document.getElementById('tipe-akun-error'),
      addForm: document.getElementById('kt_modal_add_akun_form'),
      addSubmitButton: document.getElementById('kt_modal_add_akun_submit')
    };

    // Switch logic untuk modal tambah (mutual exclusive)
    DOM.akunTypeSwitches.forEach(switchEl => {
      switchEl.addEventListener('change', function() {
        if (this.checked) {
          DOM.akunTypeSwitches.forEach(otherSwitch => {
            if (otherSwitch !== this) {
              otherSwitch.checked = false;
            }
          });
        }
        DOM.tipeAkunError?.classList.add('d-none');
      });
    });

    // AJAX Form Submission
    if (DOM.addForm && DOM.addSubmitButton) {
      DOM.addForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        // Check if at least one switch is selected
        const hasTypeSelected = formData.get('is_pendapatan') || formData.get('is_belanja') || formData.get('is_pembiayaan');

        if (!hasTypeSelected) {
          DOM.tipeAkunError?.classList.remove('d-none');
          toastr.error('Anda harus memilih salah satu tipe akun!', 'VALIDASI GAGAL');
          return;
        }

        // Show loading state
        DOM.addSubmitButton.setAttribute('data-kt-indicator', 'on');
        DOM.addSubmitButton.disabled = true;

        // Clear previous errors
        DOM.addForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        DOM.addForm.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');

        // Submit via AJAX
        $.ajax({
          url: "{{ route('akun.store') }}",
          method: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function(response) {
            $('#kt_modal_add_akun').modal('hide');
            DOM.addForm.reset();

            toastr.success(response.message || 'Data berhasil disimpan!', 'BERHASIL');

            setTimeout(() => {
              location.reload();
            }, 1000);
          },
          error: function(xhr) {
            if (xhr.status === 422) {
              const errors = xhr.responseJSON.errors;
              Object.keys(errors).forEach(field => {
                const input = DOM.addForm.querySelector(`[name="${field}"]`);
                if (input) {
                  input.classList.add('is-invalid');
                  const feedback = input.nextElementSibling;
                  if (feedback && feedback.classList.contains('invalid-feedback')) {
                    feedback.textContent = errors[field][0];
                  }
                }
              });
              toastr.error('Periksa kembali form Anda', 'VALIDASI GAGAL');
            } else {
              toastr.error('Terjadi kesalahan saat menyimpan data', 'GAGAL');
            }
          },
          complete: function() {
            DOM.addSubmitButton.removeAttribute('data-kt-indicator');
            DOM.addSubmitButton.disabled = false;
          }
        });
      });
    }

    // Auto show modal if validation errors exist
    @if ($errors->any() && old('_token'))
      $('#kt_modal_add_akun').modal('show');
    @endif
  });
</script>
