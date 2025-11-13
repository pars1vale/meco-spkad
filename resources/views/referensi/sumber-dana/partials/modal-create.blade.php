<!-- Modal Tambah Sumber Dana -->
<div class="modal fade" id="kt_modal_add_sumberdana" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered mw-650px">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="fw-bold">Tambah Sumber Dana</h2>
        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
          <i class="ki-outline ki-cross fs-1"></i>
        </div>
      </div>

      <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
        <form id="kt_modal_add_sumberdana_form">
          @csrf
          <div class="d-flex flex-column scroll-y me-n7 pe-7">

            <!-- Kode Dana -->
            <div class="fv-row mb-7">
              <label class="required fw-semibold fs-6 mb-2">Kode Dana</label>
              <input type="text" name="kode_dana" class="form-control form-control-solid" placeholder="Masukkan Kode Dana" maxlength="50"
                required />
              <div class="invalid-feedback"></div>
              <div class="form-text">Maksimal 50 karakter</div>
            </div>

            <!-- Nama Dana -->
            <div class="fv-row mb-7">
              <label class="required fw-semibold fs-6 mb-2">Nama Dana</label>
              <input type="text" name="nama_dana" class="form-control form-control-solid" placeholder="Masukkan Nama Dana" maxlength="255"
                required />
              <div class="invalid-feedback"></div>
              <div class="form-text">Maksimal 255 karakter</div>
            </div>

            <!-- Sumber Dana -->
            <div class="fv-row mb-7">
              <label class="fw-semibold fs-6 mb-2">Sumber Dana</label>
              <textarea name="sumber_dana" class="form-control form-control-solid" rows="3" placeholder="Masukkan Sumber Dana (opsional)"></textarea>
              <div class="form-text">Opsional - bisa dikosongkan</div>
            </div>

          </div>

          <div class="text-center pt-10">
            <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary" id="kt_modal_add_sumberdana_submit">
              <span class="indicator-label">Simpan</span>
              <span class="indicator-progress">Menyimpan...
                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
              </span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById('kt_modal_add_sumberdana_form');
    const submitButton = document.getElementById('kt_modal_add_sumberdana_submit');

    if (form && submitButton) {
      form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Validate required fields
        const kodeDana = form.querySelector('input[name="kode_dana"]').value.trim();
        const namaDana = form.querySelector('input[name="nama_dana"]').value.trim();

        if (!kodeDana || !namaDana) {
          toastr.error('Kode Dana dan Nama Dana wajib diisi!', 'VALIDASI GAGAL');
          return;
        }

        // Show loading state
        submitButton.setAttribute('data-kt-indicator', 'on');
        submitButton.disabled = true;

        // Clear previous errors
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');

        // Get form data
        const formData = new FormData(form);

        // Submit via AJAX
        $.ajax({
          url: "{{ route('referensi.sumber-dana.store') }}",
          method: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function(response) {
            $('#kt_modal_add_sumberdana').modal('hide');
            form.reset();

            toastr.success(response.message || 'Data berhasil disimpan!', 'BERHASIL');

            setTimeout(() => {
              location.reload();
            }, 1000);
          },
          error: function(xhr) {
            if (xhr.status === 422) {
              const errors = xhr.responseJSON.errors;
              Object.keys(errors).forEach(field => {
                const input = form.querySelector(`[name="${field}"]`);
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
            submitButton.removeAttribute('data-kt-indicator');
            submitButton.disabled = false;
          }
        });
      });
    }

    // Auto show modal if validation errors exist
    @if ($errors->any() && old('_token'))
      $('#kt_modal_add_sumberdana').modal('show');
    @endif
  });
</script>
