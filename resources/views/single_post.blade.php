@extends('layouts.app')

@push('css')
    <link rel="stylesheet" href="{{ asset('/css/global.css') }}">
@endpush

@section('title', $blogPost->dogRace->name . ' - Aidella')

@section('component', 'SinglePost')

@section('robots_content', 'index, follow')


@section('meta_title', $blogPost->meta_title ? $blogPost->meta_title : $blogPost->dogRace->name . ' - Aidella')
@section('meta_description', $blogPost->meta_description ? $blogPost->meta_description : $blogPost->dogRace->name . ' - Aidella')

@section('data')
    {!! json_encode([
        'dogPages' => $dogPages,
        'blogPost' => $blogPost
    ]) !!}
@endsection
