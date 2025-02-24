<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')  </title>
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">

</head>

<body>

    @include('include.navbar')

    <main>
        @include('include.main')
        @include('include.saidebar')
    </main>

    @include('include.footer')

    <script>
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('content');
        const toggleBtn = document.getElementById('toggleBtn');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            if (window.innerWidth > 768) {
                content.classList.toggle('expanded');
            }
        });
    </script>

</body>

</html>
