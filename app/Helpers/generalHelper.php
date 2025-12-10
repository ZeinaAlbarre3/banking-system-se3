<?php

if (!function_exists('formatMoney')) {
    /**
     * Format number as money with commas
     *
     * @param int|float|null $amount
     * @param string $currency
     * @return string
     */
    function formatMoney(int|float|null $amount, string $currency = ''): string
    {
        if ($amount === null) {
            return '0';
        }

        $formatted = number_format($amount, 0, '.', ',');

        return $currency
            ? $formatted . ' ' . $currency
            : $formatted;
    }
}

