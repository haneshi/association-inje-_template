@extends('admin.layouts.default.layout')


@php
    $pageData = [
        'title' => '관리자 홈',
        'name' => '관리자 리스트',
    ];
@endphp

@section('title', "| {$pageData['title']}")

{{-- @section('beforeStyle')@endsection --}}

{{-- @section('afterStyle')@endsection --}}

@section('mainContent')
    @include('admin.pages.member.partials.list')
@endsection

{{-- @section('beforeScript')@endsection --}}
{{-- @section('afterScript') @endsection --}}

