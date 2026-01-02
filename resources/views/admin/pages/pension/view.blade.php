@extends('admin.layouts.default.layout')


@php
    $pageData = [
        'title' => '펜션 상세정보',
        'name' => $pension->name . ' 상세정보',
    ];
@endphp

@section('title', "| {$pageData['title']}")

@section('beforeStyle')
    <link rel="stylesheet" href="{{ asset('assets/plugins/uppy/uppy.min.css') }}?v={{ env('SITES_ADMIN_ASSETS_VERSION') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/tagify/tagify.css') }}?v={{ env('SITES_ADMIN_ASSETS_VERSION') }}">
    <style>
        /* 태그 배경색 */
        .tagify {
            /* --tag-bg: #f97316 !important; */
        }

        /* 인풋 크기조절 */
        .tagify__input {
            all: unset !important;
            min-height: 1.5h !important;
            white-space: pre-wrap !important;
            position: relative !important;
        }

        /* 태그 크기조절 */
        .tagify__tag {
            margin-block: 0px !important;
        }
    </style>
@endsection

{{-- @section('afterStyle')@endsection --}}

@section('mainContent')
    <div class="row">
        <div class="flex-fill">
            <div class="card">
                <div class="card-header pb-0">
                    <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist" style="font-size: 0.8rem;">
                        <li class="nav-item">
                            <a href="#tab-pension" class="nav-link" data-bs-toggle="tab">
                                <x-tabler-building style="width: 0.8rem;" />
                                <span>기본정보</span>
                            </a>
                        </li>

                        <li class="nav-item ms-auto">
                            <a href="#tab-write-room" class="nav-link active" data-bs-toggle="tab">
                                <x-tabler-plus style="width: 0.8rem;" />
                                <span>객실 추가</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane" id="tab-pension">
                            @include('admin.pages.pension.partials.form.pensionEdit')
                        </div>
                        <div class="tab-pane active show" id="tab-write-room">
                            @include('admin.pages.pension.partials.form.roomWrite')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- @section('beforeScript')@endsection --}}
@section('afterScript')
    <script src="{{ asset('assets/plugins/validation/just-validate.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/address.js') }}?v={{ env('SITES_ADMIN_ASSETS_VERSION') }}"></script>
    <script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
    <script src="//dapi.kakao.com/v2/maps/sdk.js?appkey=49f49d684621b554bb7e4382786b3e46&libraries=services"></script>
    <script src="{{ asset('assets/plugins/uppy/uppy.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/tagify/tagify.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const uppy_pension_edit = new Uppy.Uppy({
                    autoProceed: false, // 파일 추가 시 자동 업로드 방지
                    restrictions: {
                        maxFileSize: 10000000, // 10MB 제한
                        maxNumberOfFiles: 5, // 최대 5개 파일
                        allowedFileTypes: ['image/*'], // 이미지 파일만 허용
                    },
                })
                .use(Uppy.Dashboard, {
                    target: '#drop-area',
                    inline: true,
                    showProgressDetails: true,
                    note: '이미지 파일만 업로드 가능 (최대 10MB, 최대 5개)',
                    height: 320,
                    width: '100%',
                    hideUploadButton: true, // 업로드 버튼 숨기기
                });
        });

        const uppy_room_write = new Uppy.Uppy({
                autoProceed: false, // 파일 추가 시 자동 업로드 방지
                restrictions: {
                    maxFileSize: 10000000, // 10MB 제한
                    maxNumberOfFiles: 5, // 최대 5개 파일
                    allowedFileTypes: ['image/*'], // 이미지 파일만 허용
                },
            })
            .use(Uppy.Dashboard, {
                target: '#drop-area-room-write',
                inline: true,
                showProgressDetails: true,
                note: '이미지 파일만 업로드 가능 (최대 10MB, 최대 5개)',
                height: 320,
                width: '100%',
                hideUploadButton: true, // 업로드 버튼 숨기기
            });
    </script>
@endsection
