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
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.travel*') ? 'active' : '' }}"
                    href="{{ route('admin.travel') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <x-tabler-backpack />
                    </div>
                    <span class="nav-link-text ms-1">관광지 관리</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.special*') ? 'active' : '' }}"
                    href="{{ route('admin.special') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <x-tabler-apple />
                    </div>
                    <span class="nav-link-text ms-1">특산물 관리</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.special*') ? 'active' : '' }}" data-bs-toggle="collapse"
                    href="#specialCollapse" role="button" aria-expanded="false" aria-controls="specialCollapse">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <x-tabler-apple />
                    </div>
                    <span class="nav-link-text ms-1">커뮤니티 관리</span>
                    <i class="fas fa-chevron-down ms-auto" style="font-size: 0.75rem; transition: transform 0.3s;"></i>
                </a>

                <div class="collapse" id="specialCollapse">
                    <ul class="list-unstyled ms-3">
                        <li class="nav-item">
                            <a class="nav-link" href="#" style="padding-left: 1.5rem;">
                                특산물 목록
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" style="padding-left: 1.5rem;">
                                특산물 등록
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" style="padding-left: 1.5rem;">
                                카테고리 관리
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- 최고관리자 이상 접근가능 --}}
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
