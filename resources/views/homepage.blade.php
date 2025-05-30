@extends('layouts.app')

@section('title', 'Accueil - Aidella')

@section('component', "Homepage")

@section('data')
    { dogRaces: @json($dogRaces) }
@endsection
