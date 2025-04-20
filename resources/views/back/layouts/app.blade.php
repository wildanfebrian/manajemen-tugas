<!DOCTYPE html>
<html lang="en">
  @include('back.layouts.header')
    <body class="sb-nav-fixed">
       @include('back.layouts.navbar')
        <div id="layoutSidenav">
            @include('back.layouts.sidebar')
            <div id="layoutSidenav_content">
                @yield('content')
              @include('back.layouts.footer')
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="{{ asset('assets/js/scripts.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="{{ asset('assets/js/datatables-simple-demo.js') }}"></script>
    </body>
</html>
