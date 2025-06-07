@extends('layouts.app')

@push('css')
    <link rel="stylesheet" href="{{ asset('/css/global.css') }}">
@endpush

@section('title', $blogPost->title . ' - Aidella')

@section('component', 'SinglePost')

{{-- SEO --}}
@section('meta_title', $blogPost->meta_title ? $blogPost->meta_title : $blogPost->title . ' - Aidella')
@section('meta_description', $blogPost->meta_description ? $blogPost->meta_description : $blogPost->title . ' - Aidella')
@section('thumbnail', isset($blogPost->pictures[0]) && $blogPost->pictures[0]->path ? asset($blogPost->pictures[0]->path) : asset('images/logo-terres-aidella-big.webp'))
@section('robots_content', 'index, follow')

@section('data')
    {!! json_encode([
        'dogPages' => $dogPages,
        'blogPost' => $blogPost
    ]) !!}
@endsection
