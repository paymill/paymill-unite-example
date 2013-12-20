<?php

// Basic PAYMILL URLs:
$bridge_url         = 'https://bridge.paymill.com';
$paymill_root       = 'https://connect.paymill.com';
$paymill_api_root   = 'https://api.paymill.com/v2/';

// App settings:
$client_id          = '[enter-your-client-id]';
$client_secret      = '[enter-your-client-secret]';

// Requested permissions:
$grant_type         = 'authorization_code';
$scope              = 'transactions_w clients_w payments_w refunds_w webhooks_w';

// Redirect URI
$redirect_uri       = 'http://test.local/paymill-unite-example/system/authMerchant.php';

// Transaction keys (available after successful authorization)
$public_key         = getPublicKeyFromStorage();
$private_key        = getPrivateKeyFromStorage();
$is_live            = getLiveModeFromStorage();


//
// Helper functions:
//

function getPublicKeyFromStorage()
{
    $public_key = null;
    if (($handle = @fopen("system/merchant.csv", "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $num = count($data);

            for ($c=0; $c < $num; $c++) {
                $public_key = $data[2];
            }
        }
        fclose($handle);
    }

    return $public_key;
}

function getPrivateKeyFromStorage()
{
    $private_key = null;
    if (($handle = @fopen("system/merchant.csv", "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $num = count($data);

            for ($c=0; $c < $num; $c++) {
                 $private_key = $data[0];
            }

        }
        fclose($handle);
    }

    return $private_key;
}

function getLiveModeFromStorage()
{
    $is_live = false;
    if (($handle = @fopen("system/merchant.csv", "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $num = count($data);

            for ($c=0; $c < $num; $c++) {
                 $is_live = $data[4]===1;
            }

        }
        fclose($handle);
    }

    return $is_live;
}
