@extends('admin.layouts.default.layout')


@php
    $pageData = [
        'title' => '게시판 수정',
        'name' => '게시판 수정',
    ];
@endphp

@section('title', "| {$pageData['title']}")

{{-- @section('beforeStyle')@endsection --}}

{{-- @section('afterStyle')@endsection --}}

@section('mainContent')
    @include('admin.pages.settings.board.partials.form.edit')
@endsection

{{-- @section('beforeScript')@endsection --}}
{{-- @section('afterScript') @endsection --}}

