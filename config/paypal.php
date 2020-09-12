<?php

return [
    'client_id' => 'AfcP0LpRkv3-ai_2GCgklDh3UXTVOVO9r_xOi1wV59W5HtDeXCL-m6Ilf_kpaHOLmCHnFU1zxJPOcAHy',
    'secret' => 'EJzQLhH3b5JdMaOSdGvdYana4x0GLhOdvz-aMD1xRI5H_RNZZ6RcE3l2zsWt98AQY3Nl1HEp92tASoXV',
    'settings' => array(
        'mode' => env('PAYPAL_MODE', 'sandbox'),
        'http.ConnectionTimeOut' => 60,
        'log.LogEnabled' => true,
        'log.FileName' => storage_path() . '/logs/paypal.log',
        'log.LogLevel' => 'ERROR'
    ),
];
