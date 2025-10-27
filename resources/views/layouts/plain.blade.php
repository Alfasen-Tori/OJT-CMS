<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | {{ config('app.name') }}</title>

    @include('layouts.partials.styles') {{-- Extract styles to a separate file --}}
    
    @stack('styles')
</head>
<body class="hold-transition layout-top-nav">
    <div class="wrapper">

        <!-- Content Wrapper -->
        <div class="content-wrapper pt-4">
            <!-- Main content -->
            <div class="content">
                <div class="container">
                    @yield('content')
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Main Footer
        <footer class="main-footer">
            <div class="container">
                <div class="float-right d-none d-sm-inline">
                    <strong>Version</strong> {{ config('app.version', '1.0.0') }}
                </div>
                <strong>&copy; {{ date('Y') }} {{ config('app.name') }}.</strong> All rights reserved.
            </div>
        </footer>
    </div> -->
    
    @include('layouts.partials.scripts') {{-- Extract scripts to a separate file --}}
</body>
</html>