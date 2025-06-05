@extends('layouts.app')

@section('title', 'Mentions Légales - Aidella')

@section('component', 'LegalMentions')

@section('robots_content', 'noindex, nofollow')


@section('data')
    { dogPages: @json($dogPages) }
@endsection
