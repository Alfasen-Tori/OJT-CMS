<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Coordinator | Dashboard')</title>

  <!-- Apply dark mode BEFORE any content renders -->
  <script>
    (function() {
      const savedTheme = localStorage.getItem('theme');
      if (savedTheme === 'dark') {
        document.documentElement.classList.add('dark-mode');
      }
    })();
  </script>

  @include('layouts.partials.styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
<div class="wrapper">

  @include('layouts.partials.navbar_c')
  @include('layouts.partials.sidebar_c')

  <div class="content-wrapper p-3">
    @yield('content')
  </div>

</div>

</body>
</html>