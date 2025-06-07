@extends('layouts.app')

@section('title', 'À propos - Aidella')

@section('component', 'About')

{{-- SEO --}}
@section('meta_title', 'À propos - Aidella')
@section('meta_description', 'Découvrez les terres d\'Aidella, Nos valeurs, la socialisation, la santé et le bien-être de nos chiots, garantissant des compagnons fidèles et équilibrés')
@section('robots_content', 'index, follow')
@section('thumbnail', asset('images/logo-terres-aidella-big.webp'))
@section('data')
    { dogPages: @json($dogPages) }
@endsection
