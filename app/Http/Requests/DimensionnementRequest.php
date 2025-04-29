<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DimensionnementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telephone' => 'required|string|max:20',
            'adresse' => 'required|string|max:255',
            'ville' => 'required|string|max:100',
            'code_postal' => 'required|string|max:10',
            'type_logement' => 'required|string|in:maison,appartement,commerce,industriel',
            'surface_toiture' => 'required|numeric|min:0',
            'orientation' => 'required|string|in:sud,sud-est,sud-ouest,est,ouest',
            'facture_annuelle' => 'required|numeric|min:0',
            'fournisseur' => 'required|string|max:255',
            'nb_personnes' => 'required|integer|min:1',
            'pays' => 'required|string|max:100',
            'budget' => 'required|numeric|min:0',
            'type_installation' => 'required|string|in:toiture,sol,ombriere,autre',
            'equipements' => 'required|array',
            'equipements.*' => 'string|in:climatisation,pompe_chaleur,vehicule_electrique',
            'objectifs' => 'required|array',
            'objectifs.*' => 'string|in:autonomie,economie,ecologie'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'required' => 'Le champ :attribute est obligatoire.',
            'email' => 'Le champ :attribute doit être une adresse email valide.',
            'max' => 'Le champ :attribute ne doit pas dépasser :max caractères.',
            'min' => 'Le champ :attribute doit être au minimum :min.',
            'numeric' => 'Le champ :attribute doit être un nombre.',
            'integer' => 'Le champ :attribute doit être un nombre entier.',
            'in' => 'La valeur sélectionnée pour :attribute n\'est pas valide.'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'nom' => 'nom complet',
            'email' => 'adresse email',
            'telephone' => 'numéro de téléphone',
            'adresse' => 'adresse',
            'ville' => 'ville',
            'code_postal' => 'code postal',
            'type_logement' => 'type de logement',
            'surface_toiture' => 'surface de toiture',
            'orientation' => 'orientation',
            'facture_annuelle' => 'facture annuelle',
            'fournisseur' => 'fournisseur d\'électricité',
            'nb_personnes' => 'nombre de personnes',
            'pays' => 'pays',
            'budget' => 'budget envisagé',
            'type_installation' => 'type d\'installation',
            'equipements' => 'équipements',
            'equipements.*' => 'équipement',
            'objectifs' => 'objectifs du projet',
            'objectifs.*' => 'objectif'
        ];
    }
}