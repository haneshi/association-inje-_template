@extends('admin.layouts.default.layout')


@php
    $pageData = [
        'title' => '펜션관리 홈',
        'name' => '펜션 리스트',
    ];
@endphp

@section('title', "| {$pageData['title']}")

{{-- @section('beforeStyle')@endsection --}}

{{-- @section('afterStyle')@endsection --}}

@section('mainContent')
    @include('admin.pages.pension.partials.list')
@endsection

{{-- @section('beforeScript')@endsection --}}
{{-- @section('afterScript') @endsection --}}

