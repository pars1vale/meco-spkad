<script>
  document.addEventListener("DOMContentLoaded", function() {
    // === Switch logic untuk tipe akun utama (mutual exclusive) ===
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

    // === Toggle buttons logic untuk kategori khusus (multiple selection) ===
    const kategoriToggles = document.querySelectorAll('.kategori-toggle');

    kategoriToggles.forEach(button => {
      button.addEventListener('click', function() {
        const field = this.getAttribute('data-field');
        const input = document.querySelector(`.kategori-input[data-field="${field}"]`);
        const icon = this.querySelector('i');

        // Toggle state
        if (input.value === '0') {
          input.value = '1';

          // Remove all light variants
          this.classList.remove('btn-light-primary', 'btn-light-warning', 'btn-light-success',
            'btn-light-info', 'btn-light-secondary', 'btn-light-danger');

          // Add active color based on field type
          if (field.includes('hibah')) {
            this.classList.add('btn-warning');
          } else if (field.includes('sosial')) {
            this.classList.add('btn-success');
          } else if (field.includes('subsidi') || field.includes('bagi')) {
            this.classList.add('btn-info');
          } else if (field.includes('modal')) {
            this.classList.add('btn-danger');
          } else if (field.includes('bunga')) {
            this.classList.add('btn-secondary');
          } else {
            this.classList.add('btn-primary');
          }

          icon.classList.remove('d-none');
        } else {
          input.value = '0';

          // Remove all active variants
          this.classList.remove('btn-primary', 'btn-warning', 'btn-success',
            'btn-info', 'btn-secondary', 'btn-danger');

          // Restore original light color
          if (field.includes('hibah')) {
            this.classList.add('btn-light-warning');
          } else if (field.includes('sosial')) {
            this.classList.add('btn-light-success');
          } else if (field.includes('subsidi') || field.includes('bagi')) {
            this.classList.add('btn-light-info');
          } else if (field.includes('modal')) {
            this.classList.add('btn-light-danger');
          } else if (field.includes('bunga')) {
            this.classList.add('btn-light-secondary');
          } else {
            this.classList.add('btn-light-primary');
          }

          icon.classList.add('d-none');
        }
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
            text: !hasTypeSelected ? 'Anda harus memilih salah satu tipe akun utama!' : 'Semua field wajib diisi!',
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
