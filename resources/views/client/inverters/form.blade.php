@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Configuration de l\'onduleur') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('client.inverters.connect') }}" id="inverterForm">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="brand">{{ __('Marque') }}</label>
                            <select class="form-control @error('brand') is-invalid @enderror" id="brand" name="brand" required>
                                <option value="">Sélectionner une marque</option>
                                <option value="sma">SMA</option>
                                <option value="fronius">Fronius</option>
                                <option value="huawei">Huawei</option>
                                <option value="solaredge">SolarEdge</option>
                                <option value="growatt">Growatt</option>
                                <option value="goodwe">GoodWe</option>
                                
                            </select>
                            @error('brand')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="model">{{ __('Modèle') }}</label>
                            <select class="form-control @error('model') is-invalid @enderror" id="model" name="model" required disabled>
                                <option value="">Sélectionner d'abord une marque</option>
                            </select>
                            @error('model')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="ip_address">{{ __('Adresse IP') }}</label>
                            <input type="text" class="form-control @error('ip_address') is-invalid @enderror" id="ip_address" name="ip_address" required>
                            @error('ip_address')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="port">{{ __('Port') }}</label>
                            <input type="number" class="form-control @error('port') is-invalid @enderror" id="port" name="port" value="502" required>
                            @error('port')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="username">{{ __('Nom d\'utilisateur') }}</label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" required>
                            @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="password">{{ __('Mot de passe') }}</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Connecter l\'onduleur') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/inverter-form.js') }}"></script>
@endpush
@endsection