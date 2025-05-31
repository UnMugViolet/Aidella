@extends('layouts.app')

@section('title', $dogRace->name . ' - Aidella')

@section('component', 'SingleDog')

@section('data')
    {!! json_encode(['dogRace' => $dogRace, 'dogRaces' => $dogRaces]) !!}
@endsection
