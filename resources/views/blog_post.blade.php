@extends('layouts.app')

@section('title', $blogPost->title)

@section('component', 'SinglePost')

@section('meta_title', $blogPost->meta_title ? $blogPost->meta_title : $blogPost->title . ' - Aidella')
@section('meta_description', $blogPost->meta_description ? $blogPost->meta_description : $blogPost->title . ' - Aidella')
@section('robots_content', 'index, follow')

@section('data')
    {!! json_encode([
        'dogPages' => $dogPages,
        'blogPost' => $blogPost
    ]) !!}
@endsection
