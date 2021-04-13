<?php
ob_start();
$txnid = uniqid();
$amount = number_format(0.1, 8);
$type = "TRX";
$address = "TNnyrLpdmJSyphfS82UShYxYXAoBLK65VD";
$CustomerId = "Karna001";
$CustomerEmail = "karna2mail@gmail.com";
$CustomerPhone = "9789416148";
$CustomerName = "Karna";
$MERCHANT_KEY = "Merchant Key Generated from App";
$PAYU_BASE_URL = "https://sw-customer-api.bitazure.com/api/v1/requests";

$header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
$payload = json_encode(
    [
        'iss'=> 'bitazure.com',
        'exp' => strtotime("+5 hours"),
        'txnid' => $txnid, 
        'amount' => $amount, 
        'type' => $type, 
        'address' => $address, 
        'cid' => $CustomerId, 
        'name' => $CustomerName, 
        'email' => $CustomerEmail,         
        'mobile' => $CustomerPhone
    ]
);
$base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
$base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
$signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $MERCHANT_KEY, true);
$base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
$jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

$cURLConnection = curl_init();

curl_setopt($cURLConnection, CURLOPT_URL, $PAYU_BASE_URL);

$headr = array();
$headr[] = 'Content-length: 0';
$headr[] = 'Content-type: application/json';
$headr[] = 'Authorization: '.$jwt;
$headr[] = 'Access-Control-Allow-Origin: '.$_SERVER['SERVER_NAME'];

curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, $headr);
curl_setopt($cURLConnection, CURLOPT_POST, true);
curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);

$response= curl_exec($cURLConnection);

if(curl_errno($cURLConnection)){
	echo $_SERVER['SERVER_NAME'];
    echo 'Request Error:' . curl_error($cURLConnection);
}
else{
	echo $response;
}
curl_close($cURLConnection);
?>
