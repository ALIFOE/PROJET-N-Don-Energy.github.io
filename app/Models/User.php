<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'meteo_config'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];    protected $casts = [
        'email_verified_at' => 'datetime',
        'report_formats' => 'array',
        'meteo_config' => 'array',
    ];

    public function onduleurs()
    {
        return $this->hasMany(Onduleur::class);
    }

    public function dimensionnements()
    {
        return $this->hasMany(Dimensionnement::class);
    }

    /**
     * Vérifie si l'utilisateur a le rôle spécifié
     *
     * @param string $role
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Vérifie si l'utilisateur est un administrateur
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }
}
