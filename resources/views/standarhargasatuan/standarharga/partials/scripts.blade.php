<script>
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
    bulkDeleteBtn: null,
    filterTipe: null,
    filterTahun: null,
    filterLock: null,
    tipeRadios: null,
    kelompokSelect: null
  };

  document.addEventListener("DOMContentLoaded", function() {
    // Initialize DOM elements
    DOM.table = $('#kt_ssh_table');
    DOM.searchInput = document.getElementById('kt_datatable_search_input');
    DOM.addForm = document.getElementById('kt_modal_add_ssh_form');
    DOM.addSubmitButton = document.getElementById('kt_modal_add_ssh_submit');
    DOM.masterCheckbox = document.querySelector('#kt_ssh_table thead input[type="checkbox"]');
    DOM.checkboxes = document.querySelectorAll('#kt_ssh_table tbody .row-checkbox');
    DOM.selectedToolbar = document.querySelector('[data-kt-customer-table-toolbar="selected"]');
    DOM.baseToolbar = document.querySelector('[data-kt-customer-table-toolbar="base"]');
    DOM.selectedCount = document.querySelector('[data-kt-customer-table-select="selected_count"]');
    DOM.bulkDeleteBtn = document.getElementById('bulk_delete_btn');
    DOM.filterTipe = document.getElementById('filter_tipe');
    DOM.filterTahun = document.getElementById('filter_tahun');
    DOM.filterLock = document.getElementById('filter_lock');
    DOM.tipeRadios = document.querySelectorAll('.tipe-ssh-radio');
    DOM.kelompokSelect = document.getElementById('kelompok_select');

    // Initialize DataTable
    var tableInstance = DOM.table.DataTable({
      responsive: true,
      searchDelay: 500,
      processing: true,
      serverSide: false,
      order: [
        [1, 'asc']
      ],
      columnDefs: [{
          targets: [0],
          orderable: false,
          className: 'text-center'
        },
        {
          targets: [9],
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

    // Filter by Tipe
    if (DOM.filterTipe) {
      DOM.filterTipe.addEventListener('change', function() {
        tableInstance.column(3).search(this.value).draw();
      });
    }

    // Filter by Tahun
    if (DOM.filterTahun) {
      DOM.filterTahun.addEventListener('change', function() {
        tableInstance.column(7).search(this.value).draw();
      });
    }

    // Filter by Lock Status
    if (DOM.filterLock) {
      DOM.filterLock.addEventListener('change', function() {
        const value = this.value;

        if (value === '') {
          tableInstance.column(8).search('').draw();
        } else if (value === '1') {
          tableInstance.column(8).search('Terkunci').draw();
        } else {
          tableInstance.column(8).search('Tidak Terkunci').draw();
        }
      });
    }

    // Toastr config
    toastr.options = {
      "closeButton": true,
      "progressBar": true,
      "positionClass": "toastr-top-right",
      "timeOut": "5000"
    };

    // Session messages
    const sessionMessages = document.querySelectorAll('#session-messages div');
    sessionMessages.forEach(msg => {
      const type = msg.dataset.type;
      const message = msg.dataset.message;

      if (type === 'error') {
        toastr.error(message, "GAGAL");
      } else if (type === 'success') {
        toastr.success(message, "BERHASIL");
      }
    });

    // Initialize Select2 in modal
    $('#kt_modal_add_ssh').on('shown.bs.modal', function() {
      $(this).find('select[data-control="select2"]').each(function() {
        if (!$(this).hasClass('select2-hidden-accessible')) {
          $(this).select2({
            placeholder: $(this).data('placeholder') || 'Pilih data',
            dropdownParent: $('#kt_modal_add_ssh'),
            allowClear: true,
            width: '100%'
          });
        }
      });
    });

    // Reset modal on hide
    $('#kt_modal_add_ssh').on('hidden.bs.modal', function() {
      if (DOM.addForm) {
        DOM.addForm.reset();
        DOM.addForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        DOM.addForm.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
      }

      if (DOM.kelompokSelect) {
        $(DOM.kelompokSelect).val('').trigger('change');
      }

      DOM.tipeRadios.forEach(radio => radio.checked = false);
    });

    // Filter kelompok by tipe
    DOM.tipeRadios.forEach(radio => {
      radio.addEventListener('change', function() {
        const tipe = this.value;
        const kelompokOptions = DOM.kelompokSelect.querySelectorAll('option');

        kelompokOptions.forEach(option => {
          if (option.value === '') {
            option.style.display = 'block';
          } else {
            const optionTipe = option.getAttribute('data-tipe');
            option.style.display = (optionTipe === tipe) ? 'block' : 'none';
          }
        });

        $(DOM.kelompokSelect).val('').trigger('change');
      });
    });

    // Toggle lock status
    $(document).on('change', '.toggle-lock', function() {
      const id = $(this).data('id');
      const checkbox = $(this);
      const label = $(this).next('label');

      $.ajax({
        url: `/standarHarga/data_ssh/${id}/toggle-lock`,
        method: 'POST',
        data: {
          _token: '{{ csrf_token() }}'
        },
        success: function(response) {
          if (response.success) {
            toastr.success(response.message, 'BERHASIL');

            if (response.is_locked) {
              label.text('Terkunci');
            } else {
              label.text('Tidak Terkunci');
            }
          }
        },
        error: function(xhr) {
          toastr.error(xhr.responseJSON?.message || 'Gagal mengubah status', 'GAGAL');
          checkbox.prop('checked', !checkbox.prop('checked'));
        }
      });
    });

    // Delete button
    DOM.table.on('click', '.delete-btn', function(e) {
      e.preventDefault();
      const form = $(this).closest('form');
      const name = $(this).data('name');

      Swal.fire({
        title: 'Apakah Anda yakin?',
        html: `Data SSH <strong>"${name}"</strong> akan dihapus!`,
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

    // Bulk selection
    function updateToolbar() {
      const checkedBoxes = document.querySelectorAll('#kt_ssh_table tbody .row-checkbox:checked');

      if (checkedBoxes.length > 0) {
        DOM.selectedCount.textContent = checkedBoxes.length;
        DOM.baseToolbar?.classList.add('d-none');
        DOM.selectedToolbar?.classList.remove('d-none');
      } else {
        DOM.baseToolbar?.classList.remove('d-none');
        DOM.selectedToolbar?.classList.add('d-none');
      }
    }

    if (DOM.masterCheckbox) {
      DOM.masterCheckbox.addEventListener('change', function() {
        const enabledCheckboxes = document.querySelectorAll('#kt_ssh_table tbody .row-checkbox:not(:disabled)');
        enabledCheckboxes.forEach(cb => cb.checked = this.checked);
        updateToolbar();
      });
    }

    DOM.table.on('change', 'tbody .row-checkbox', function() {
      updateToolbar();
      const allCheckboxes = document.querySelectorAll('#kt_ssh_table tbody .row-checkbox:not(:disabled)');
      const checkedBoxes = document.querySelectorAll('#kt_ssh_table tbody .row-checkbox:checked');

      if (DOM.masterCheckbox) {
        DOM.masterCheckbox.checked = checkedBoxes.length === allCheckboxes.length && allCheckboxes.length > 0;
        DOM.masterCheckbox.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < allCheckboxes.length;
      }
    });

    // Bulk delete
    DOM.bulkDeleteBtn?.addEventListener('click', function() {
      const checkedBoxes = document.querySelectorAll('#kt_ssh_table tbody .row-checkbox:checked');

      if (checkedBoxes.length === 0) {
        toastr.info('Pilih minimal satu data untuk dihapus.', 'INFORMASI');
        return;
      }

      Swal.fire({
        title: 'Apakah Anda yakin?',
        html: `Anda akan menghapus <strong>${checkedBoxes.length}</strong> data SSH terpilih!`,
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
          form.action = '{{ route('data_ssh.bulk-delete') }}';

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

    // AJAX Form submission
    if (DOM.addForm && DOM.addSubmitButton) {
      DOM.addForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        // Validation
        const tipeChecked = document.querySelector('.tipe-ssh-radio:checked');
        if (!tipeChecked) {
          toastr.error('Pilih tipe standar harga terlebih dahulu!', 'VALIDASI GAGAL');
          return;
        }

        DOM.addSubmitButton.setAttribute('data-kt-indicator', 'on');
        DOM.addSubmitButton.disabled = true;

        DOM.addForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        DOM.addForm.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');

        $.ajax({
          url: "{{ route('data_ssh.store') }}",
          method: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function(response) {
            $('#kt_modal_add_ssh').modal('hide');
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
              toastr.error(xhr.responseJSON?.message || 'Terjadi kesalahan', 'GAGAL');
            }
          },
          complete: function() {
            DOM.addSubmitButton.removeAttribute('data-kt-indicator');
            DOM.addSubmitButton.disabled = false;
          }
        });
      });
    }
  });
</script>
