@extends('layouts.app')

@section('content')
@vite(['resources/css/app.css', 'resources/js/app.js'])
<div class="container mt-5">
    <div class="row align-items-center" style="min-height: 60vh ;">
        <div class="col-md-6">
            <h1 class="display-3 fw-bold medical-red">MediTrackD</h1>
            <p class="lead text-dark">
                Gerez vos dossier medicaux de maniere efficace et sécurisée avec MediTrackD, votre système de gestion intégré de dossiers médicaux.
            </p>
            <div class="mt-4">
                <a href="{{ ('dashboard') }}" class="btn btn-primary btn-lg px-4 text-dark">
                    Accéder à MediTrackD
                </a>
            </div>
        </div>
        <div class="col-md-6 text-center">
            <i class="bi bi-heart-pulse medical-icon heartBeat-active"></i>
        </div>
    </div>
</div>
@endsection
