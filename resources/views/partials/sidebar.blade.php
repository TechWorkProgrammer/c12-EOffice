<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item">
            <a class="nav-link @yield('beranda', 'collapsed')" href="{{route("home")}}">
                <i class="bi bi-grid"></i>
                <span>Beranda</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-menu-button-wide"></i><span>Layanan</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="components-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="#">
                        <i class="bi bi-circle"></i><span>Permintaan</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="bi bi-circle"></i><span>Ambil Hadiah</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-journal-text"></i><span>Laporan</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="forms-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="#">
                        <i class="bi bi-circle"></i><span>Supir</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="bi bi-circle"></i><span>Sampah</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="bi bi-circle"></i><span>Penjemputan</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link @yield('data', 'collapsed')" data-bs-target="#tables-nav" data-bs-toggle="collapse"
               href="#">
                <i class="bi bi-layout-text-window-reverse"></i><span>Data</span><i
                    class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="tables-nav" class="nav-content @yield('data', 'collapse')" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{route("data.pengguna")}}" class="@yield('data.pengguna', '')">
                        <i class="bi bi-circle"></i><span>Pengguna</span>
                    </a>
                </li>
                <li>
                    <a href="{{route("data.program")}}" class="@yield('data.program', '')">
                        <i class="bi bi-circle"></i><span>Program</span>
                    </a>
                </li>
                <li>
                    <a href="{{route("data.pojok_edukasi")}}" class="@yield('data.pojok_edukasi', '')">
                        <i class="bi bi-circle"></i><span>Pojok Edukasi</span>
                    </a>
                </li>
                <li>
                    <a href="{{route("data.edukasi")}}" class="@yield('data.edukasi', '')">
                        <i class="bi bi-circle"></i><span>Edukasi</span>
                    </a>
                </li>
                <li>
                    <a href="{{route("data.formulir")}}" class="@yield('data.formulir', '')">
                        <i class="bi bi-circle"></i><span>Formulir</span>
                    </a>
                </li>
                <li>
                    <a href="{{route("data.hadiah")}}" class="@yield('data.hadiah', '')">
                        <i class="bi bi-circle"></i><span>Hadiah</span>
                    </a>
                </li>
                <li>
                    <a href="{{route("data.admin")}}" class="@yield('data.admin', '')">
                        <i class="bi bi-circle"></i><span>Admin</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#icons-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-tools"></i><span>Pengaturan</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="icons-nav" class="nav-content @if($sidebar = 'pengaturan') collapse @else collapsed @endif"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="#">
                        <i class="bi bi-circle"></i><span>Hadiah</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="bi bi-circle"></i><span>Aplikasi</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-heading">Akun</li>
        <li class="nav-item">
            <a class="nav-link @yield('pengguna', 'collapsed')" href="{{route("profile")}}">
                <i class="bi bi-person"></i>
                <span>Pengguna</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="#">
                <i class="bi bi-box-arrow-up-right"></i>
                <span>Keluar</span>
            </a>
        </li>
    </ul>
</aside>
