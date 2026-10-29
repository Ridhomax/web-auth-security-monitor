
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Kemahasiswaan FIT')</title>

    <meta name="description"
          content="Portal informasi dan layanan Kemahasiswaan Fakultas Ilmu Terapan Telkom University.">

    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
</head>
<body>

    <header class="site-header">
        <div class="container navbar">

            {{-- LOGO --}}
            <a
                href="{{ url('/') }}"
                class="brand"
                aria-label="Beranda Kemahasiswaan FIT"
            >
                <img
                    src="{{ asset('images/Logo Kemahasiswaan FIT Induk Reverse.png') }}"
                    alt="Logo Kemahasiswaan FIT"
                    class="brand-logo"
                >
            </a>

            {{-- NAVIGASI DESKTOP DAN MOBILE --}}
            <nav
                class="nav-links"
                id="main-navigation"
                aria-label="Navigasi utama"
            >
                <a href="{{ url('/') }}">Beranda</a>
                <a href="{{ url('/#layanan') }}">Layanan</a>
                <a href="{{ url('/#informasi') }}">Informasi</a>
                <a href="{{ url('/#kontak') }}">Kontak</a>

                {{-- MENU KHUSUS ADMIN --}}
                @auth
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}">
                            Dashboard
                        </a>
                    @endif

                    {{-- LOGOUT MOBILE --}}
                    <form
                        action="{{ route('logout') }}"
                        method="POST"
                        class="mobile-logout-form"
                    >
                        @csrf

                        <button type="submit" class="mobile-logout">
                            Logout
                        </button>
                    </form>
                @else
                    {{-- LOGIN MOBILE --}}
                    <a
                        href="{{ route('login') }}"
                        class="mobile-login"
                    >
                        Login
                    </a>
                @endauth
            </nav>

            {{-- AKSI DESKTOP --}}
            @auth
                <form
                    action="{{ route('logout') }}"
                    method="POST"
                    class="desktop-logout-form"
                >
                    @csrf

                    <button type="submit" class="btn-login">
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn-login">
                    Login
                </a>
            @endauth

            {{-- HAMBURGER MOBILE --}}
            <button
                type="button"
                class="menu-toggle"
                id="menu-toggle"
                aria-label="Buka menu navigasi"
                aria-controls="main-navigation"
                aria-expanded="false"
            >
                <span></span>
                <span></span>
                <span></span>
            </button>

        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="site-footer" id="kontak">
        <div class="container footer-content">
            <div>
                <h2>Kemahasiswaan FIT</h2>
                <p>
                    Mendukung pengembangan potensi, kreativitas,
                    dan prestasi mahasiswa.
                </p>
            </div>

            <div>
                <h3>Hubungi Kami</h3>
                <p>Kemahasiswaan@tass.telkomuniversity.ac.id</p>
                <p>kmhsfit@telkomuniversity.ac.id</p>
                <p>(+62) 851 743 620 23</p>
            </div>

            <div>
                <h3>Alamat</h3>
                <p>
                    Gedung Selaru, Jl. Telekomunikasi No.1,
                    Sukapura, Kec. Dayeuhkolot, Kabupaten Bandung,
                    Jawa Barat 40257
                </p>
            </div>
        </div>

        <div class="footer-bottom">
            <p>Copyright &copy; 2026 Kemahasiswaan FIT</p>
        </div>
    </footer>

    <script>
        const menuToggle = document.getElementById('menu-toggle');
        const mainNavigation = document.getElementById('main-navigation');

        menuToggle.addEventListener('click', () => {
            const isOpen = mainNavigation.classList.toggle('active');

            menuToggle.classList.toggle('active', isOpen);
            menuToggle.setAttribute('aria-expanded', isOpen);
            menuToggle.setAttribute('aria-label', isOpen ? 'Tutup menu navigasi' : 'Buka menu navigasi');
        });
    </script>

</body>
</html>
