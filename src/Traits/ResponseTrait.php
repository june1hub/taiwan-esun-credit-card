<?php

namespace June1hub\TaiwanEsunCreditCard\Traits;

trait ResponseTrait 
{
    public static function getResponseMessage($code)
    {
        return isset(config('esun.creditCard.payStatusCode')[$code]) 
            ? config('esun.creditCard.payStatusCode')[$code]
            : false;
    }

    public static function getResponseIsSuccess($code)
    {
        return !empty(config('esun.creditCard.successResponceCode')[$code]) 
        ? true
        : false;
    }

    public static function getResponseIsError($code)
    {
        return [
            'status' => false,
            'message' => '錯誤，無法解析。'
        ];
    }

}

?>