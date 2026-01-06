@extends('admin.layouts.default.layout')


@php
    $pageData = [
        'title' => '관광지',
        'name' => '관광지 리스트',
    ];
@endphp

@section('title', "| {$pageData['title']}")

{{-- @section('beforeStyle')@endsection --}}

{{-- @section('afterStyle')@endsection --}}

@section('mainContent')
    @include('admin.pages.travel.partials.list')
@endsection

{{-- @section('beforeScript')@endsection --}}
{{-- @section('afterScript') @endsection --}}
