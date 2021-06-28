<?php

require_once 'Jibit.class.php';

// Your Api Key :
$apiKey = 'xxxxx';
// Your Api Secret :
$apiSecret = 'xx-xx';

/** @var Jibit $jibit */
$jibit = new Jibit($apiKey, $apiSecret);

// Making payments request
// you should save the order details in DB, you need if for verify
$requestResult = $jibit->paymentRequest(1000, '1', '09375065007', 'http://salam.ir');


if (!empty($requestResult['pspSwitchingUrl'])) {
    //successful result and redirect to PG
    header('Location: ' . $requestResult['pspSwitchingUrl']);
}
if (!empty($requestResult['errors'])) {
    //fail result and show the error
    echo $requestResult['errors'][0]['code'] . ' ' . $requestResult['errors'][0]['message'];
}



