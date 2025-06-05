@extends('layouts.app')

@section('title', 'À propos - Aidella')

@section('component', 'About')

@section('robots_content', 'index, follow')

@section('data')
    { dogPages: @json($dogPages) }
@endsection
