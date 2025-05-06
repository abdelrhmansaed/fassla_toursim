<?php

namespace App\Services;
use Illuminate\Support\Facades\Http;

class CurrencyConverter
{
    protected static $rates = [];

    public static function convertToEGP($amount, $fromCurrency)
    {
        $fromCurrency = strtoupper($fromCurrency);

        if ($fromCurrency === 'EGP') {
            return $amount;
        }

        // لو معانا الريت في الكاش الداخلي، نستخدمه
        if (isset(self::$rates[$fromCurrency])) {
            return round($amount * self::$rates[$fromCurrency], 2);
        }

        // استعلام API مره واحدة فقط
        $response = Http::get('https://api.exchangerate-api.com/v4/latest/' . $fromCurrency);

        if (!$response->ok()) {
            throw new \Exception('فشل الاتصال بـ API تحويل العملة.');
        }

        $data = $response->json();

        if (!isset($data['rates']['EGP'])) {
            throw new \Exception('لم يتم العثور على سعر الصرف في الاستجابة.');
        }

        // خزنه في الكاش المحلي
        self::$rates[$fromCurrency] = $data['rates']['EGP'];

        return round($amount * self::$rates[$fromCurrency], 2);
    }
}
