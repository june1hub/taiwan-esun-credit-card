<?php

namespace June1hub\TaiwanEsunCreditCard;

use Illuminate\Support\Facades\Facade;

class EsunCreditCardFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'esun-credit-card';
    }
}