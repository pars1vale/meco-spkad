<script>
  document.addEventListener("DOMContentLoaded", function() {
    // 1. Buat instance grouping engine dulu
    const groupingEngine = new DataTableGroupingEngine({
      tableId: 'kt_bidang_urusan_table',
      groupingLevels: [{
        columnIndex: 3,
        className: 'bg-light-primary',
        label: 'Urusan'
      }]
    });

    // 2. Inisialisasi DataTable
    const table = $('#kt_bidang_urusan_table').DataTable({
      responsive: true,
      searchDelay: 500,
      processing: true,
      serverSide: false,
      order: [
        [3, 'asc'],
        [1, 'asc']
      ],
      columnDefs: [{
          targets: [0],
          orderable: false,
          className: 'text-center'
        },
        {
          targets: [3],
          visible: false
        },
        {
          targets: [1, 2],
          className: 'fs-6'
        },
        {
          targets: [4],
          orderable: false,
          className: 'text-end'
        }
      ],
      dom: "<'row'<'col-sm-12'tr>><'row mt-4'<'col-sm-12 col-md-5 d-flex align-items-center justify-content-start'li><'col-sm-12 col-md-7 d-flex align-items-center justify-content-end'p>>",
      language: {
        paginate: {
          previous: '<i class="ki-outline ki-arrow-left fs-4"></i>',
          next: '<i class="ki-outline ki-arrow-right fs-4"></i>'
        }
      },
      drawCallback: function(settings) {
        // 4. Panggil grouping logic di sini (bukan via getDrawCallback)
        const api = this.api();
        const rows = api.rows({
          page: 'current'
        }).nodes();
        groupingEngine.renderGroupHeaders(api, rows);
      }
    });

    // 3. Set table ke engine (untuk click handlers)
    groupingEngine.setTable(table);
    groupingEngine.attachGroupClickHandlers(table);

    // Search functionality
    $('#kt_datatable_search_input').keyup(function() {
      table.search(this.value).draw();
    });

    // Delete handler
    const deleteHandler = new DeleteHandler({
      tableId: 'kt_bidang_urusan_table',
      deleteRoute: '{{ route('bidang-urusan.bulk-delete') }}',
      deleteMessage: 'Data bidang urusan'
    });
    deleteHandler.init();

    // Checkbox logic
    initCheckboxLogic('kt_bidang_urusan_table');
  });
</script>
