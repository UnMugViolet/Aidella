@extends('layouts.app')

@section('title', 'Conditions Générales d\'utilisation - Aidella')

@section('component', 'CGU')

{{-- SEO --}}
@section('robots_content', 'noindex, nofollow')
@section('thumbnail', asset('images/logo-terres-aidella-big.webp'))

@section('data')
    { dogPages: @json($dogPages) }
@endsection
