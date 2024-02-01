<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | E-Audit - Inspektorat Kab. Magetan</title>
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/logo-kab-magetan.png') }}">
    <script src="{{ asset('assets/libs/flot.curvedlines/flot/jquery.min.js') }}"></script>
    <!-- Bootstrap Css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <!-- Sweet Alert 2 -->
    <link href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Sweet Alerts js -->
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    @yield('head')
</head>
<body data-layout="vertical" data-sidebar="dark">
    @yield('content')
    @yield('modal')
    @if(Session::has('alert'))
        <script>
            let timerInterval
            Swal.fire({
                icon: '{{ Session::get('alert')['type'] }}',
                title: '{{ Session::get('alert')['title'] }}',
                html: '{{ Session::get('alert')['message'] }}',
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true,
                willClose: () => {
                    clearInterval(timerInterval)
                }
            });
        </script>
    @endif
    <!-- JAVASCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/libs/metismenujs/metismenujs.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    @yield('script')
</body>
</html>