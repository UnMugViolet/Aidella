@extends('layouts.app')

@section('title', 'Nos conseils - Aidella')

@section('component', 'ListPosts')

@section('meta_title', 'Nos conseils - Aidella')
@section('meta_description', 'Retrouvez la liste de nops articles sur des sujets variés afin de prendre soin de votre animal de compagnie')

@section('data')
    {!! json_encode([
        'dogPages' => $dogPages,
        'blogPosts' => $blogPosts
    ]) !!}
@endsection
