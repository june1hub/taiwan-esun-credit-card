<?php

namespace June1hub\TaiwanEsunCreditCard;

use Illuminate\Support\Facades\View;
use June1hub\TaiwanEsunCreditCard\Traits\ResponseTrait;

class EsunCreditCard
{
    use ResponseTrait;
    
    /**
     * 付款
     * 
     * @param params.ONO *(string) : 訂單編號(不可重複)
     * @param params.TA  *(integer): 金額
     * @param params.U   *(url)    : 回傳網址
     * @param params.IC   (integer): 分期 3|6
     * @param params.BPF  (string) : 銀行紅利折抵 Y|N
     */
    public static function pay($params)
    {
        $config = config('esun.creditCard');

        // 網址
        $apiUrl = config('app.env') == 'production' ? $config['apiUrl']['live'] : $config['apiUrl']['test'];
        
        // 特店代碼
        $params['MID'] = $config['MID'];
        // 終端機代碼
        $params['TID'] = $config['TID'];

        // 參數整理
        $data = json_encode($params);
        $mac  = hash('sha256', $data . $config['mackey']);
        $ksn  = $config['ksn'];
        $postData = [
            'data' => $data,
            'mac' => $mac,
            'ksn' => $ksn
        ];

        // i don't know why this is not working
        // return view('esun::send', compact('apiUrl', 'postData'));

        // use this temporarily
        echo '
            <!doctype html>
            <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
                    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
                    <title>Redirect to Pay...</title>
                </head>
                <body>
                    <form action="'.$apiUrl.'" id="pay-form" method="POST">';
                        foreach ($postData as $key => $value) {
                            $value = $key == 'data' ? htmlspecialchars($value): $value;
                            echo '<input type="hidden" name="'.$key.'" value="'.$value.'">';
                        }                        
        echo '                        
                    </form>
                </body>
                <script>
                   document.getElementById("pay-form").submit();
                </script>
            </html>
        ';
        
        
        // View::make('EsunCreditCard::send')
        //     ->with('apiUrl', $apiUrl)
        //     ->with('postData', $postData);
    }

    public static function parseResponse($request)
    {
        $data = $request->DATA;

        // 整理
        $newRequest = [];
        $receive_data_temp_1 = explode(',', $data);
        foreach ($receive_data_temp_1 as $key => $value) {
            $receive_data_temp_2 = explode('=', $value);
            $newRequest[$receive_data_temp_2[0]] = $receive_data_temp_2[1];
        }

        if (!isset($request['MACD']) || !isset($newRequest['RC'])) {
            return self::getResponseIsError();
        }

        return [
            'status' => self::getResponseIsSuccess($newRequest['RC']),
            'message' => self::getResponseMessage($newRequest['RC'])
        ];
    }

    

}