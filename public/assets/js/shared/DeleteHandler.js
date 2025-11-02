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