<?php

namespace App\Casts;

use App\Helpers\MoneyHelper;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Money\Money;

class MoneyCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param array<string, mixed> $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?Money
    {
        if ($value === null) {
            return null;
        }

        $currency = MoneyHelper::getCurrency();

        // Convert decimal to minor units
        $amount = (string)round((float)$value * 100);

        return new Money($amount, $currency);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param array<string, mixed> $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value === null) {
            return null;
        }

        if (!$value instanceof Money) {
            $currency = MoneyHelper::getCurrency();
            $amount = (string)round((float)$value * 100);
            $value = new Money($amount, $currency);
        }

        // Return decimal for storage (stored as major unit in DB decimal column)
        return (float)$value->getAmount() / 100;
    }
}
