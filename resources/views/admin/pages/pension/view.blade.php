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
@endsection

{{-- @section('afterStyle')@endsection --}}

@section('mainContent')
    <div class="row">
        <div class="flex-fill">
            <div class="card">
                <div class="card-header pb-0">
                    <ul class="nav nav-tabs card-header-tabs" id="sortTable" data-bs-toggle="tabs" role="tablist"
                        style="font-size: 0.8rem;">
                        <li class="nav-item">
                            <a href="#tab-pension" class="nav-link active" data-bs-toggle="tab">
                                <x-tabler-building style="width: 0.8rem;" />
                                <span>기본정보</span>
                            </a>
                        </li>
                        @foreach ($rooms as $room)
                            <li class="nav-item {{ $room->is_active ? '' : 'filtered' }}" data-id="{{ $room->id }}">
                                <a href="#tab-room-{{ $room->id }}"
                                    class="nav-link {{ $room->is_active ? 'handle' : 'bg-danger text-white' }}"
                                    data-bs-toggle="tab">
                                    <x-tabler-arrows-move style="width: 0.9rem;" />
                                    <span>{{ $room->name }}</span>
                                </a>
                            </li>
                        @endforeach
                        <li class="nav-item ms-auto">
                            <a href="#tab-write-room" class="nav-link" data-bs-toggle="tab">
                                <x-tabler-plus style="width: 0.8rem;" />
                                <span>객실 추가</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane active show" id="tab-pension">
                            @include('admin.pages.pension.partials.form.pensionEdit')
                        </div>
                        @foreach ($rooms as $room)
                            <div class="tab-pane" id="tab-room-{{ $room->id }}">
                                @include('admin.pages.pension.partials.form.roomEdit', $room)
                            </div>
                        @endforeach

                        <div class="tab-pane" id="tab-write-room">
                            @include('admin.pages.pension.partials.form.roomWrite')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- @section('beforeScript')@endsection --}}
@section('beforeScript')
    <script src="{{ asset('assets/plugins/validation/just-validate.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/address.js') }}?v={{ env('SITES_ADMIN_ASSETS_VERSION') }}"></script>
    <script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
    <script src="//dapi.kakao.com/v2/maps/sdk.js?appkey=49f49d684621b554bb7e4382786b3e46&libraries=services"></script>
    <script src="{{ asset('assets/plugins/uppy/uppy.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/tagify/tagify.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/SortableJS/sortable.min.js') }}"></script>

    <script>
        const sortTable = document.getElementById('sortTable');
        new Sortable(sortTable, {
            animation: 150,
            handle: '.handle',
            filter: '.filtered',
            onMove: function(evt) {
                return !evt.related.classList.contains('filtered');
            },
            onEnd: function(e) {
                const seqIdxes = [];
                const childNodes = document.querySelectorAll(`#sortTable > li`);
                childNodes.forEach(item => {
                    if (item.getAttribute('data-id')) {
                        seqIdxes.push(item.getAttribute('data-id'));
                    }
                });
                if (seqIdxes.length > 1) {
                    common.ajax.postJson('{{ route('admin.pension.data') }}', {
                        pType: 'setRoomSeq',
                        seqIdxes
                    });
                }
            }
        });
    </script>
@endsection
