@extends('layouts.app')

@section('title', 'À propos - Aidella')

@section('component', 'About')

@section('data')
    { dogPages: @json($dogPages) }
@endsection
