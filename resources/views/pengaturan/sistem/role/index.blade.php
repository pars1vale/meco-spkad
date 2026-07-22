  @extends('layouts.master')

  @section('title', 'Manajemen Role & Permission')

  @section('content')
    <div id="kt_app_content" class="app-content flex-column-fluid">
      <div id="kt_app_content_container" class="app-container container-fluid">

        {{-- Header --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
          <div>
            <h3 class="mb-1 fw-bold">Manajemen Role & Permission</h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Role & Permission</li>
              </ol>
            </nav>
          </div>
          @can('role.create')
            <button type="button" class="btn btn-primary btn-sm px-3" data-bs-toggle="modal" data-bs-target="#modalCreateRole">
              <i class="ti ti-plus me-1"></i> Tambah Role
            </button>
          @endcan
        </div>

        {{-- Alert --}}
        @if (session('success'))
          <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
            <i class="ti ti-circle-check fs-5"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        @endif

        @if (session('error'))
          <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
            <i class="ti ti-alert-circle fs-5"></i>
            <span>{{ session('error') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        @endif

        {{-- Summary Cards --}}
        <div class="row g-3 mb-4">
          <div class="col-sm-4">
            <div class="card border-0 shadow-sm h-100">
              <div class="card-body d-flex align-items-center">
                <div class="symbol symbol-50px me-4">
                  <span class="symbol-label bg-light-primary">
                    <i class="ki-duotone ki-shield-tick fs-2x text-primary">
                      <span class="path1"></span>
                      <span class="path2"></span>
                    </i>
                  </span>
                </div>
                <div>
                  <div class="text-muted fw-semibold fs-7">Total Role</div>
                  <div class="fs-2 fw-bold text-gray-900">{{ $roles->count() }}</div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="card border-0 shadow-sm h-100">
              <div class="card-body d-flex align-items-center">
                <div class="symbol symbol-50px me-4">
                  <span class="symbol-label bg-light-success">
                    <i class="ki-duotone ki-key fs-2x text-success">
                      <span class="path1"></span>
                      <span class="path2"></span>
                    </i>
                  </span>
                </div>
                <div>
                  <div class="text-muted fw-semibold fs-7">Total Permission</div>
                  <div class="fs-2 fw-bold text-gray-900">{{ $totalPermissions }}</div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="card border-0 shadow-sm h-100">
              <div class="card-body d-flex align-items-center">
                <div class="symbol symbol-50px me-4">
                  <span class="symbol-label bg-light-info">
                    <i class="ki-solid ki-address-book fs-2x text-info">
                      {{-- <span class="path1"></span>
                      <span class="path2"></span> --}}
                    </i>
                  </span>
                </div>
                <div>
                  <div class="text-muted fw-semibold fs-7">Total User</div>
                  <div class="fs-2 fw-bold text-gray-900">{{ $totalUsers }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- Tabel Role --}}
        <div class="card border-0 shadow-sm">
          <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
            <span class="fw-semibold text-dark">Daftar Role</span>
            <span class="badge bg-primary bg-opacity-10 text-primary small">{{ $roles->count() }} role terdaftar</span>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th class="ps-4" style="width: 40px;">#</th>
                    <th>Nama Role</th>
                    <th>Permissions</th>
                    <th class="text-center" style="width: 100px;">Jumlah User</th>
                    <th class="text-end pe-4" style="width: 160px;">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($roles as $index => $role)
                    <tr>
                      <td class="ps-4 text-muted small">{{ $index + 1 }}</td>
                      <td>
                        @php
                          $badgeColor = match ($role->name) {
                              'kepala bidang' => 'primary',
                              'sekretaris' => 'info',
                              'staff' => 'success',
                              default => 'secondary',
                          };
                        @endphp
                        <span class="badge bg-{{ $badgeColor }} bg-opacity-15 text-{{ $badgeColor }} px-3 py-2 fs-7 text-capitalize fw-semibold">
                          {{ $role->name }}
                        </span>
                      </td>
                      <td>
                        @php
                          $perms = $role->permissions;
                          $shown = $perms->take(4);
                          $remaining = $perms->count() - 4;
                        @endphp
                        <div class="d-flex flex-wrap gap-1 align-items-center">
                          @foreach ($shown as $permission)
                            <span class="badge bg-light text-dark border small fw-normal">
                              {{ $permission->name }}
                            </span>
                          @endforeach
                          @if ($remaining > 0)
                            <span class="badge bg-secondary bg-opacity-10 text-secondary border small">
                              +{{ $remaining }} lainnya
                            </span>
                          @endif
                          @if ($perms->count() === 0)
                            <span class="text-muted small fst-italic">Belum ada permission</span>
                          @endif
                        </div>
                      </td>
                      <td class="text-center">
                        <span class="badge bg-light text-dark border">
                          {{ $role->users()->count() }} user
                        </span>
                      </td>
                      <td class="text-end pe-4">
                        @can('role.edit')
                          <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-outline-primary me-1" title="Edit Permission">
                            <i class="ti ti-edit me-1"></i> Edit
                          </a>
                        @endcan
                        @can('role.delete')
                          <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="d-inline form-delete">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Role" data-role="{{ $role->name }}">
                              <i class="ti ti-trash"></i>
                            </button>
                          </form>
                        @endcan
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="5" class="text-center py-5 text-muted">
                        <i class="ti ti-shield-off fs-2 d-block mb-2"></i>
                        Belum ada role yang dibuat.
                        @can('role.create')
                          <br>
                          <button class="btn btn-sm btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#modalCreateRole">
                            Buat Role Pertama
                          </button>
                        @endcan
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

      </div>
    </div>
    {{-- Modal Create --}}
    @include('pengaturan.sistem.role.partials.modal-create')

  @endsection

  @push('scripts')
    <script>
      document.querySelectorAll('.form-delete').forEach(form => {
        form.addEventListener('submit', function(e) {
          e.preventDefault();
          const roleName = this.querySelector('[data-role]').dataset.role;
          if (confirm(`Yakin ingin menghapus role "${roleName}"?\nUser yang memiliki role ini akan kehilangan akses.`)) {
            this.submit();
          }
        });
      });
    </script>
  @endpush
