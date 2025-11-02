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