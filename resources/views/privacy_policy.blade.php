@extends('layouts.app')

@section('title', 'Politique de confidentialité - Aidella')

@section('component', 'PrivacyPolicy')

@section('robots_content', 'noindex, nofollow')

@section('data')
    { dogPages: @json($dogPages) }
@endsection
