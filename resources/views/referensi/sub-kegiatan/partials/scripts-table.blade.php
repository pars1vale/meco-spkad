<script>
  document.addEventListener("DOMContentLoaded", function() {
    // 1. Buat instance grouping engine
    const groupingEngine = new DataTableGroupingEngine({
      tableId: 'kt_sub_kegiatan_table',
      groupingLevels: [{
          columnIndex: 3,
          className: 'bg-light-primary',
          label: 'Urusan'
        },
        {
          columnIndex: 4,
          className: 'bg-secondary',
          label: 'Bidang Urusan'
        },
        {
          columnIndex: 5,
          className: 'bg-light',
          label: 'Program'
        },
        {
          columnIndex: 6,
          className: 'bg-light-warning',
          label: 'Kegiatan'
        }
      ]
    });

    // 2. Inisialisasi DataTable
    const table = $('#kt_sub_kegiatan_table').DataTable({
      responsive: true,
      searchDelay: 500,
      processing: true,
      serverSide: false,
      order: [
        [3, 'asc'],
        [4, 'asc'],
        [5, 'asc'],
        [6, 'asc'],
        [1, 'asc']
      ],
      columnDefs: [{
          targets: [0],
          orderable: false,
          className: 'text-center'
        },
        {
          targets: [3, 4, 5, 6],
          visible: false
        },
        {
          targets: [1, 2],
          className: 'fs-6'
        },
        {
          targets: [7],
          orderable: false,
          className: 'text-end'
        }
      ],
      dom: "<'row'<'col-sm-12'tr>>" +
        "<'row mt-4'" +
        "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-start'li>" +
        "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-end'p>" +
        ">",
      language: {
        paginate: {
          previous: '<i class="ki-outline ki-arrow-left fs-4"></i>',
          next: '<i class="ki-outline ki-arrow-right fs-4"></i>'
        }
      },
      drawCallback: function(settings) {
        const api = this.api();
        const rows = api.rows({
          page: 'current'
        }).nodes();
        groupingEngine.renderGroupHeaders(api, rows);
      }
    });

    // 3. Set table & attach handlers
    groupingEngine.setTable(table);
    groupingEngine.attachGroupClickHandlers(table);

    // Search
    $('#kt_datatable_search_input').keyup(function() {
      table.search(this.value).draw();
    });

    // Session messages
    const sessionMessages = document.querySelectorAll('#session-messages div');
    sessionMessages.forEach(msg => {
      const type = msg.dataset.type;
      const message = msg.dataset.message;
      Swal.fire({
        icon: type,
        title: type === 'success' ? 'Berhasil' : 'Gagal',
        text: message,
        confirmButtonText: 'OK',
        buttonsStyling: false,
        customClass: {
          confirmButton: "btn btn-primary"
        }
      });
    });

    // Delete handler
    const deleteHandler = new DeleteHandler({
      tableId: 'kt_sub_kegiatan_table',
      deleteRoute: '{{ route('sub-kegiatan.bulk-delete') }}',
      deleteMessage: 'Data sub kegiatan'
    });
    deleteHandler.init();

    // Checkbox logic
    initCheckboxLogic('kt_sub_kegiatan_table');
  });
</script>
