@extends('admin.layouts.default.layout')


@php
    $pageData = [
        'title' => '펜션 상세정보',
        'name' => $pension->name . ' 상세정보',
    ];
@endphp

@section('title', "| {$pageData['title']}")

{{-- @section('beforeStyle')@endsection --}}

{{-- @section('afterStyle')@endsection --}}

@section('mainContent')
    <div class="row">
        <div class="flex-fill">
            <div class="card">
                <div class="card-header pb-0">
                    <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
                        <li class="nav-item">
                            <a href="#tab-pension" class="nav-link active" data-bs-toggle="tab">
                                <x-tabler-building />
                                <span>기본정보</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="tab-pension">
                        <div class="tab-pane active show" id="tab-pension">
                            @include('admin.pages.pension.partials.form.pensionEdit')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- @section('beforeScript')@endsection --}}
{{-- @section('afterScript') @endsection --}}
