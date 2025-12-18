<script>
  // Cache DOM elements
  const DOM = {
    table: null,
    searchInput: null,
    addForm: null,
    addSubmitButton: null,
    masterCheckbox: null,
    checkboxes: null,
    selectedToolbar: null,
    baseToolbar: null,
    selectedCount: null,
    bulkDeleteBtn: null
  };

  document.addEventListener("DOMContentLoaded", function() {
    // Initialize cached DOM elements
    DOM.table = $('#kt_satuan_table');
    DOM.searchInput = document.getElementById('kt_datatable_search_input');
    DOM.addForm = document.getElementById('kt_modal_add_satuan_form');
    DOM.addSubmitButton = document.getElementById('kt_modal_add_satuan_submit');
    DOM.masterCheckbox = document.querySelector('#kt_satuan_table thead input[type="checkbox"]');
    DOM.checkboxes = document.querySelectorAll('#kt_satuan_table tbody input[type="checkbox"]');
    DOM.selectedToolbar = document.querySelector('[data-kt-customer-table-toolbar="selected"]');
    DOM.baseToolbar = document.querySelector('[data-kt-customer-table-toolbar="base"]');
    DOM.selectedCount = document.querySelector('[data-kt-customer-table-select="selected_count"]');
    DOM.bulkDeleteBtn = document.getElementById('bulk_delete_btn');

    // Initialize DataTable
    var tableInstance = DOM.table.DataTable({
      responsive: true,
      searchDelay: 500,
      processing: true,
      serverSide: false,
      columnDefs: [{
          targets: [0],
          orderable: false,
          className: 'text-center'
        },
        {
          targets: [4],
          orderable: false,
          className: 'text-end'
        }
      ]
    });

    // Search functionality
    if (DOM.searchInput) {
      DOM.searchInput.addEventListener('keyup', function() {
        tableInstance.search(this.value).draw();
      });
    }

    // Configure Toastr options
    toastr.options = {
      "closeButton": true,
      "debug": false,
      "newestOnTop": false,
      "progressBar": true,
      "positionClass": "toastr-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "1000",
      "timeOut": "5000",
      "extendedTimeOut": "1000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    };

    // Session Messages dengan Toaster
    const sessionMessages = document.querySelectorAll('#session-messages div');
    sessionMessages.forEach(msg => {
      const type = msg.dataset.type;
      const message = msg.dataset.message;

      if (type === 'error') {
        toastr.error(message, "GAGAL");
      } else if (type === 'success') {
        toastr.success(message, "BERHASIL");
      } else {
        toastr.info(message);
      }
    });

    // Event delegation untuk delete buttons
    DOM.table.on('click', '.delete-btn', function(e) {
      e.preventDefault();
      const form = $(this).closest('form');
      const name = $(this).data('name');

      Swal.fire({
        title: 'Apakah Anda yakin?',
        html: `Data satuan <strong>"${name}"</strong> akan dihapus!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal',
        buttonsStyling: false,
        customClass: {
          confirmButton: "btn btn-danger",
          cancelButton: "btn btn-secondary"
        }
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit();
        }
      });
    });

    // Bulk selection functionality
    function updateToolbar() {
      const checkedBoxes = document.querySelectorAll('#kt_satuan_table tbody input[type="checkbox"]:checked');

      if (checkedBoxes.length > 0) {
        DOM.selectedCount.textContent = checkedBoxes.length;
        DOM.baseToolbar?.classList.add('d-none');
        DOM.selectedToolbar?.classList.remove('d-none');
      } else {
        DOM.baseToolbar?.classList.remove('d-none');
        DOM.selectedToolbar?.classList.add('d-none');
      }
    }

    // Master checkbox
    if (DOM.masterCheckbox) {
      DOM.masterCheckbox.addEventListener('change', function() {
        DOM.checkboxes.forEach(checkbox => {
          checkbox.checked = this.checked;
        });
        updateToolbar();
      });
    }

    // Individual checkboxes
    DOM.checkboxes.forEach(checkbox => {
      checkbox.addEventListener('change', function() {
        updateToolbar();
        const checkedBoxes = document.querySelectorAll('#kt_satuan_table tbody input[type="checkbox"]:checked');
        if (DOM.masterCheckbox) {
          DOM.masterCheckbox.checked = checkedBoxes.length === DOM.checkboxes.length;
          DOM.masterCheckbox.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < DOM.checkboxes.length;
        }
      });
    });

    // Bulk delete
    DOM.bulkDeleteBtn?.addEventListener('click', function() {
      const checkedBoxes = document.querySelectorAll('#kt_satuan_table tbody input[type="checkbox"]:checked');

      if (checkedBoxes.length === 0) {
        toastr.info('Pilih minimal satu satuan untuk dihapus.', 'INFORMASI');
        return;
      }

      Swal.fire({
        title: 'Apakah Anda yakin?',
        html: `Anda akan menghapus <strong>${checkedBoxes.length}</strong> data satuan terpilih!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal',
        buttonsStyling: false,
        customClass: {
          confirmButton: "btn btn-danger",
          cancelButton: "btn btn-secondary"
        }
      }).then((result) => {
        if (result.isConfirmed) {
          const ids = Array.from(checkedBoxes).map(cb => cb.value);

          const form = document.createElement('form');
          form.method = 'POST';
          form.action = '{{ route('satuan.bulk-delete') }}';

          const csrfToken = document.createElement('input');
          csrfToken.type = 'hidden';
          csrfToken.name = '_token';
          csrfToken.value = '{{ csrf_token() }}';
          form.appendChild(csrfToken);

          ids.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = id;
            form.appendChild(input);
          });

          document.body.appendChild(form);
          form.submit();
        }
      });
    });

    // AJAX Form Submission
    if (DOM.addForm && DOM.addSubmitButton) {
      DOM.addForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const namaSatuan = formData.get('nama_satuan');

        if (!namaSatuan || !namaSatuan.trim()) {
          toastr.error('Nama satuan harus diisi!', 'VALIDASI GAGAL');
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
          url: "{{ route('satuan.store') }}",
          method: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function(response) {
            $('#kt_modal_add_satuan').modal('hide');
            DOM.addForm.reset();

            toastr.success(response.message || 'Data berhasil disimpan!', 'BERHASIL', {
              timeOut: 2000,
              onHidden: function() {
                location.reload();
              }
            });
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
      $('#kt_modal_add_satuan').modal('show');
    @endif
  });
</script>
