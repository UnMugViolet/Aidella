@extends('layouts.app')

@section('title', 'Accueil - Aidella')

@section('component', "Homepage")

@section('data')
    { dogPages: @json($dogPages) }
@endsection
