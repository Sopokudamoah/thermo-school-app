<?php

namespace App\Helpers;

use App\Models\AcademicSetting;
use Illuminate\Support\Facades\Cache;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;

class MoneyHelper
{
    /**
     * Format money object to string
     *
     * @param Money|null $money
     * @return string
     */
    public static function format($money): string
    {
        if ($money === null) {
            return '0.00';
        }

        if (!$money instanceof Money) {
            $amount = (string)round((float)$money * 100);
            $money = new Money($amount, self::getCurrency());
        }

        $currencies = new ISOCurrencies();
        $setting = Cache::remember('academic_setting', 3600, function () {
            return AcademicSetting::first();
        });

        $locale = 'en_GH';
        try {
            $numberFormatter = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);

            if ($setting && $setting->currency_symbol) {
                $numberFormatter->setSymbol(\NumberFormatter::CURRENCY_SYMBOL, $setting->currency_symbol);
            }

            $moneyFormatter = new IntlMoneyFormatter($numberFormatter, $currencies);
            return $moneyFormatter->format($money);
        } catch (\Exception $e) {
            // Fallback if intl is not available or fails
            $symbol = ($setting && $setting->currency_symbol) ? $setting->currency_symbol : '₵';
            return $symbol . number_format((float)$money->getAmount() / 100, 2);
        }
    }

    /**
     * Get the system currency
     *
     * @return \Money\Currency
     */
    public static function getCurrency(): \Money\Currency
    {
        $code = Cache::remember('currency_code', 3600, function () {
            $setting = AcademicSetting::first();
            return $setting ? $setting->currency_code : 'GHS';
        });
        return new \Money\Currency($code);
    }

    /**
     * Get all available currencies
     *
     * @return array
     */
    public static function getCurrenciesList(): array
    {
        $currencies = new ISOCurrencies();
        $list = [];
        foreach ($currencies as $currency) {
            $list[] = $currency->getCode();
        }
        sort($list);
        return $list;
    }

    /**
     * Get a zero money object
     *
     * @return Money
     */
    public static function zero(): Money
    {
        return new Money(0, self::getCurrency());
    }
}
