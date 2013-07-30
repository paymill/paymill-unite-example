<?php

$client_id          = '<YOUR-APP-ID>';
$private_access_key = '<YOUR-PRIVATE-KEY>';
$grant_type         = 'authorization_code';

// Requested permissions:
$scope              = 'transactions_w clients_w payments_w refunds_w webhooks_w';

$merchant_root      = 'http://test.local/paymill-unite-example/merchant';
$shop_root          = 'http://test.local/paymill-unite-example/';
$redirect_uri       = 'http://test.local/paymill-unite-example/system/authMerchant.php';
$paymill_root       = 'https://connect.paymill.com';
$paymill_api_root   = 'https://api.paymill.com/v2/';
?>