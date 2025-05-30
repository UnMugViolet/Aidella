@extends('layouts.app')

@section('title', $dogRace->name . ' - Aidella')

@section('component', 'SingleDog')

@section('data')
    @json($dogRace)
@endsection
