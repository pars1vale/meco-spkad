// DataTableGroupingEngine
class DataTableGroupingEngine {
    constructor(config) {
        this.tableId = config.tableId;
        this.groupingLevels = config.groupingLevels;
        this.table = null;
    }

    renderGroupHeaders(api, rows) {
        const lastValues = {};
        this.groupingLevels.forEach(level => {
            lastValues[level.columnIndex] = null;
        });

        api.column(this.groupingLevels[0].columnIndex, { page: 'current' }).data().each((value, i) => {
            const levelData = {};
            this.groupingLevels.forEach(level => {
                levelData[level.columnIndex] = api.cell(rows[i], level.columnIndex).data();
            });

            for (let j = 0; j < this.groupingLevels.length; j++) {
                const level = this.groupingLevels[j];
                const currentValue = levelData[level.columnIndex];
                const lastValue = lastValues[level.columnIndex];

                if (lastValue !== currentValue) {
                    for (let k = j + 1; k < this.groupingLevels.length; k++) {
                        lastValues[this.groupingLevels[k].columnIndex] = null;
                    }
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

// DeleteHandler
class DeleteHandler {
    constructor(config) {
        this.tableId = config.tableId;
        this.deleteRoute = config.deleteRoute;
        this.deleteMessage = config.deleteMessage || 'Data ini';
    }

    init() {
        this.initSingleDelete();
        this.initBulkDelete();
    }

    initSingleDelete() {
        $(`#${this.tableId}`).on('click', '.delete-btn', (e) => {
            e.preventDefault();
            const form = $(e.target).closest('form');
            const name = $(e.target).closest('.delete-btn').data('name');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                html: `${this.deleteMessage} <strong>"${name}"</strong> akan dihapus!`,
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
    }

    initBulkDelete() {
        const bulkBtn = document.getElementById('bulk_delete_btn');
        if (!bulkBtn) return;

        bulkBtn.addEventListener('click', () => {
            const checkedBoxes = document.querySelectorAll(
                `#${this.tableId} tbody input[type="checkbox"]:checked`
            );

            if (checkedBoxes.length === 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'Tidak ada data dipilih',
                    text: 'Pilih minimal satu data untuk dihapus.',
                    confirmButtonText: 'OK',
                    buttonsStyling: false,
                    customClass: { confirmButton: "btn btn-primary" }
                });
                return;
            }

            Swal.fire({
                title: 'Apakah Anda yakin?',
                html: `Anda akan menghapus <strong>${checkedBoxes.length}</strong> ${this.deleteMessage} terpilih!`,
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
                    form.action = this.deleteRoute;

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = document.querySelector('meta[name="csrf-token"]').content;
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
    }
}

// CheckboxHandler
function initCheckboxLogic(tableId) {
    const checkboxes = document.querySelectorAll(`#${tableId} tbody input[type="checkbox"]`);
    const masterCheckbox = document.querySelector(`#${tableId} thead input[type="checkbox"]`);
    const selectedToolbar = document.querySelector('[data-kt-customer-table-toolbar="selected"]');
    const baseToolbar = document.querySelector('[data-kt-customer-table-toolbar="base"]');
    const selectedCount = document.querySelector('[data-kt-customer-table-select="selected_count"]');

    function updateToolbar() {
        const checkedBoxes = document.querySelectorAll(`#${tableId} tbody input[type="checkbox"]:checked`);
        if (checkedBoxes.length > 0) {
            selectedCount.textContent = checkedBoxes.length;
            baseToolbar.classList.add('d-none');
            selectedToolbar.classList.remove('d-none');
        } else {
            baseToolbar.classList.remove('d-none');
            selectedToolbar.classList.add('d-none');
        }
    }

    if (masterCheckbox) {
        masterCheckbox.addEventListener('change', function () {
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateToolbar();
        });
    }

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            updateToolbar();
            const checkedBoxes = document.querySelectorAll(`#${tableId} tbody input[type="checkbox"]:checked`);
            if (masterCheckbox) {
                masterCheckbox.checked = checkedBoxes.length === checkboxes.length;
                masterCheckbox.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < checkboxes.length;
            }
        });
    });
}