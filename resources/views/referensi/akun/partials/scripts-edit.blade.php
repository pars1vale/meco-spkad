<script>
  document.addEventListener("DOMContentLoaded", function() {
    // === Switch logic untuk form edit ===
    const akunTypeSwitches = document.querySelectorAll('.akun-type-switch');
    const tipeAkunError = document.getElementById('tipe-akun-error-edit');

    akunTypeSwitches.forEach(switchEl => {
      switchEl.addEventListener('change', function() {
        if (this.checked) {
          // Matikan switch lainnya
          akunTypeSwitches.forEach(otherSwitch => {
            if (otherSwitch !== this) {
              otherSwitch.checked = false;
            }
          });
        }
        // Reset error message
        tipeAkunError?.classList.add('d-none');
      });
    });

    // === Form validation ===
    const editForm = document.getElementById('edit_akun_form');
    const updateButton = document.getElementById('update_akun_btn');

    if (editForm && updateButton) {
      editForm.addEventListener('submit', function(e) {
        const kodeAkun = editForm.querySelector('input[name="kode_akun"]').value.trim();
        const namaAkun = editForm.querySelector('textarea[name="nama_akun"]').value.trim();

        // Check if at least one switch is selected
        const pendapatanChecked = editForm.querySelector('#pendapatanSwitchEdit').checked;
        const belanjaChecked = editForm.querySelector('#belanjaSwitchEdit').checked;
        const pembiayaanChecked = editForm.querySelector('#pembiayaanSwitchEdit').checked;

        const hasTypeSelected = pendapatanChecked || belanjaChecked || pembiayaanChecked;

        if (!kodeAkun || !namaAkun || !hasTypeSelected) {
          e.preventDefault();

          if (!hasTypeSelected) {
            tipeAkunError?.classList.remove('d-none');
          }

          Swal.fire({
            icon: 'error',
            title: 'Validasi gagal',
            text: !hasTypeSelected ? 'Anda harus memilih salah satu tipe akun!' : 'Semua field wajib diisi!',
            confirmButtonText: 'OK',
            buttonsStyling: false,
            customClass: {
              confirmButton: "btn btn-primary"
            }
          });
          return;
        }

        updateButton.setAttribute('data-kt-indicator', 'on');
        updateButton.disabled = true;
      });
    }
  });
</script>
