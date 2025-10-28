<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Admin | Dashboard')</title>

  @include('layouts.partials.styles') {{-- Extract styles to a separate file --}}
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
