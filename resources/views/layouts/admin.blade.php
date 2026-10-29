<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Admin Kemahasiswaan FIT')</title>

    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>

<div class="admin-layout">
    <div
        class="sidebar-overlay"
        id="sidebar-overlay"
        aria-hidden="true"
    ></div>

    {{-- SIDEBAR --}}
    <aside class="admin-sidebar" id="admin-sidebar">

        <div class="sidebar-brand">
            <a href="{{ route('admin.dashboard') }}">
                <img
                    src="{{ asset('images/Logo Kemahasiswaan FIT Induk Reverse.png') }}"
                    alt="Kemahasiswaan FIT"
                >
            </a>
        </div>

        <nav class="sidebar-nav" aria-label="Navigasi admin">

            <a
                href="{{ route('admin.dashboard') }}"
                class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
            >
                Dashboard
            </a>

            <a
                href="{{ route('admin.news.index') }}"
                class="{{ request()->routeIs('admin.news.*') ? 'active' : '' }}"
            >
                Manajemen Berita
            </a>

            <a
                href="{{ route('admin.documents.index') }}"
                class="{{ request()->routeIs('admin.documents.*') ? 'active' : '' }}"
            >
                Manajemen Dokumen
            </a>

            <a href="{{ url('/') }}" target="_blank" rel="noopener noreferrer">
                Lihat Website
            </a>

        </nav>

        <div class="sidebar-footer">

            <form action="{{ route('logout') }}" method="POST">
                @csrf

                <button type="submit" class="sidebar-logout">
                    Logout
                </button>
            </form>

        </div>

    </aside>

    {{-- KONTEN ADMIN --}}
    <div class="admin-main">

        <header class="admin-topbar">

            <button
                type="button"
                class="sidebar-toggle"
                id="sidebar-toggle"
                aria-label="Buka navigasi admin"
                aria-controls="admin-sidebar"
                aria-expanded="false"
            >
                ☰
            </button>

            <div class="topbar-title">
                <strong>Kemahasiswaan FIT</strong>
                <span>Administrator</span>
            </div>

        </header>

        <main class="admin-content">
            @yield('content')
        </main>

    </div>

</div>

<script>
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebar = document.getElementById('admin-sidebar');
    const sidebarOverlay = document.getElementById('sidebar-overlay');

    function setSidebarState(isOpen) {
        sidebar.classList.toggle('open', isOpen);
        sidebarOverlay.classList.toggle('active', isOpen);

        document.body.classList.toggle('sidebar-open', isOpen);

        sidebarToggle.setAttribute('aria-expanded', String(isOpen));

        sidebarToggle.setAttribute(
            'aria-label',
            isOpen ? 'Tutup navigasi admin' : 'Buka navigasi admin'
        );
    }

    sidebarToggle.addEventListener('click', () => {
        const isOpen = sidebar.classList.contains('open');

        setSidebarState(!isOpen);
    });

    sidebarOverlay.addEventListener('click', () => {
        setSidebarState(false);
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            setSidebarState(false);
        }
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth > 900) {
            setSidebarState(false);
        }
    });
</script>

</body>
</html>