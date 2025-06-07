@extends('layouts.app')

@section('title', 'Politique de confidentialité - Aidella')

@section('component', 'PrivacyPolicy')

{{-- SEO --}}
@section('robots_content', 'noindex, nofollow')
@section('thumbnail', asset('images/logo-terres-aidella-big.webp'))

@section('data')
    { dogPages: @json($dogPages) }
@endsection
