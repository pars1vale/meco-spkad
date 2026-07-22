<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
  <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
    <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
      <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
        <h1 class="page-heading text-dark fw-bold fs-3 m-0">
          {{ $title ?? 'Halaman' }}
        </h1>
        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
          @forelse ($breadcrumbs ?? [] as $breadcrumb)
            <li class="breadcrumb-item">
              @if ($breadcrumb['url'] ?? null)
                <a href="{{ $breadcrumb['url'] }}" class="text-muted text-hover-primary">
                  {{ $breadcrumb['label'] }}
                </a>
              @else
                <span class="text-muted">{{ $breadcrumb['label'] }}</span>
              @endif
            </li>
            @if (!$loop->last)
              <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
              </li>
            @endif
          @empty
            <li class="breadcrumb-item text-muted">
              <a href="{{ url('/') }}" class="text-muted text-hover-primary">Home</a>
            </li>
          @endforelse
        </ul>
      </div>

      @if ($slot->isNotEmpty())
        <div class="d-flex align-items-center gap-2 gap-lg-3">
          {{ $slot }}
        </div>
      @endif
    </div>
    <div class="d-flex align-items-center gap-2 gap-lg-3">
      <span class="badge badge-light-primary fs-7 fw-bold px-4 py-2">
        <i class="ki-outline ki-calendar fs-6 me-1"></i>
        Tahun Anggaran: {{ session('tahun_anggaran', '-') }}
      </span>
    </div>
  </div>
</div>
