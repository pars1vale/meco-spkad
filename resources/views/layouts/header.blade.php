<div id="kt_app_header" class="app-header d-flex flex-column flex-stack">
  <div class="d-flex flex-stack flex-grow-1">
    <div class="app-header-logo d-flex align-items-center ps-lg-12" id="kt_app_header_logo">
      <div id="kt_app_sidebar_toggle"
        class="app-sidebar-toggle btn btn-sm btn-icon bg-body btn-color-gray-500 btn-active-color-primary w-30px h-30px ms-n2 me-4 d-none d-lg-flex"
        data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="app-sidebar-minimize">
        <i class="ki-outline ki-abstract-14 fs-3 mt-1"></i>
      </div>
      <div class="btn btn-icon btn-active-color-primary w-35px h-35px ms-3 me-2 d-flex d-lg-none" id="kt_app_sidebar_mobile_toggle">
        <i class="ki-outline ki-abstract-14 fs-2"></i>
      </div>
      <a href="index.html" class="app-sidebar-logo">
        <img alt="Logo" src="{{ asset('assets/media/logos/demo39.png') }}" class="h-50px theme-light-show" />
        <img src="{{ asset('assets/landing/img/navbar-logo.png') }}" alt="logo" class="h-50px theme-dark-show" />
      </a>
    </div>
    <div class="app-navbar flex-grow-1 justify-content-end" id="kt_app_header_navbar">
      <div class="app-navbar-item d-flex align-items-stretch flex-lg-grow-1">
      </div>
      <div class="app-navbar-item ms-2 ms-lg-6" id="kt_header_user_menu_toggle">
        <div class="cursor-pointer symbol symbol-circle symbol-30px symbol-lg-45px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
          data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
          {{-- <img src="{{ asset('assets/media/avatars/300-2.jpg') }}" alt="user" /> --}}
          {{-- <img src="https://avatar.iran.liara.run/public" alt="user" /> --}}
          <img src="https://api.dicebear.com/9.x/fun-emoji/svg?seed=Brian" alt="user" />
        </div>
        <div
          class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
          data-kt-menu="true">
          <div class="menu-item px-3">
            <div class="menu-content d-flex align-items-center px-3">
              <div class="symbol symbol-50px me-5">
                {{-- <img alt="Logo" src="https://avatar.iran.liara.run/public" /> --}}
                <img alt="Logo" src="https://api.dicebear.com/9.x/fun-emoji/svg?seed=Brian" />
              </div>
              <div class="d-flex flex-column">
                <div class="fw-bold d-flex align-items-center fs-5"> {{ auth()->user()->name }}
                  <span class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">Online</span>
                </div>
                <a href="#" class="fw-semibold text-muted text-hover-primary fs-7">{{ auth()->user()->email }}</a>
              </div>
            </div>
          </div>
          <div class="separator my-2"></div>
          <div class="menu-item px-5">
            <a href="#" class="menu-link px-5">My Profile</a>
          </div>
          <div class="separator my-2"></div>
          <div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="left-start"
            data-kt-menu-offset="-15px, 0">
            <a href="#" class="menu-link px-5">
              <span class="menu-title position-relative">Mode
                <span class="ms-5 position-absolute translate-middle-y top-50 end-0">
                  <i class="ki-outline ki-night-day theme-light-show fs-2"></i>
                  <i class="ki-outline ki-moon theme-dark-show fs-2"></i>
                </span></span>
            </a>
            <div
              class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px"
              data-kt-menu="true" data-kt-element="theme-mode-menu">
              <div class="menu-item px-3 my-0">
                <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="light">
                  <span class="menu-icon" data-kt-element="icon">
                    <i class="ki-outline ki-night-day fs-2"></i>
                  </span>
                  <span class="menu-title">Light</span>
                </a>
              </div>
              <div class="menu-item px-3 my-0">
                <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="dark">
                  <span class="menu-icon" data-kt-element="icon">
                    <i class="ki-outline ki-moon fs-2"></i>
                  </span>
                  <span class="menu-title">Dark</span>
                </a>
              </div>
              <div class="menu-item px-3 my-0">
                <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="system">
                  <span class="menu-icon" data-kt-element="icon">
                    <i class="ki-outline ki-screen fs-2"></i>
                  </span>
                  <span class="menu-title">System</span>
                </a>
              </div>
            </div>
          </div>
          <div class="menu-item px-5">
            <a href="{{ route('logout') }}" class="menu-link px-5">Sign Out</a>
          </div>
        </div>
      </div>
      <div class="app-navbar-item ms-2 ms-lg-6 me-lg-6">
      </div>
      <div class="app-navbar-item ms-2 ms-lg-6 ms-n2 me-3 d-flex d-lg-none">
        <div class="btn btn-icon btn-custom btn-color-gray-600 btn-active-color-primary w-35px h-35px w-md-40px h-md-40px"
          id="kt_app_aside_mobile_toggle">
          <i class="ki-outline ki-burger-menu-2 fs-2"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="app-header-separator"></div>
</div>
