@extends('layouts.app')

@section('title', $blogPost->dogRace->name . ' - Aidella')

@section('component', 'SinglePost')

@section('meta_title', $blogPost->meta_title ? $blogPost->meta_title : $blogPost->dogRace->name . ' - Aidella')
@section('meta_description', $blogPost->meta_description ? $blogPost->meta_description : $blogPost->dogRace->name . ' - Aidella')

@section('data')
    {!! json_encode([
        'dogPages' => $dogPages,
        'blogPost' => $blogPost
    ]) !!}
@endsection
