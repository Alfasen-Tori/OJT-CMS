<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Admin | Dashboard')</title>

  <!-- Load theme immediately to prevent FOUC -->
  <script>
    // Apply dark mode BEFORE any content renders
    (function() {
      const savedTheme = localStorage.getItem('theme');
      if (savedTheme === 'dark') {
        document.documentElement.classList.add('dark-mode');
        document.body.classList.add('dark-mode');
      }
    })();
  </script>

  @include('layouts.partials.styles')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  @include('layouts.partials.navbar_a')
  @include('layouts.partials.sidebar_a')

  <div class="content-wrapper p-3">
    @yield('content')
  </div>

</div>

</body>
</html>