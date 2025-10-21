@extends('layouts.app')


@section('title', 'Module COMESA')


@section('content')
<div class="container-fluid py-4">
<div class="row justify-content-center">
<div class="col-12 col-xl-11">
<nav aria-label="breadcrumb" class="mb-3">
<ol class="breadcrumb bg-white px-3 py-2 rounded-3 shadow-sm">
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
<li class="breadcrumb-item active" aria-current="page">Module COMESA</li>
</ol>
</nav>


<div class="card border-0 shadow-sm rounded-3">
<div class="card-header d-flex justify-content-between align-items-center">
<div>
<h3 class="mb-0">Module COMESA</h3>
<small class="text-light">Gestion multi-pays, taux locaux et règles régionales</small>
</div>
<div>
<button id="saveAllBtn" class="btn btn-primary">Enregistrer tout</button>
</div>
</div>


<div class="card-body">
<div class="row">
<div class="col-12">
<ul class="nav nav-tabs mb-3" id="comesaTabs" role="tablist">
<li class="nav-item" role="presentation">
<button class="nav-link active" id="config-tab" data-bs-toggle="tab" data-bs-target="#config" type="button" role="tab">Configuration par pays <i class="fas fa-flag ms-2"></i></button>
</li>
<li class="nav-item" role="presentation">
<button class="nav-link" id="rates-tab" data-bs-toggle="tab" data-bs-target="#rates" type="button" role="tab">Taux locaux <i class="fas fa-coins ms-2"></i></button>
</li>
<li class="nav-item" role="presentation">
<button class="nav-link" id="rules-tab" data-bs-toggle="tab" data-bs-target="#rules" type="button" role="tab">Règles régionales <i class="fas fa-gavel ms-2"></i></button>
</li>
</ul>


<div class="tab-content">
<div class="tab-pane fade show active" id="config" role="tabpanel">
<div class="card border-0 p-3 mb-3">
<div class="d-flex justify-content-between align-items-center mb-3">
<h5 class="mb-0">Configurations par pays</h5>
<div class="d-flex">
<select id="countrySelect" class="form-select d-inline-block" style="width:220px">
<option value="">-- Choisir un pays --</option>
@foreach(\App\Models\Pays::orderBy('nom')->get() as $p)
<option value="{{ $p->id }}">{{ $p->nom }} ({{ $p->code }})</option>
@endforeach
</select>
<button id="addConfigBtn" class="btn btn-outline-light ms-2">Ajouter</button>
</div>
</div>


<div id="countryBadges" class="country-badges mb-3">
@foreach(\App\Models\Pays::orderBy('nom')->get() as $p)
<div class="badge-item" data-id="{{ $p->id }}">{{ $p->nom }} ({{ $p->code }})</div>
@endforeach
</div>


<div class="table-responsive">
<table class="table table-hover align-middle" id="configsTable">
<thead>
@endsection