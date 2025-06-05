@extends('layouts.app')

@section('title', 'Nos articles - Aidella')

@section('component', 'ListPosts')

@section('meta_title', 'Nos articles - Aidella')
@section('meta_description', 'Retrouvez la liste de nops articles sur des sujets variés afin de prendre soin de votre animal de compagnie')
@section('robots_content', 'index, follow')


@section('data')
    {!! json_encode([
        'dogPages' => $dogPages,
        'blogPosts' => $blogPosts
    ]) !!}
@endsection
