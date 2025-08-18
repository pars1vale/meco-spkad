<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title> Landing | {{ config('app.name') }}</title>
  <meta name="description" content="">
  <meta name="keywords" content="">
  <link rel="shortcut icon" href="{{ URL::to('assets/media/logos/favicon.ico') }}" />

  <!-- Favicons -->
  <link href="{{ asset('assets/landing/img/favicon.png') }}" rel="icon">
  <link href="{{ asset('assets/landing/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('assets/landing/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/landing/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/landing/vendor/aos/aos.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/landing/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/landing/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="{{ asset('assets/landing/css/main.css') }}" rel="stylesheet">
  <style>
    .text-primary-blue {
      color: #1b1f94;
    }

    .btn-green {
      background-color: #28c76f;
      color: #fff;
      font-weight: 500;
      padding: 8px 20px;
      border: none;
      transition: 0.3s ease;
    }

    .btn-green:hover {
      background-color: #20b765;
      color: #fff;
    }

    .card-spkad {
      border: none;
      border-radius: 1rem;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      transition: transform 0.2s ease;
    }

    .card-spkad:hover {
      transform: translateY(-5px);
    }

    .icon-circle {
      width: 50px;
      height: 50px;
      background-color: #e9f9f1;
      color: #28c76f;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
    }
  </style>

