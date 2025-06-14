@extends('layouts.app')

@section('title', 'Elevage canin Les Terres d\'Aidella')


{{-- SEO --}}
@section('meta_title', 'Elevage canin Les Terres d\'Aidella')
@section('meta_description', 'Elevage canin près d\'Epinal, spécialisé dans les chiens LOF. Berger Australien, Labrador, Cavalier King Charles, Bouledogue Français, Coton de Tulear, Spitz Nain')
@section('thumbnail', asset('images/logo-terres-aidella-big.webp'))
@section('robots_content', 'index, follow')

@section('component', "Homepage")

@section('data')
    { dogPages: @json($dogPages) }
@endsection
