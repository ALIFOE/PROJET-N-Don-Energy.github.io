<?php

namespace App\Traits;

trait HasRoles
{
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isTechnicien(): bool
    {
        return $this->role === 'technicien';
    }

    public function isClient(): bool
    {
        return $this->role === 'client';
    }
}