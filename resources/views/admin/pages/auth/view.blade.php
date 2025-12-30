@extends('admin.layouts.default.layout')


@php
    $pageData = [
        'title' => '내 정보',
        'name' => config('auth.admin')->name . '정보',
    ];
@endphp

@section('title', "| {$pageData['title']}")

{{-- @section('beforeStyle')@endsection --}}

{{-- @section('afterStyle')@endsection --}}

@section('mainContent')
    <div class="row">
        <div class="card">
            <div class="card-header pb-0">
                <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
                    <li class="nav-item">
                        <a href="#tab-user" class="nav-link active" data-bs-toggle="tab">
                            <x-tabler-user class="icon" />
                            <span>내 정보</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-password" class="nav-link" data-bs-toggle="tab">
                            <x-tabler-key class="icon" />
                            <span class="ms-2">비밀번호 변경</span></a>
                    </li>
                </ul>
            </div>

            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane active show" id="tab-user">
                        @include('admin.pages.auth.form.account')
                    </div>
                    <div class="tab-pane" id="tab-password">
                        @include('admin.pages.auth.form.password')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- @section('beforeScript')@endsection --}}
@section('afterScript')
    <script src="{{ asset('assets/plugins/validation/just-validate.min.js') }}"></script>
@endsection
