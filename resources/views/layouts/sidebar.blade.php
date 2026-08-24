<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
  data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="start"
  data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
  <div class="app-sidebar-wrapper">
    <div id="kt_app_sidebar_wrapper" class="hover-scroll-y my-5 my-lg-2 mx-4" data-kt-scroll="true"
      data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_header"
      data-kt-scroll-wrappers="#kt_app_sidebar_wrapper" data-kt-scroll-offset="5px">
      <div id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false"
        class="app-sidebar-menu-primary menu menu-column menu-rounded menu-sub-indention menu-state-bullet-primary px-3 mb-5">
        <div class="menu-item">
          <a href="{{ route('home') }}" class="menu-link {{ Request::is('home') ? 'active' : '' }}">
            <span class="menu-icon">
              <i class="ki-outline ki-home-2 fs-2"></i>
            </span>
            <span class="menu-title">Dashboards</span>
          </a>
        </div>
        {{-- refersni --}}
        <div data-kt-menu-trigger="click" class="menu-item {{ Request::routeIs('referensi.*') ? 'show' : '' }} menu-accordion">
          <span class="menu-link">
            <span class="menu-icon">
              <i class="ki-outline ki-pin fs-2"></i>
            </span>
            <span class="menu-title">Referensi</span>
            <span class="menu-arrow"></span>
          </span>
          <div class="menu-sub menu-sub-accordion">
            <div class="menu-item">
              <a class="menu-link {{ Request::routeIs('referensi.urusan.index') ? 'active' : '' }}" href="{{ route('referensi.urusan.index') }}"
                title="Seluruh Data Urusan" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Urusan</span>
              </a>
            </div>
            <div class="menu-item">
              <a class="menu-link {{ Request::routeIs('referensi.bidang-urusan.index') ? 'active' : '' }}"
                href="{{ route('referensi.bidang-urusan.index') }}" title="Seluruh Data Bidang Urusan" data-bs-toggle="tooltip"
                data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Bidang Urusan</span>
              </a>
            </div>
            <div class="menu-item">
              <a class="menu-link {{ Request::routeIs('referensi.program.index') ? 'active' : '' }}" href="{{ route('referensi.program.index') }}"
                title="Seluruh Data Program" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Program</span>
              </a>
            </div>
            <div class="menu-item">
              <a class="menu-link {{ Request::routeIs('referensi.kegiatan.index') ? 'active' : '' }}" href="{{ route('referensi.kegiatan.index') }}"
                title="Seluruh Data Kegiatan" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Kegiatan</span>
              </a>
            </div>
            <div class="menu-item">
              <a class="menu-link {{ Request::routeIs('referensi.sub-kegiatan.index') ? 'active' : '' }}"
                href="{{ route('referensi.sub-kegiatan.index') }}" title="Seluruh Data Sub Kegiatan" data-bs-toggle="tooltip" data-bs-trigger="hover"
                data-bs-dismiss="click" data-bs-placement="right">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Sub Kegiatan</span>
              </a>
            </div>
            <div class="menu-item">
              <a class="menu-link {{ Request::routeIs('referensi.akun.index') ? 'active' : '' }}" href="{{ route('referensi.akun.index') }}"
                title="Seluruh Data Akun" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Akun</span>
              </a>
            </div>
            <div class="menu-item">
              <a class="menu-link {{ Request::routeIs('referensi.sumber-dana.index') ? 'active' : '' }}"
                href="{{ route('referensi.sumber-dana.index') }}" title="Seluruh Data Sumber Dana" data-bs-toggle="tooltip"
                data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Sumber Dana</span>
              </a>
            </div>
            <div class="menu-item">
              <a class="menu-link" href="{{ route('404') }}" title="berikan tooltip di attrubute 'title'" data-bs-toggle="tooltip"
                data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Mapping Sub Kegiatan - SPM</span>
              </a>
            </div>
            <div class="menu-item">
              <a class="menu-link" href="{{ route('404') }}" title="berikan tooltip di attrubute 'title'" data-bs-toggle="tooltip"
                data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Mapping Sub Kegiatan - Kemiskinan Ekstrim</span>
              </a>
            </div>
          </div>
        </div>
        {{-- pengaturan --}}
        <div data-kt-menu-trigger="click" class="menu-item {{ Request::routeIs('pengaturan.*') ? 'show' : '' }} menu-accordion">
          <span class="menu-link">
            <span class="menu-icon">
              <i class="ki-outline ki-gear fs-2"></i>
            </span>
            <span class="menu-title">Pengaturan</span>
            <span class="menu-arrow"></span>
          </span>

          <div class="menu-sub menu-sub-accordion">
            {{-- PROFIL --}}
            <div data-kt-menu-trigger="click"
              class="menu-item menu-accordion {{ Request::routeIs('pengaturan.perangkat-daerah.*') ? 'show' : '' }}">
              <span class="menu-link">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Profil</span>
                <span class="menu-arrow"></span>
              </span>

              <div class="menu-sub menu-sub-accordion">
                <div class="menu-item">
                  <a class="menu-link {{ Request::routeIs('pengaturan.perangkat-daerah.index') ? 'active' : '' }}"
                    href="{{ route('pengaturan.perangkat-daerah.index') }}">
                    <span class="menu-bullet">
                      <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">Perangkat Daerah</span>
                  </a>
                </div>
              </div>
            </div>

            {{-- USER --}}
            <div data-kt-menu-trigger="click" class="menu-item menu-accordion ">
              <span class="menu-link">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">User</span>
                <span class="menu-arrow"></span>
              </span>

              <div class="menu-sub menu-sub-accordion">
                <div class="menu-item">
                  <a class="menu-link " href="#" title="Seluruh Data User dengan filter sebagai penyelia keuangan" data-bs-toggle="tooltip"
                    data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                    <span class="menu-bullet">
                      <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">Penyelia Keuangan</span>
                  </a>
                </div>
                <div class="menu-item">
                  <a class="menu-link " href="#">
                    <span class="menu-bullet">
                      <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">Manage User</span>
                  </a>
                </div>
              </div>
            </div>

            <div class="menu-item">
              <a href="{{ route('pengaturan.akses.role.index') }}"
                class="menu-link {{ Request::is('pengaturan.akses.role.index') ? 'active' : '' }}" title="Manage user roles and permissions"
                data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Role</span>
              </a>
            </div>
          </div>
        </div>
        {{-- RKPD --}}
        <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
          <span class="menu-link">
            <span class="menu-icon">
              <i class="ki-outline ki-element-8 fs-2"></i>
            </span>
            <span class="menu-title">RKPD</span>
            <span class="menu-arrow"></span>
          </span>
          <div class="menu-sub menu-sub-accordion">
            <div class="menu-item">
              <a class="menu-link {{ Request::routeIs('rkpd.tahap-penjadwalan.index') ? 'active' : '' }}"
                href="{{ route('rkpd.tahap-penjadwalan.index') }}" title="Seluruh Data Tahap Penjadwalan" data-bs-toggle="tooltip"
                data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Tahap Penjadwalan</span>
              </a>
            </div>
            <div class="menu-item">
              <a class="menu-link {{ Request::routeIs('rkpd.sub-tahap.index') ? 'active' : '' }}" href="{{ route('rkpd.sub-tahap.index') }}"
                title="Seluruh Data Sub Tahap" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click"
                data-bs-placement="right">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Sub Tahap</span>
              </a>
            </div>
            <div class="menu-item">
              <a class="menu-link {{ Request::routeIs('rkpd.jadwal-rkpd.index') ? 'active' : '' }}" href="{{ route('rkpd.jadwal-rkpd.index') }}"
                title="Jadwal RKPD" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Jadwal RKPD</span>
              </a>
            </div>
            <div class="menu-item">
              <a class="menu-link {{ Request::routeIs('rkpd.renja.index') ? 'active' : '' }}" href="{{ route('rkpd.renja.index') }}"
                title="Renja" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Renja</span>
              </a>
            </div>
            <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
              <span class="menu-link">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Dokumen Anggaran</span>
                <span class="menu-arrow"></span>
              </span>

              <div class="menu-sub menu-sub-accordion">
                <div class="menu-item">
                  <a class="menu-link {{ Request::routeIs('rka-skpd.index') ? 'active' : '' }}" href="{{ route('rka-skpd.index') }}">
                    <span class="menu-bullet">
                      <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">RKA SKPD</span>
                  </a>
                </div>
                <div class="menu-item">
                  <a class="menu-link" href="#">
                    <span class="menu-bullet">
                      <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">RKA Pendapatan</span>
                  </a>
                </div>
                <div class="menu-item">
                  <a class="menu-link" href="#">
                    <span class="menu-bullet">
                      <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">RKA Rekap Belanja</span>
                  </a>
                </div>
                <div class="menu-item">
                  <a class="menu-link" href="#">
                    <span class="menu-bullet">
                      <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">RKA Pembiayaan</span>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
        {{-- Penganggaran --}}

        {{-- pendapatan --}}
        <div class="menu-item">
          <a href="{{ route('pendapatan.index') }}" class="menu-link {{ Request::routeIs('pendapatan.index') ? 'active' : '' }}">
            <span class="menu-icon">
              <i class="ki-outline ki-wallet fs-2"></i>
            </span>
            <span class="menu-title">Pendapatan</span>
          </a>
        </div>
        {{-- Pembiayaan --}}
        <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
          <span class="menu-link">
            <span class="menu-icon">
              <i class="ki-outline ki-two-credit-cart fs-2"></i>
            </span>
            <span class="menu-title">Pembiayaan</span>
            <span class="menu-arrow"></span>
          </span>
          <div class="menu-sub menu-sub-accordion">
            <div class="menu-item">
              <a class="menu-link {{ Request::routeIs('pembiayaan.penerimaan.index') ? 'active' : '' }}"
                href="{{ route('pembiayaan.penerimaan.index') }}" title="Check out over 200 in-house components" data-bs-toggle="tooltip"
                data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Penerimaan</span>
              </a>
            </div>
            <div class="menu-item">
              <a class="menu-link" {{ Request::routeIs('pembiayaan.pengeluaran.index') ? 'active' : '' }}
                href="{{ route('pembiayaan.pengeluaran.index') }}" title="Check out the complete documentation" data-bs-toggle="tooltip"
                data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Pengeluaran</span>
              </a>
            </div>
          </div>
        </div>
        {{-- Standar Harga --}}
        <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
          <span class="menu-link">
            <span class="menu-icon">
              <i class="ki-outline ki-price-tag fs-2"></i>
            </span>
            <span class="menu-title">Standar harga</span>
            <span class="menu-arrow"></span>
          </span>
          <div class="menu-sub menu-sub-accordion">
            <div class="menu-item">
              <a class="menu-link {{ Request::routeIs('data_ssh.index') ? 'active' : '' }}" href="{{ route('data_ssh.index') }}"
                title="Data Pembiayaan Penerimaan" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click"
                data-bs-placement="right">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Standar Harga Satuan</span>
              </a>
            </div>
            <div class="menu-item">
              <a class="menu-link {{ Request::routeIs('kelompok_satuan_harga.index') ? 'active' : '' }}"
                href="{{ route('kelompok_satuan_harga.index') }}" title="Check out the complete documentation" data-bs-toggle="tooltip"
                data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Kelompok Barang</span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
