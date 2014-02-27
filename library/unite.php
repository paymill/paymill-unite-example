<?php

// Basic PAYMILL URLs:
$bridge_url         = 'https://bridge.chipmunk.dev/';
$paymill_root       = 'https://connect.chipmunk.dev/';
$paymill_api_root   = 'https://api.chipmunk.dev/v2/';

// App settings:
$client_id          = 'app_66237214a4ea54303386401c2cfb28c47a17df384';
$client_secret      = '0cd386c8c18a328fbc94dfd4e98f0e82';

// Requested permissions:
$grant_type         = 'authorization_code';
$scope              = 'transactions_w clients_w payments_w refunds_w webhooks_w';

// Redirect URI
$redirect_uri       = 'https://market.chipmunk.dev/system/authMerchant.php';

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
