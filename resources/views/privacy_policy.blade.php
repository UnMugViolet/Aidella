@extends('layouts.app')

@section('title', 'Politique de confidentialité - Aidella')

@section('component', 'PrivacyPolicy')

@section('data')
    { dogPages: @json($dogPages) }
@endsection
