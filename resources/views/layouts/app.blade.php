<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - {{ config('app.name') }}</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap/dist/css/bootstrap.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    @stack('style')

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">

    <!-- FONTS GOOGGLE -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        .navbar.active {
            background-color: #3ABAF4;
            box-shadow: rgba(103, 119, 239, 0.2) rgba(0, 0, 0, 0.03);
        }

        .navbar-bg {
            content: " ";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 115px;
            background-color: #3ABAF4;
            z-index: -1;
        }

        ul.nav-tabs li.nav-item a.nav-link i {
            color: #3ABAF4;
        }

        body.layout-3 .navbar.navbar-secondary .navbar-nav>.nav-item.active>.nav-link {
            color: #3ABAF4;
        }

        a {
            color: #3ABAF4;
            font-weight: 500;
            transition: all 0.5s;
            -webkit-transition: all 0.5s;
            -o-transition: all 0.5s;
        }

        .nav-pills .nav-item .nav-link.active {
            box-shadow: 0 2px 6px #acb5f6;
            color: #fff;
            background-color: #3ABAF4;
        }
    </style>

</head>

<body>
    <div id="app">
        <div class="main-wrapper">
            <!-- Header -->
            @include('components.header')

            <!-- Sidebar -->
            @include('components.sidebar')

            <!-- Content -->
            @yield('main')

            <!-- Footer -->
            @include('components.footer')
        </div>
    </div>

    <script src="{{ asset('library/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    <!-- General JS Scripts -->
    <script src="{{ asset('library/popper.js/dist/umd/popper.js') }}"></script>
    <script src="{{ asset('library/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('library/jquery.nicescroll/dist/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset('library/summernote/dist/summernote-bs4.js') }}"></script>
    <script src="{{ asset('library/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('js/stisla.js') }}"></script>
    @stack('scripts')
    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>

</body>

</html>
