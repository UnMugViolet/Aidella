@extends('layouts.app')

@section('title', 'Mentions Légales - Aidella')

@section('component', 'LegalMentions')

@section('data')
    { dogPages: @json($dogPages) }
@endsection
