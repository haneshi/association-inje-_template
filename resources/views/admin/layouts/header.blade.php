<div class="page-header border-radius-lg mt-4 d-flex flex-column justify-content-end">
    <nav class="navbar navbar-main navbar-expand-lg bg-transparent shadow-none w-100 z-index-2">
        <div class="container-fluid py-1">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 ps-2 me-sm-6 me-5">
                    <li class="breadcrumb-item text-sm"><a class="text-white opacity-5" href="javascript:void(0)">Pages</a>
                    </li>
                    <li class="breadcrumb-item text-sm text-white active" aria-current="page">Profile</li>
                </ol>
                <h6 class="text-white font-weight-bolder ms-2">Profile</h6>
            </nav>
            <nav id="navbarBlur">
                <div class="container-fluid py-1 px-3">
                    <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0" id="navbar">
                        <ul class="navbar-nav  justify-content-end">
                            <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                                <a href="javascript:void(0)" class="nav-link text-body p-0" id="iconNavbarSidenav">
                                    <div class="sidenav-toggler-inner">
                                        <x-tabler-menu-2 />
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </nav>
    <span class="mask bg-primary opacity-9"></span>
    <div class="w-100 position-relative p-3">
        <div class="d-flex justify-content-between align-items-end">
            <div class="d-flex align-items-center">
                <div class="avatar avatar-xl position-relative me-3">
                    <img src="{{ asset('assets/plugins/img/bruce-mars.jpg') }}" alt="profile_image"
                        class="w-100 border-radius-lg shadow-sm">
                </div>
                <div>
                    <h5 class="mb-1 text-white font-weight-bolder">
                        {{ Auth::guard('admin')->user()->name }}
                    </h5>
                    <p class="mb-0 text-white text-sm">
                        {{ Auth::guard('admin')->user()->job }}
                    </p>
                </div>
            </div>
            <div class="d-flex align-items-center">
                <a href="{{ route('admin.logout') }}" class="btn btn-outline-white mb-0 btn-sm">
                    로그아웃
                </a>
            </div>
        </div>
    </div>
</div>
