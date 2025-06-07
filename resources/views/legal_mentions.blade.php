@extends('layouts.app')

@section('title', 'Mentions Légales - Aidella')

@section('component', 'LegalMentions')

{{-- SEO --}}
@section('robots_content', 'noindex, nofollow')
@section('thumbnail', asset('images/logo-terres-aidella-big.webp'))

@section('data')
    { dogPages: @json($dogPages) }
@endsection
