<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'meteo_config',
        'report_frequency',
        'report_formats',
        'notification_preferences',
        'theme_preference',
        'language',
        'two_factor_enabled',
        'two_factor_secret'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'report_formats' => 'array',
        'meteo_config' => 'array',
        'notification_preferences' => 'array',
        'two_factor_enabled' => 'boolean'
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
}
