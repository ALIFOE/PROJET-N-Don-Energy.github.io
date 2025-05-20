<!DOCTYPE html>
<html>
<head>
    <title>Nouvelle Inscription</title>
</head>
<body>
    <h1>Nouvelle Inscription à une Formation</h1>    <p>Une nouvelle inscription a été effectuée. Voici les détails :</p>

    <ul>
        <li><strong>Nom :</strong> {{ $inscription->nom }}</li>
        <li><strong>Email :</strong> {{ $inscription->email }}</li>
        <li><strong>Téléphone :</strong> {{ $inscription->telephone }}</li>
        <li><strong>Formation :</strong> {{ $inscription->formation->titre }}</li>
    </ul>

    <h2>Documents fournis :</h2>
    <ul>
        <li>
            <strong>Acte de naissance :</strong>
            <a href="{{ Storage::disk('public')->url($inscription->acte_naissance_path) }}">Voir le document</a>
        </li>
        <li>
            <strong>CNI :</strong>
            <a href="{{ Storage::disk('public')->url($inscription->cni_path) }}">Voir le document</a>
        </li>
        <li>
            <strong>Diplôme :</strong>
            <a href="{{ Storage::disk('public')->url($inscription->diplome_path) }}">Voir le document</a>
        </li>
        @if($inscription->autres_documents_paths)
            <li>
                <strong>Autres documents :</strong>
                <ul>
                    @foreach($inscription->autres_documents_paths as $path)
                        <li><a href="{{ Storage::disk('public')->url($path) }}">Voir le document</a></li>
                    @endforeach
                </ul>
            </li>
        @endif
    </ul>

    <p>
        <a href="{{ route('admin.formations.inscriptions') }}">Voir toutes les inscriptions</a>
    </p>
</body>
</html>
