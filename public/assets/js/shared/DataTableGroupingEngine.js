/**
 * Generic Nested Grouping Engine untuk DataTable
 * Bisa handle 1 level, 2 level, 3 level, atau lebih
 */
class DataTableGroupingEngine {
    constructor(config) {
        this.tableId = config.tableId;
        this.groupingLevels = config.groupingLevels; // Array config untuk setiap level
        this.table = null;
    }

    /**
     * groupingLevels format:
     * [
     *   { columnIndex: 3, className: 'bg-light-primary', label: 'Urusan' },
     *   { columnIndex: 4, className: 'bg-secondary', label: 'Bidang Urusan' },
     *   { columnIndex: 5, className: 'bg-light', label: 'Program' },
     *   { columnIndex: 6, className: 'bg-light-warning', label: 'Kegiatan' }
     * ]
     */

    renderGroupHeaders(api, rows) {
        const lastValues = {}; // Track last value untuk setiap level

        // Initialize semua level dengan null
        this.groupingLevels.forEach(level => {
            lastValues[level.columnIndex] = null;
        });

        // Loop setiap row untuk render headers
        api.column(this.groupingLevels[0].columnIndex, { page: 'current' }).data().each((value, i) => {
            // Collect data dari semua levels
            const levelData = {};
            this.groupingLevels.forEach(level => {
                levelData[level.columnIndex] = api.cell(rows[i], level.columnIndex).data();
            });

            // Render headers dari level tertinggi ke terendah
            for (let j = 0; j < this.groupingLevels.length; j++) {
                const level = this.groupingLevels[j];
                const currentValue = levelData[level.columnIndex];
                const lastValue = lastValues[level.columnIndex];

                if (lastValue !== currentValue) {
                    // Ada perubahan di level ini
                    // Reset semua level yang lebih dalam (lebih tinggi index)
                    for (let k = j + 1; k < this.groupingLevels.length; k++) {
                        lastValues[this.groupingLevels[k].columnIndex] = null;
                    }

                    // Render header untuk level ini
                    this.renderHeader(rows[i], level, currentValue);
                    lastValues[level.columnIndex] = currentValue;
                }
            }
        });
    }

    renderHeader(row, level, value) {
        const colspan = this.calculateColspan();
        const html = `
      <tr class="group ${level.className}" data-level="${level.columnIndex}">
        <td colspan="${colspan}" class="fw-bold fs-5 px-4 py-3">${value}</td>
      </tr>
    `;
        $(row).before(html);
    }

    calculateColspan() {
        // +1 untuk action column
        return this.groupingLevels[this.groupingLevels.length - 1].columnIndex + 2;
    }

    attachGroupClickHandlers(table) {
        $(`#${this.tableId}`).on('click', 'tr.group', (e) => {
            const level = $(e.currentTarget).data('level');
            const currentOrder = table.order()[0];

            if (currentOrder[0] === level && currentOrder[1] === 'asc') {
                table.order([level, 'desc']).draw();
            } else {
                table.order([level, 'asc']).draw();
            }
        });
    }

    getDrawCallback() {
        return (settings) => {
            const api = this.table.api();
            const rows = api.rows({ page: 'current' }).nodes();
            this.renderGroupHeaders(api, rows);
        };
    }

    setTable(table) {
        this.table = table;
    }
}