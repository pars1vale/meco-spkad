<!DOCTYPE html>
<html lang="en">

<head>
  <base href="" />
  <meta charset="utf-8" />
  <title>@yield('title', 'Login') | {{ config('app.name') }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- Stylesheets -->
  <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
  <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" />
  <link rel="shortcut icon" href="{{ URL::to('assets/media/logos/favicon.ico') }}" />
</head>

<body id="kt_body" class="app-blank">
  <!-- Theme Mode Setup -->
  <script>
    var defaultThemeMode = "light";
    var themeMode;
    if (document.documentElement) {
      if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
        themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
      } else if (localStorage.getItem("data-bs-theme") !== null) {
        themeMode = localStorage.getItem("data-bs-theme");
      } else {
        themeMode = defaultThemeMode;
      }
      if (themeMode === "system") {
        themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
      }
      document.documentElement.setAttribute("data-bs-theme", themeMode);
    }
  </script>
  <!-- Root Wrapper -->
  <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
    @yield('content')
  </div>
  <!-- JS Assets -->
  <script>
    var hostUrl = "{{ asset('assets/') }}/";
  </script>
  <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
  <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
  <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
  @yield('script')
</body>

</html>
