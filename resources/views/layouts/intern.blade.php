<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Student | Dashboard')</title>

  @include('layouts.partials.styles') {{-- Extract styles to a separate file --}}
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
<div class="wrapper">

  @include('layouts.partials.navbar_i')
  @include('layouts.partials.sidebar_i')

  <div class="content-wrapper py-3 px-sm-3 px-2">
    @yield('content')
  </div>

</div>

@include('layouts.partials.scripts_i') {{-- Extract scripts to a separate file --}}
</body>
</html>