</head>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

      <a href="index.html" class="logo d-flex align-items-center">
        <img src="{{ asset('assets/landing/img/navbar-logo.png') }}" alt="">
        {{-- <h1 class="sitename">SPKAD </h1> --}}
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="{{ route('landing.index') }}" class="active">Home</a></li>
          <li><a href="#keuangan">Keuangan</a></li>
          <li><a href="#aset">Aset</a></li>
          <li class="dropdown"><a href="#"><span>Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="#">Dropdown 1</a></li>
              <li class="dropdown"><a href="#"><span>Deep Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                <ul>
                  <li><a href="#">Deep Dropdown 1</a></li>
                  <li><a href="#">Deep Dropdown 2</a></li>
                  <li><a href="#">Deep Dropdown 3</a></li>
                  <li><a href="#">Deep Dropdown 4</a></li>
                  <li><a href="#">Deep Dropdown 5</a></li>
                </ul>
              </li>
              <li><a href="#">Dropdown 2</a></li>
              <li><a href="#">Dropdown 3</a></li>
              <li><a href="#">Dropdown 4</a></li>
            </ul>
          </li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

    </div>
  </header>

  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section dark-background">
      <img src="{{ asset('assets/img/hero-bg-2.jpg') }}" alt="" class="hero-bg">

      <div class="container">
        <div class="row gy-4 justify-content-between">
          <div class="col-lg-4 order-lg-last hero-img" data-aos="zoom-out" data-aos-delay="100">
            <img src="{{ asset('assets/img/hero-img.png') }}" class="img-fluid animated" alt="">
          </div>

          <div class="col-lg-6  d-flex flex-column justify-content-center" data-aos="fade-in">
            <h1>Selamat Datang Di, <span>SPKAD</span></h1>
            <p>Pengelolaan informasi pembangunan daerah, informasi keuangan daerah, dan informasi pemerintahan daerah lainnya yang saling terhubung
              untuk dimanfaatkan dalam penyelenggaraan pembangunan daerah.</p>
            <div class="d-flex">
              {{-- <a href="#about" class="btn-get-started">Get Started</a> --}}
            </div>
          </div>

        </div>
      </div>

      <svg class="hero-waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 24 150 28 "
        preserveAspectRatio="none">
        <defs>
          <path id="wave-path" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z"></path>
        </defs>
        <g class="wave1">
          <use xlink:href="#wave-path" x="50" y="3"></use>
        </g>
        <g class="wave2">
          <use xlink:href="#wave-path" x="50" y="0"></use>
        </g>
        <g class="wave3">
          <use xlink:href="#wave-path" x="50" y="9"></use>
        </g>
      </svg>

    </section><!-- /Hero Section -->

    <!-- Keungan Daerah Section -->
    <section id="keuangan" class="about section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row align-items-xl-center gy-5">

          <div class="col-xl-5 content">
            <h3>Layanan</h3>
            <h2>Informasi Keuangan Daerah</h2>
            <p>AInformasi Keuangan Daerah adalah suatu sistem yang digunakan untuk pengelolaan data dan informasi serta penyusunan, monitoring, dan
              evaluasi dokumen pengelolaan keuangan daerah secara elektronik.</p>
            <br>
            <p>- Permendagri 70 Tahun 2019 tentang Sistem Informasi Pemerintahan Daerah -
            </p>
            <a href="#" class="read-more" data-bs-toggle="modal" data-bs-target="#modalInfoKeuangan">
              <span>Selengkapnya</span>
              <i class="bi bi-arrow-right"></i>
            </a>
          </div>

          <div class="col-xl-7">
            <div class="row gy-4 icon-boxes">

              <div class="col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="icon-box">
                  <i class="bi bi-graph-up-arrow"></i>
                  <h3>Perencanaan Anggaran Daerah</h3>
                  <p>Perencanaan Anggaran Daerah merupakan modul yang digunakan untuk merencanakan anggaran daerah. Modul ini mencakup proses
                    penyusunan, penetapan dan evaluasi anggaran daerah</p>
                </div>
              </div> <!-- End Icon Box -->

              <div class="col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="icon-box">
                  <i class="bi bi-clipboard-pulse"></i>
                  <h3>Penatausahaan Keuangan Daerah</h3>
                  <p>Penatausahaan Keuangan Daerah merupakan modul yang digunakan untuk melaksanakan dan menatausahakan anggaran daerah.</p>
                </div>
              </div> <!-- End Icon Box -->

              <div class="col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="icon-box">
                  <i class="bi bi-command"></i>
                  <h3>Akuntansi dan Pelaporan Keuangan Daerah</h3>
                  <p>Akuntansi dan Pelaporan Keuangan Daerah merupakan modul yang digunakan untuk melakukan akuntansi dan pelaporan keuangan daerah.
                  </p>
                </div>
              </div> <!-- End Icon Box -->

              <div class="col-md-6" data-aos="fade-up" data-aos-delay="500">
                <div class="icon-box">
                  <i class="bi bi-buildings"></i>
                  <h3>BUMD</h3>
                  <p>BUMD adalah perusahaan milik pemerintah daerah, yang dibentuk untuk memberikan pelayanan kepada masyarakat, meningkatkan
                    pendapatan daerah, dan membantu pembangunan daerah.!</p>
                </div>
              </div> <!-- End Icon Box -->

            </div>
          </div>

        </div>
      </div>

    </section><!-- /About Section -->

    {{-- modal Keuangan --}}
    <div class="modal fade" id="modalInfoKeuangan" tabindex="-1" aria-labelledby="modalInfoKeuanganLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 rounded-4">
          <div class="modal-header border-0 px-4 pt-4">
            <h5 class="modal-title fw-bold text-primary-blue" id="modalInfoKeuanganLabel">Informasi Keuangan Daerah</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
          </div>
          <div class="modal-body py-4 px-4">

            <div class="row row-cols-1 row-cols-md-4 g-4">

              <!-- Card 1 -->
              <div class="col">
                <div class="card card-spkad h-100">
                  <div class="icon-circle mb-3 mx-auto">
                    <i class="bi bi-graph-up"></i>
                  </div>
                  <div class="card-body text-center">
                    <h6 class="fw-bold">Perencanaan</h6>
                    <p class="text-muted small">Penyusunan dan evaluasi anggaran daerah.</p>
                  </div>
                  <div class="card-footer bg-transparent text-center border-0 pb-4">
                    <a href="{{ route('login') }}" class="btn btn-green rounded-pill">Pilih Modul Ini</a>
                  </div>
                </div>
              </div>

              <!-- Card 2 -->
              <div class="col">
                <div class="card card-spkad h-100">
                  <div class="icon-circle mb-3 mx-auto">
                    <i class="bi bi-cash-coin"></i>
                  </div>
                  <div class="card-body text-center">
                    <h6 class="fw-bold">Penatausahaan</h6>
                    <p class="text-muted small">Penganggaran dan pertanggungjawaban keuangan.</p>
                  </div>
                  <div class="card-footer bg-transparent text-center border-0 pb-4">
                    <a href="{{ route('login') }}" class="btn btn-green rounded-pill">Pilih Modul Ini</a>
                  </div>
                </div>
              </div>

              <!-- Card 3 -->
              <div class="col">
                <div class="card card-spkad h-100">
                  <div class="icon-circle mb-3 mx-auto">
                    <i class="bi bi-journal-check"></i>
                  </div>
                  <div class="card-body text-center">
                    <h6 class="fw-bold">Akuntansi</h6>
                    <p class="text-muted small">Pencatatan dan pelaporan keuangan daerah.</p>
                  </div>
                  <div class="card-footer bg-transparent text-center border-0 pb-4">
                    <a href="{{ route('login') }}" class="btn btn-green rounded-pill">Pilih Modul Ini</a>
                  </div>
                </div>
              </div>

              <!-- Card 4 -->
              <div class="col">
                <div class="card card-spkad h-100">
                  <div class="icon-circle mb-3 mx-auto">
                    <i class="bi bi-buildings"></i>
                  </div>
                  <div class="card-body text-center">
                    <h6 class="fw-bold">BUMD</h6>
                    <p class="text-muted small">Pengelolaan Badan Usaha Milik Daerah.</p>
                  </div>
                  <div class="card-footer bg-transparent text-center border-0 pb-4">
                    <button class="btn btn-secondary rounded-pill" disabled>Dalam Pengembangan</button>
                  </div>
                </div>
              </div>

            </div>

          </div>
          <div class="modal-footer border-0">
            <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Tutup</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Stats Section -->
    <section id="stats" class="stats section light-background">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4">

          <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
            <i class="bi bi-emoji-smile"></i>
            <div class="stats-item">
              <span data-purecounter-start="0" data-purecounter-end="232" data-purecounter-duration="1" class="purecounter"></span>
              <p>Happy Clients</p>
            </div>
          </div><!-- End Stats Item -->

          <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
            <i class="bi bi-journal-richtext"></i>
            <div class="stats-item">
              <span data-purecounter-start="0" data-purecounter-end="521" data-purecounter-duration="1" class="purecounter"></span>
              <p>Projects</p>
            </div>
          </div><!-- End Stats Item -->

          <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
            <i class="bi bi-headset"></i>
            <div class="stats-item">
              <span data-purecounter-start="0" data-purecounter-end="1463" data-purecounter-duration="1" class="purecounter"></span>
              <p>Hours Of Support</p>
            </div>
          </div><!-- End Stats Item -->

          <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
            <i class="bi bi-people"></i>
            <div class="stats-item">
              <span data-purecounter-start="0" data-purecounter-end="15" data-purecounter-duration="1" class="purecounter"></span>
              <p>Hard Workers</p>
            </div>
          </div><!-- End Stats Item -->

        </div>

      </div>

    </section><!-- /Stats Section -->

    <!-- Aset Section -->
  </main>

  <footer id="footer" class="footer dark-background">

    <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-lg-4 col-md-6 footer-about">
          <a href="index.html" class="logo d-flex align-items-center">
            <span class="sitename">SPKAD</span>
          </a>
          <div class="footer-contact pt-3">
            <p>abepura new street</p>
            <p>Jayapura, Papua, ID</p>
            <p class="mt-3"><strong>Phone:</strong> <span>+62 2345 6789</span></p>
            <p><strong>Email:</strong> <span>info@example.com</span></p>
          </div>
          <div class="social-links d-flex mt-4">
            <a href=""><i class="bi bi-twitter-x"></i></a>
            <a href=""><i class="bi bi-facebook"></i></a>
            <a href=""><i class="bi bi-instagram"></i></a>
            <a href=""><i class="bi bi-linkedin"></i></a>
          </div>
        </div>

        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Informasi Aplikasi</h4>
          <ul>
            <li><a href="#">Dasar Hukum</a></li>
            <li><a href="#">Dokumentasi</a></li>
            <li><a href="#">FAQ</a></li>
            <li><a href="#">Terms of service</a></li>
            <li><a href="#">Privacy policy</a></li>
          </ul>
        </div>
        {{-- 
        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Our Services</h4>
          <ul>
            <li><a href="#">Web Design</a></li>
            <li><a href="#">Web Development</a></li>
            <li><a href="#">Product Management</a></li>
            <li><a href="#">Marketing</a></li>
            <li><a href="#">Graphic Design</a></li>
          </ul>
        </div>

        <div class="col-lg-4 col-md-12 footer-newsletter">
          <h4>Our Newsletter</h4>
          <p>Subscribe to our newsletter and receive the latest news about our products and services!</p>
          <form action="forms/newsletter.php" method="post" class="php-email-form">
            <div class="newsletter-form"><input type="email" name="email"><input type="submit" value="Subscribe"></div>
            <div class="loading">Loading</div>
            <div class="error-message"></div>
            <div class="sent-message">Your subscription request has been sent. Thank you!</div>
          </form>
        </div> --}}

      </div>
    </div>

    <div class="container copyright text-center mt-4">
      <p>© <span>Copyright</span> <strong class="px-1 sitename">SPKAD</strong> <span>All Rights Reserved</span></p>
      <div class="credits">
        Lorem ipsum, dolor sit amet consectetur adipisicing elit.
      </div>
    </div>

  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="{{ asset('assets/landing/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/landing/vendor/php-email-form/validate.js') }}"></script>
  <script src="{{ asset('assets/landing/vendor/aos/aos.js') }}"></script>
  <script src="{{ asset('assets/landing/vendor/glightbox/js/glightbox.min.js') }}"></script>
  <script src="{{ asset('assets/landing/vendor/purecounter/purecounter_vanilla.js') }}"></script>
  <script src="{{ asset('assets/landing/vendor/swiper/swiper-bundle.min.js') }}"></script>

  <!-- Main JS File -->
  <script src="{{ asset('assets/landing/js/main.js') }}"></script>

</body>

</html>
