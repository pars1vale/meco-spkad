<!--begin::Sidebar-->
<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
  data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="start"
  data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
  <!--begin::Wrapper-->
  <div class="app-sidebar-wrapper">
    <div id="kt_app_sidebar_wrapper" class="hover-scroll-y my-5 my-lg-2 mx-4" data-kt-scroll="true"
      data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_header"
      data-kt-scroll-wrappers="#kt_app_sidebar_wrapper" data-kt-scroll-offset="5px">
      <!--begin::Sidebar menu-->
      <div id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false"
        class="app-sidebar-menu-primary menu menu-column menu-rounded menu-sub-indention menu-state-bullet-primary px-3 mb-5">
        <!--begin:Menu item-->
        <!--begin:Menu item-->
        <div class="menu-item">
          <!--begin:Menu link-->
          <a href="{{ route('home') }}" class="menu-link {{ Request::is('home') ? 'active' : '' }}">
            <span class="menu-icon">
              <i class="ki-outline ki-home-2 fs-2"></i>
            </span>
            <span class="menu-title">Dashboards</span>
          </a>
          <!--end:Menu link-->
        </div>
        <div data-kt-menu-trigger="click" class="menu-item {{ Request::routeIs('referensi.*') ? 'show' : '' }} menu-accordion">
          <!--begin:Menu link-->
          <span class="menu-link">
            <span class="menu-icon">
              <i class="ki-outline ki-data fs-2"></i>
            </span>
            <span class="menu-title">Referensi</span>
            <span class="menu-arrow"></span>
          </span>
          <!--end:Menu link-->
          <!--begin:Menu sub-->
          <div class="menu-sub menu-sub-accordion">
            <!--begin:Menu item-->
            <div class="menu-item">
              <!--begin:Menu link-->
              <a class="menu-link {{ Request::routeIs('referensi.bidang-urusan.index') ? 'active' : '' }}"
                href="{{ route('referensi.bidang-urusan.index') }}" title="Seluruh Data Bidang Urusan" data-bs-toggle="tooltip"
                data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Bidang Urusan</span>
              </a>
              <!--end:Menu link-->
            </div>
            <!--end:Menu item-->
            <!--begin:Menu item-->
            <div class="menu-item">
              <!--begin:Menu link-->
              <a class="menu-link {{ Request::routeIs('referensi.program.index') ? 'active' : '' }}" href="{{ route('referensi.program.index') }}"
                title="Seluruh Data Program" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Program</span>
              </a>
              <!--end:Menu link-->
            </div>
            <!--end:Menu item-->
            <!--begin:Menu item-->
            <div class="menu-item">
              <!--begin:Menu link-->
              <a class="menu-link {{ Request::routeIs('referensi.kegiatan.index') ? 'active' : '' }}" href="{{ route('referensi.kegiatan.index') }}"
                title="Seluruh Data Kegiatan" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Kegiatan</span>
              </a>
              <!--end:Menu link-->
            </div>
            <!--end:Menu item-->
            <!--begin:Menu item-->
            <div class="menu-item">
              <!--begin:Menu link-->
              <a class="menu-link {{ Request::routeIs('referensi.sub-kegiatan.index') ? 'active' : '' }}"
                href="{{ route('referensi.sub-kegiatan.index') }}" title="Seluruh Data Sub Kegiatan" data-bs-toggle="tooltip" data-bs-trigger="hover"
                data-bs-dismiss="click" data-bs-placement="right">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Sub Kegiatan</span>
              </a>
              <!--end:Menu link-->
            </div>
            <!--end:Menu item-->
            <!--begin:Menu item-->
            <div class="menu-item">
              <!--begin:Menu link-->
              <a class="menu-link {{ Request::routeIs('referensi.akun.index') ? 'active' : '' }}" href="{{ route('referensi.akun.index') }}"
                title="Seluruh Data Akun" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Akun</span>
              </a>
              <!--end:Menu link-->
            </div>
            <!--begin:Menu item-->
            <div class="menu-item">
              <!--begin:Menu link-->
              <a class="menu-link {{ Request::routeIs('referensi.sumber-dana.index') ? 'active' : '' }}"
                href="{{ route('referensi.sumber-dana.index') }}" title="Seluruh Data Sumber Dana" data-bs-toggle="tooltip" data-bs-trigger="hover"
                data-bs-dismiss="click" data-bs-placement="right">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Sumber Dana</span>
              </a>
              <!--end:Menu link-->
            </div>
            <!--end:Menu item-->
            <!--begin:Menu item-->
            <div class="menu-item">
              <!--begin:Menu link-->
              <a class="menu-link" href="https://preview.keenthemes.com/html/metronic/docs/getting-started/changelog" target="_blank"
                title="berikan tooltip di attrubute 'title'" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click"
                data-bs-placement="right">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Mapping Sub Kegiatan - SPM</span>
              </a>
              <!--end:Menu link-->
            </div>
            <!--begin:Menu item-->
            <div class="menu-item">
              <!--begin:Menu link-->
              <a class="menu-link" href="https://preview.keenthemes.com/html/metronic/docs/getting-started/changelog" target="_blank"
                title="berikan tooltip di attrubute 'title'" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click"
                data-bs-placement="right">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Mapping Sub Kegiatan - Kemiskinan Ekstrim</span>
              </a>
              <!--end:Menu link-->
            </div>
            <!--end:Menu item-->
            <!--end:Menu item-->
            <!--end:Menu item-->
          </div>
          <!--end:Menu sub-->
        </div>

        <!--end:Menu item-->
        <!--end:Menu item-->
      </div>
      <!--end::Sidebar menu-->
      <!--begin::Teames-->
      <!--end::Teames-->
    </div>
  </div>
  <!--end::Wrapper-->
</div>
<!--end::Sidebar-->
