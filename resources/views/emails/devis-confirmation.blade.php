@extends('emails.layouts.devis-mail')

@section('content')
<h1>Confirmation de votre demande de devis - CREFER</h1>

<p>Cher(e) {{ $devis->nom }} {{ $devis->prenom }},</p>

<p>Nous avons bien reçu votre demande de devis pour une installation solaire photovoltaïque. Nous vous remercions de votre confiance.</p>

<h2>Détails de votre demande :</h2>
<ul>
    <li><strong>Type de bâtiment :</strong> {{ $devis->type_batiment }}</li>
    <li><strong>Consommation annuelle :</strong> {{ $devis->consommation_annuelle }} kWh</li>
    <li><strong>Type de toiture :</strong> {{ $devis->type_toiture }}</li>
    <li><strong>Orientation :</strong> {{ $devis->orientation }}</li>
</ul>

@if($devis->objectifs && count($devis->objectifs) > 0)
<h2>Vos objectifs :</h2>
<ul>
    @foreach($devis->objectifs as $objectif)
    <li>{{ $objectif }}</li>
    @endforeach
</ul>
@endif

<p>Nous allons étudier votre demande dans les plus brefs délais et nous vous contacterons pour vous présenter notre proposition détaillée.</p>

@if($devis->message)
<h2>Votre message :</h2>
<p>{{ $devis->message }}</p>
@endif

<p>
    <a href="{{ route('devis.resultats', $devis->id) }}" class="btn-primary">
        Voir les résultats de l'analyse
    </a>
</p>

<p>Pour toute question, n'hésitez pas à nous contacter.</p>

<p>
    Cordialement,<br>
    L'équipe CREFER
</p>
@endsection
