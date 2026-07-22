{{-- Modal Tambah Role --}}
<div class="modal fade" id="modalCreateRole" tabindex="-1" aria-labelledby="modalCreateRoleLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">

      <div class="modal-header border-bottom">
        <div>
          <h5 class="modal-title fw-semibold" id="modalCreateRoleLabel">
            <i class="ti ti-shield-plus me-2 text-primary"></i>Tambah Role Baru
          </h5>
          <p class="text-muted small mb-0 mt-1">Buat role dan tentukan permission yang dimilikinya</p>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="{{ route('roles.store') }}" method="POST" id="formCreateRole">
        @csrf

        <div class="modal-body p-4">

          {{-- Nama Role --}}
          <div class="mb-4">
            <label for="roleName" class="form-label fw-semibold small text-uppercase text-muted ls-1">
              Nama Role <span class="text-danger">*</span>
            </label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="roleName" name="name" value="{{ old('name') }}"
              placeholder="Contoh: supervisor, admin, operator..." autocomplete="off">
            @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="form-text">Gunakan huruf kecil. Nama role bersifat unik.</div>
          </div>

          {{-- Divider --}}
          <hr class="my-4">

          {{-- Permission Section --}}
          <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
              <div class="fw-semibold">Assign Permission</div>
              <div class="text-muted small">Centang permission yang boleh diakses role ini</div>
            </div>
            <div class="form-check form-switch mb-0">
              <input class="form-check-input" type="checkbox" id="checkAllGlobal" role="switch">
              <label class="form-check-label small" for="checkAllGlobal">Pilih Semua</label>
            </div>
          </div>

          {{-- Permission Groups --}}
          <div class="row g-3">
            @foreach ($permissions as $group => $groupPermissions)
              <div class="col-md-6">
                <div class="card border h-100">
                  <div class="card-header bg-light py-2 px-3 d-flex align-items-center justify-content-between">
                    <span class="fw-semibold small text-capitalize d-flex align-items-center gap-2">
                      @php
                        $icon = match ($group) {
                            'sk' => 'ti-file-text',
                            'laporan' => 'ti-chart-bar',
                            'user' => 'ti-users',
                            'role' => 'ti-shield',
                            default => 'ti-settings',
                        };
                        $label = match ($group) {
                            'sk' => 'Surat Keputusan',
                            'laporan' => 'Laporan',
                            'user' => 'User',
                            'role' => 'Role & Permission',
                            default => ucfirst($group),
                        };
                      @endphp
                      <i class="ti {{ $icon }} text-primary"></i>
                      {{ $label }}
                    </span>
                    <div class="form-check mb-0">
                      <input class="form-check-input check-group-all" type="checkbox" id="checkAll_{{ $group }}"
                        data-group="{{ $group }}" title="Pilih semua {{ $label }}">
                      <label class="form-check-label small text-muted" for="checkAll_{{ $group }}">
                        Semua
                      </label>
                    </div>
                  </div>
                  <div class="card-body py-2 px-3">
                    @foreach ($groupPermissions as $permission)
                      <div class="form-check py-1 border-bottom permission-item" data-group="{{ $group }}">
                        <input class="form-check-input perm-checkbox" type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                          id="perm_create_{{ $permission->id }}" data-group="{{ $group }}"
                          {{ in_array($permission->name, old('permissions', [])) ? 'checked' : '' }}>
                        <label class="form-check-label w-100 d-flex justify-content-between align-items-center"
                          for="perm_create_{{ $permission->id }}">
                          <span class="small">
                            @php
                              $action = explode('.', $permission->name)[1] ?? '';
                              $actionLabel = match ($action) {
                                  'view' => 'Lihat data',
                                  'create' => 'Tambah data',
                                  'edit' => 'Edit data',
                                  'delete' => 'Hapus data',
                                  'approve' => 'Setujui / approve',
                                  'export' => 'Export laporan',
                                  default => ucfirst($action),
                              };
                            @endphp
                            {{ $actionLabel }}
                          </span>
                          <span class="badge bg-light text-secondary border small ms-2 font-monospace" style="font-size:10px;">
                            {{ $permission->name }}
                          </span>
                        </label>
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>
            @endforeach
          </div>

          {{-- Validation error untuk permissions --}}
          @error('permissions')
            <div class="text-danger small mt-2">{{ $message }}</div>
          @enderror

        </div>

        <div class="modal-footer border-top bg-light">
          <div class="me-auto text-muted small" id="countSelectedPerms">
            <i class="ti ti-check me-1"></i><span id="selectedCount">0</span> permission dipilih
          </div>
          <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">
            Batal
          </button>
          <button type="submit" class="btn btn-sm btn-primary px-4">
            <i class="ti ti-device-floppy me-1"></i> Simpan Role
          </button>
        </div>

      </form>
    </div>
  </div>
</div>

@push('scripts')
  <script>
    (function() {
      // Hitung selected permission
      function updateCount() {
        const count = document.querySelectorAll('#formCreateRole .perm-checkbox:checked').length;
        document.getElementById('selectedCount').textContent = count;
      }

      // Check All Global
      document.getElementById('checkAllGlobal').addEventListener('change', function() {
        document.querySelectorAll('#formCreateRole .perm-checkbox').forEach(cb => cb.checked = this.checked);
        document.querySelectorAll('#formCreateRole .check-group-all').forEach(cb => cb.checked = this.checked);
        updateCount();
      });

      // Check All per Grup
      document.querySelectorAll('#formCreateRole .check-group-all').forEach(groupCb => {
        groupCb.addEventListener('change', function() {
          const group = this.dataset.group;
          document.querySelectorAll(`#formCreateRole .perm-checkbox[data-group="${group}"]`)
            .forEach(cb => cb.checked = this.checked);
          syncGlobalCheckbox();
          updateCount();
        });
      });

      // Individual checkbox change
      document.querySelectorAll('#formCreateRole .perm-checkbox').forEach(cb => {
        cb.addEventListener('change', function() {
          const group = this.dataset.group;
          const allInGroup = document.querySelectorAll(`#formCreateRole .perm-checkbox[data-group="${group}"]`);
          const allChecked = [...allInGroup].every(c => c.checked);
          document.getElementById(`checkAll_${group}`).checked = allChecked;
          syncGlobalCheckbox();
          updateCount();
        });
      });

      function syncGlobalCheckbox() {
        const all = document.querySelectorAll('#formCreateRole .perm-checkbox');
        const allChecked = [...all].every(c => c.checked);
        document.getElementById('checkAllGlobal').checked = allChecked;
      }

      // Jika ada old() value, update count saat modal dibuka
      const modal = document.getElementById('modalCreateRole');
      if (modal) {
        modal.addEventListener('shown.bs.modal', updateCount);
      }

      // Reset form saat modal ditutup
      modal.addEventListener('hidden.bs.modal', function() {
        document.getElementById('formCreateRole').reset();
        document.querySelectorAll('#formCreateRole .check-group-all').forEach(cb => cb.checked = false);
        document.getElementById('checkAllGlobal').checked = false;
        updateCount();
      });

      // Init count on load (untuk old() Laravel)
      updateCount();
    })();
  </script>
@endpush
