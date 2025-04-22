<!DOCTYPE html>
<html lang="en">
  @include('back.layouts.header')
    <body class="bg-light">
        <div class="container-fluid">
            <main>
                @yield('content')
            </main>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="{{ asset('assets/js/scripts.js') }}"></script>
    </body>
</html> 