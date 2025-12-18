@extends('admin.layouts.default.layout')


@php
    $pageData = [
        'title' => '펜션관리 홈',
    ];
@endphp

@section('title', "| {$pageData['title']}")

{{-- @section('beforeStyle')@endsection --}}

{{-- @section('afterStyle')@endsection --}}

@section('mainContent')
    {{-- @include('admin.pages.member.partials.list') --}}
@endsection

{{-- @section('beforeScript')@endsection --}}
{{-- @section('afterScript') @endsection --}}

