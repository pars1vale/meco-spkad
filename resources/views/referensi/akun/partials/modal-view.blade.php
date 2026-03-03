<!-- Modal View Detail Akun -->
<div class="modal fade" id="kt_modal_view_akun" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered mw-900px">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="fw-bold">Detail Akun</h2>
        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
          <i class="ki-outline ki-cross fs-1"></i>
        </div>
      </div>

      <div class="modal-body py-10 px-lg-17">
        <div id="modal_view_content">
          <!-- Content will be loaded via AJAX -->
          <div class="text-center py-10">
            <span class="spinner-border spinner-border-lg align-middle"></span>
            <p class="mt-3">Memuat data...</p>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<script>
  // Function untuk attach view listeners
  function attachViewListeners() {
    const viewButtons = document.querySelectorAll('.view-btn');

    viewButtons.forEach(button => {
      button.onclick = function(e) {
        e.preventDefault();
        const id = this.getAttribute('data-id');
        const modal = new bootstrap.Modal(document.getElementById('kt_modal_view_akun'));
        const modalBody = document.getElementById('modal_view_content');

        // Show loading
        modalBody.innerHTML = `
          <div class="text-center py-10">
            <span class="spinner-border spinner-border-lg align-middle"></span>
            <p class="mt-3">Memuat data...</p>
          </div>
        `;

        modal.show();

        // Fetch detail
        fetch(`{{ url('referensi/akun') }}/${id}/detail`)
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              renderAkunDetail(data.data);
            } else {
              modalBody.innerHTML = `
                <div class="alert alert-danger">
                  <strong>Error:</strong> ${data.message}
                </div>
              `;
            }
          })
          .catch(error => {
            modalBody.innerHTML = `
              <div class="alert alert-danger">
                <strong>Error:</strong> Gagal memuat data
              </div>
            `;
          });
      };
    });
  }

  // Function untuk render detail akun
  function renderAkunDetail(akun) {
    const modalBody = document.getElementById('modal_view_content');

    let kategoriHtml = '';
    if (akun.kategori_khusus && akun.kategori_khusus.length > 0) {
      kategoriHtml = akun.kategori_khusus.map(k =>
        `<span class="badge badge-light-primary me-1 mb-1">${k}</span>`
      ).join('');
    } else {
      kategoriHtml = '<span class="text-muted fst-italic">Tidak ada kategori khusus</span>';
    }

    modalBody.innerHTML = `
      <div class="row g-5">
        <div class="col-md-6">
          <div class="card card-flush h-100">
            <div class="card-header">
              <h3 class="card-title">Informasi Utama</h3>
            </div>
            <div class="card-body pt-0">
              <div class="mb-5">
                <label class="fw-bold text-gray-600 mb-1">Kode Akun</label>
                <div class="fw-bold fs-4 text-primary">${akun.kode_akun}</div>
              </div>
              <div class="mb-5">
                <label class="fw-bold text-gray-600 mb-1">Nama Akun</label>
                <div class="fw-bold">${akun.nama_akun}</div>
              </div>
              <div class="mb-5">
                <label class="fw-bold text-gray-600 mb-1">Keterangan</label>
                <div class="text-gray-700">${akun.ket_akun || '<span class="text-muted fst-italic">Tidak ada keterangan</span>'}</div>
              </div>
              <div class="mb-0">
                <label class="fw-bold text-gray-600 mb-1">Tahun Anggaran</label>
                <div>${akun.tahun_anggaran || '-'}</div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="card card-flush h-100">
            <div class="card-header">
              <h3 class="card-title">Tipe Akun</h3>
            </div>
            <div class="card-body pt-0">
              <div class="mb-5">
                <label class="fw-bold text-gray-600 mb-2">Tipe Utama</label>
                <div class="d-flex flex-wrap gap-2">
                  <span class="badge badge-light-${akun.is_pendapatan ? 'success' : 'secondary'} fs-7 px-3 py-2">
                    ${akun.is_pendapatan ? '✓' : '✗'} Pendapatan
                  </span>
                  <span class="badge badge-light-${akun.is_bl ? 'success' : 'secondary'} fs-7 px-3 py-2">
                    ${akun.is_bl ? '✓' : '✗'} Belanja
                  </span>
                  <span class="badge badge-light-${akun.is_pembiayaan ? 'success' : 'secondary'} fs-7 px-3 py-2">
                    ${akun.is_pembiayaan ? '✓' : '✗'} Pembiayaan
                  </span>
                </div>
              </div>
              <div class="separator my-5"></div>
              <div class="mb-0">
                <label class="fw-bold text-gray-600 mb-2">Kategori Khusus</label>
                <div class="d-flex flex-wrap gap-1">
                  ${kategoriHtml}
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12">
          <div class="card card-flush">
            <div class="card-header">
              <h3 class="card-title">Status & Informasi Tambahan</h3>
            </div>
            <div class="card-body pt-0">
              <div class="row">
                <div class="col-md-3 mb-5">
                  <label class="fw-bold text-gray-600 mb-1">Status</label>
                  <div>
                    <span class="badge badge-light-${akun.active ? 'success' : 'danger'} fs-6">
                      ${akun.active ? 'Aktif' : 'Tidak Aktif'}
                    </span>
                  </div>
                </div>
                <div class="col-md-3 mb-5">
                  <label class="fw-bold text-gray-600 mb-1">Level</label>
                  <div>${akun.level || '-'}</div>
                </div>
                <div class="col-md-3 mb-5">
                  <label class="fw-bold text-gray-600 mb-1">Terkunci</label>
                  <div>
                    <span class="badge badge-light-${akun.is_locked ? 'danger' : 'success'} fs-6">
                      ${akun.is_locked ? 'Ya' : 'Tidak'}
                    </span>
                  </div>
                </div>
                <div class="col-md-3 mb-5">
                  <label class="fw-bold text-gray-600 mb-1">ID Akun</label>
                  <div class="text-muted">${akun.id_akun || '-'}</div>
                </div>
                <div class="col-md-6 mb-0">
                  <label class="fw-bold text-gray-600 mb-1">Dibuat</label>
                  <div class="text-muted">${akun.created_at || '-'}</div>
                </div>
                <div class="col-md-6 mb-0">
                  <label class="fw-bold text-gray-600 mb-1">Terakhir Diupdate</label>
                  <div class="text-muted">${akun.updated_at || '-'}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    `;
  }

  // Expose functions ke global scope agar bisa dipanggil dari index
  window.attachViewListeners = attachViewListeners;
  window.renderAkunDetail = renderAkunDetail;
</script>
