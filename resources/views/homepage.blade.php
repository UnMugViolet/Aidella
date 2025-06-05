@extends('layouts.app')

@section('title', 'Accueil - Aidella')

@section('robots_content', 'index, follow')

@section('component', "Homepage")

@section('data')
    { dogPages: @json($dogPages) }
@endsection
