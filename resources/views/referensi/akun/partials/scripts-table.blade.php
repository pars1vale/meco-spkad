<script>
  document.addEventListener("DOMContentLoaded", function() {
    // Cache DOM elements
    const DOM = {
      table: $('#kt_akun_table'),
      searchInput: document.getElementById('kt_datatable_search_input'),
      masterCheckbox: document.querySelector('#kt_akun_table thead input[type="checkbox"]'),
      checkboxes: document.querySelectorAll('#kt_akun_table tbody input[type="checkbox"]'),
      selectedToolbar: document.querySelector('[data-kt-customer-table-toolbar="selected"]'),
      baseToolbar: document.querySelector('[data-kt-customer-table-toolbar="base"]'),
      selectedCount: document.querySelector('[data-kt-customer-table-select="selected_count"]'),
      bulkDeleteBtn: document.getElementById('bulk_delete_btn')
    };

    // Initialize DataTable
    const tableInstance = DOM.table.DataTable({
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
          targets: [6],
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

    // Delete button dengan event delegation
    DOM.table.on('click', '.btn-light-danger', function(e) {
      e.preventDefault();
      const form = $(this).closest('form');
      const name = $(this).data('name');

      Swal.fire({
        title: 'Apakah Anda yakin?',
        html: `Data akun <strong>"${name}"</strong> akan dihapus!`,
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
      const checkedBoxes = document.querySelectorAll('#kt_akun_table tbody input[type="checkbox"]:checked');

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
        const checkedBoxes = document.querySelectorAll('#kt_akun_table tbody input[type="checkbox"]:checked');
        if (DOM.masterCheckbox) {
          DOM.masterCheckbox.checked = checkedBoxes.length === DOM.checkboxes.length;
          DOM.masterCheckbox.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < DOM.checkboxes.length;
        }
      });
    });

    // Bulk delete
    DOM.bulkDeleteBtn?.addEventListener('click', function() {
      const checkedBoxes = document.querySelectorAll('#kt_akun_table tbody input[type="checkbox"]:checked');

      if (checkedBoxes.length === 0) {
        Swal.fire({
          icon: 'info',
          title: 'Tidak ada data dipilih',
          text: 'Pilih minimal satu akun untuk dihapus.',
          confirmButtonText: 'OK',
          buttonsStyling: false,
          customClass: {
            confirmButton: "btn btn-primary"
          }
        });
        return;
      }

      Swal.fire({
        title: 'Apakah Anda yakin?',
        html: `Anda akan menghapus <strong>${checkedBoxes.length}</strong> data akun terpilih!`,
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
          form.action = '{{ route('akun.bulk-delete') }}';

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
  });
</script>
