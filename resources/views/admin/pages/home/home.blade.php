@extends('admin.layouts.default.layout')


@php
    $pageData = [
        'title' => '협회 관리자',
        'name' => '협회 관리자 홈',
    ];
@endphp

@section('title', "| {$pageData['title']}")

{{-- @section('beforeStyle')@endsection --}}

{{-- @section('afterStyle')@endsection --}}

@section('mainContent')
    <div class="text-align-center">
        메인 컨텐츠
    </div>
@endsection

{{-- @section('beforeScript')@endsection --}}
{{-- @section('afterScript') @endsection --}}

