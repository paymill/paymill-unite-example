<?php

// Basic PAYMILL URLs:
$bridge_url         = 'https://bridge.chipmunk.dev/';
$paymill_root       = 'https://connect.chipmunk.dev/';
$paymill_api_root   = 'https://api.chipmunk.dev/v2/';
// does session exists
if(isset($_SESSION['userConfig'])) {
    $client_id        = $_SESSION['userConfig']['clientId'];
    $client_secret    = $_SESSION['userConfig']['clientSecret'];

    $grant_type       = $_SESSION['userConfig']['grantType'];
    $scopes           = $_SESSION['userConfig']['scope'];

    $redirect_uri     = $_SESSION['userConfig']['redirectUri'];

    $bridge_url       = $_SESSION['userConfig']['bridgeUrl'];
    $paymill_api_root = $_SESSION['userConfig']['apiRoot'];
    $paymill_root     = $_SESSION['userConfig']['paymillRoot'];
    $redirect_uri     = $_SESSION['userConfig']['redirectUri'];
} else {
    // App settings:
    $client_id     = 'app_66237214a4ea54303386401c2cfb28c47a17df384';
    $client_secret = '0cd386c8c18a328fbc94dfd4e98f0e82';

    // Requested permissions:
    $grant_type    = 'authorization_code';
    $scopes        = 'transactions_w clients_w payments_w refunds_w webhooks_w';

    // Redirect URI
    $redirect_uri  = 'https://market.chipmunk.dev/system/authMerchant.php';

}

// Transaction keys (available after successful authorization)
$public_key  = getPublicKeyFromStorage();
$private_key = getPrivateKeyFromStorage();
$is_live     = getLiveModeFromStorage();


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
