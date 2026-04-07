@extends('layouts.master')

@section('title', 'Edit Role: ' . ucfirst($role->name))

@section('content')
  <div class="container-fluid px-4 py-3">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
      <div>
        <h4 class="mb-1 fw-semibold">Edit Role</h4>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 small">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Role & Permission</a></li>
            <li class="breadcrumb-item active text-capitalize">{{ $role->name }}</li>
          </ol>
        </nav>
      </div>
      <a href="{{ route('roles.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="ti ti-arrow-left me-1"></i> Kembali
      </a>
    </div>

    <form action="{{ route('roles.update', $role->id) }}" method="POST" id="formEditRole">
      @csrf
      @method('PUT')

      <div class="row g-4">

        {{-- Kolom Kiri: Info Role --}}
        <div class="col-lg-4">
          <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
              <span class="fw-semibold">Informasi Role</span>
            </div>
            <div class="card-body">

              {{-- Nama Role --}}
              <div class="mb-3">
                <label for="roleName" class="form-label fw-semibold small text-uppercase text-muted">
                  Nama Role <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="roleName" name="name"
                  value="{{ old('name', $role->name) }}" placeholder="Nama role...">
                @error('name')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              {{-- Stats --}}
              <hr>
              <div class="d-flex flex-column gap-2">
                <div class="d-flex justify-content-between align-items-center">
                  <span class="small text-muted">Permission aktif</span>
                  <span class="badge bg-primary bg-opacity-10 text-primary fw-semibold" id="statPermCount">
                    {{ count($rolePermissions) }}
                  </span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                  <span class="small text-muted">User dengan role ini</span>
                  <span class="badge bg-secondary bg-opacity-10 text-secondary fw-semibold">
                    {{ $role->users()->count() }}
                  </span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                  <span class="small text-muted">Total permission tersedia</span>
                  <span class="badge bg-secondary bg-opacity-10 text-secondary fw-semibold">
                    {{ $permissions->flatten()->count() }}
                  </span>
                </div>
              </div>

              {{-- Info box --}}
              <div class="alert alert-warning border-0 small mt-3 mb-0 d-flex gap-2">
                <i class="ti ti-alert-triangle flex-shrink-0"></i>
                <span>Perubahan permission akan langsung berlaku pada semua user yang memiliki role ini.</span>
              </div>

            </div>
          </div>
        </div>

        {{-- Kolom Kanan: Permission --}}
        <div class="col-lg-8">
          <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between">
              <span class="fw-semibold">Assign Permission</span>
              <div class="form-check form-switch mb-0">
                <input class="form-check-input" type="checkbox" id="checkAllGlobal" role="switch">
                <label class="form-check-label small" for="checkAllGlobal">Pilih Semua</label>
              </div>
            </div>
            <div class="card-body">

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
                          @php
                            $allGroupChecked = $groupPermissions->every(fn($p) => in_array($p->name, $rolePermissions));
                          @endphp
                          <input class="form-check-input check-group-all" type="checkbox" id="checkAll_{{ $group }}"
                            data-group="{{ $group }}" {{ $allGroupChecked ? 'checked' : '' }}>
                          <label class="form-check-label small text-muted" for="checkAll_{{ $group }}">
                            Semua
                          </label>
                        </div>
                      </div>
                      <div class="card-body py-2 px-3">
                        @foreach ($groupPermissions as $permission)
                          <div class="form-check py-1 border-bottom" data-group="{{ $group }}">
                            <input class="form-check-input perm-checkbox" type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                              id="perm_edit_{{ $permission->id }}" data-group="{{ $group }}"
                              {{ in_array($permission->name, old('permissions', $rolePermissions)) ? 'checked' : '' }}>
                            <label class="form-check-label w-100 d-flex justify-content-between align-items-center"
                              for="perm_edit_{{ $permission->id }}">
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

              @error('permissions')
                <div class="text-danger small mt-2">{{ $message }}</div>
              @enderror

            </div>
            <div class="card-footer bg-white border-top d-flex align-items-center justify-content-between">
              <span class="text-muted small">
                <i class="ti ti-check me-1"></i>
                <span id="selectedCount">{{ count($rolePermissions) }}</span> permission dipilih
              </span>
              <div class="d-flex gap-2">
                <a href="{{ route('roles.index') }}" class="btn btn-sm btn-outline-secondary">
                  Batal
                </a>
                <button type="submit" class="btn btn-sm btn-primary px-4">
                  <i class="ti ti-device-floppy me-1"></i> Simpan Perubahan
                </button>
              </div>
            </div>
          </div>
        </div>

      </div>
    </form>

  </div>
@endsection

@push('scripts')
  <script>
    (function() {
      function updateCount() {
        const count = document.querySelectorAll('#formEditRole .perm-checkbox:checked').length;
        document.getElementById('selectedCount').textContent = count;
        const statEl = document.getElementById('statPermCount');
        if (statEl) statEl.textContent = count;
      }

      document.getElementById('checkAllGlobal').addEventListener('change', function() {
        document.querySelectorAll('#formEditRole .perm-checkbox').forEach(cb => cb.checked = this.checked);
        document.querySelectorAll('#formEditRole .check-group-all').forEach(cb => cb.checked = this.checked);
        updateCount();
      });

      document.querySelectorAll('#formEditRole .check-group-all').forEach(groupCb => {
        groupCb.addEventListener('change', function() {
          const group = this.dataset.group;
          document.querySelectorAll(`#formEditRole .perm-checkbox[data-group="${group}"]`)
            .forEach(cb => cb.checked = this.checked);
          syncGlobalCheckbox();
          updateCount();
        });
      });

      document.querySelectorAll('#formEditRole .perm-checkbox').forEach(cb => {
        cb.addEventListener('change', function() {
          const group = this.dataset.group;
          const allInGroup = document.querySelectorAll(`#formEditRole .perm-checkbox[data-group="${group}"]`);
          const allChecked = [...allInGroup].every(c => c.checked);
          document.getElementById(`checkAll_${group}`).checked = allChecked;
          syncGlobalCheckbox();
          updateCount();
        });
      });

      function syncGlobalCheckbox() {
        const all = document.querySelectorAll('#formEditRole .perm-checkbox');
        document.getElementById('checkAllGlobal').checked = [...all].every(c => c.checked);
      }

      // Init
      syncGlobalCheckbox();
      updateCount();
    })();
  </script>
@endpush
