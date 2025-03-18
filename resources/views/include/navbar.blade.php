<header>
    <button class="toggle-btn" id="toggleBtn"><i class="material-icons">menu</i></button>
    <div>
        <a class="material-icons" href="{{ url('dashboard') }}">home </a>
        <a href="#">@yield('page')</a>
    </div>
    <a href="{{ url('logout') }}" class="toggle-btn2"><i class="fa-solid fa-right-from-bracket"></i> خروج</a>
</header>
