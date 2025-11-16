<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Student | Dashboard')</title>

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

  @include('layouts.partials.navbar_i')
  @include('layouts.partials.sidebar_i')

  <div class="content-wrapper py-3 px-sm-3 px-2">
    @yield('content')
  </div>

</div>

@include('layouts.partials.scripts_i')
</body>
</html>