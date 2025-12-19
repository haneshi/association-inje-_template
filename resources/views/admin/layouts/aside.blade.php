<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 "
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="https://demos.creative-tim.com/soft-ui-dashboard/pages/dashboard.html "
            target="_blank">
            <img src="{{ asset('assets/plugins/img/logo-ct-dark.png') }}" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-1 font-weight-bold">협회 관리자</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.home*') ? 'active' : '' }}"
                    href="{{ route('admin.home') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <x-tabler-home />
                    </div>
                    <span class="nav-link-text ms-1">홈</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.pension*') ? 'active' : '' }}"
                    href="{{ route('admin.pension') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <x-tabler-map />
                    </div>
                    <span class="nav-link-text ms-1">펜션 관리</span>
                </a>
            </li>
            @if (config('auth.isSuper'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.setting.member*') ? 'active' : '' }}"
                        href="{{ route('admin.setting.member') }}">
                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <x-tabler-users />
                        </div>
                        <span class="nav-link-text ms-1">관리자 관리</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>
    <div class="sidenav-footer mx-3 ">

    </div>
</aside>
