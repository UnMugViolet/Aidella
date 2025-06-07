@extends('layouts.app')

@section('title', 'Nos articles - Aidella')

@section('component', 'ListPosts')

{{-- SEO --}}
@section('meta_title', 'Nos articles - Aidella')
@section('meta_description', 'Retrouvez la liste de nos articles sur des sujets variés afin de prendre soin de votre animal de compagnie')
@section('thumbnail', asset('images/logo-terres-aidella-big.webp'))
@section('robots_content', 'index, follow')


@section('data')
    {!! json_encode([
        'dogPages' => $dogPages,
        'blogPosts' => $blogPosts
    ]) !!}
@endsection
