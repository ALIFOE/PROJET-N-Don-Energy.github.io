<?php

namespace App\Traits;

trait HasCurrency
{
    /**
     * Formatte un montant en FCFA
     *
     * @param float|int $amount
     * @return string
     */
    public static function formatAmount($amount): string
    {
        return number_format($amount, 0, ',', ' ') . ' FCFA';
    }

    /**
     * Convertit un montant en format lisible FCFA
     *
     * @param float|int $amount
     * @return string
     */
    public function getFormattedPriceAttribute(): string
    {
        return self::formatAmount($this->price);
    }

    /**
     * Convertit un montant total en format lisible FCFA
     *
     * @param float|int $amount
     * @return string
     */
    public function getFormattedTotalPriceAttribute(): string
    {
        return self::formatAmount($this->total_price);
    }
}
