<?php

    //
    // Set required parameters
    //

    // use sesssion parameters, if exists
    if(isset($_SESSION['userConfig'])) {
        // App settings:
        $client_id        = $_SESSION['userConfig']['clientId'];
        $client_secret    = $_SESSION['userConfig']['clientSecret'];
        // Requested permissions:
        $grant_type       = $_SESSION['userConfig']['grantType'];
        $scopes           = $_SESSION['userConfig']['scope'];
        // Redirect URI
        $redirect_uri     = $_SESSION['userConfig']['redirectUri'];
        // Basic PAYMILL URLs:
        $bridge_url       = $_SESSION['userConfig']['bridgeUrl'];
        $paymill_api_root = $_SESSION['userConfig']['apiRoot'];
        $paymill_root     = $_SESSION['userConfig']['paymillRoot'];
    } else {
        // App settings:
        $client_id     = 'app_66237214a4ea54303386401c2cfb28c47a17df384';
        $client_secret = '0cd386c8c18a328fbc94dfd4e98f0e82';
        // Requested permissions:
        $grant_type    = 'authorization_code';
        $scopes        = 'transactions_w clients_w payments_w refunds_w webhooks_w';
        // Redirect URI
        $redirect_uri  = getActualAuthUrl();
        // Basic PAYMILL URLs:
        $bridge_url       = 'https://bridge.paymill.com/';
        $paymill_root     = 'https://connect.paymill.com/';
        $paymill_api_root = 'https://api.paymill.com/v2/';
    }

    // Transaction keys (available after successful authorization)
    $public_key  = getPublicKeyFromStorage();
    $private_key = getPrivateKeyFromStorage();
    $is_live     = getLiveModeFromStorage();


    //
    // Helper functions:
    //

    /**
     * sets the url to <current location/library/authMerchant.php>
     * required for the redirect after connecting
     * @var $url string
     * @return string The Auth-Url
     */
    function getActualAuthUrl()
    {
        $url = '';

        if (isset($_SERVER['HTTPS']) && filter_var($_SERVER['HTTPS'], FILTER_VALIDATE_BOOLEAN))
            $url .= 'https';
        else
            $url .= 'http';

        $url .= '://';

        if (isset($_SERVER['HTTP_HOST']))
            $url .= $_SERVER['HTTP_HOST'];
        elseif (isset($_SERVER['SERVER_NAME']))
            $url .= $_SERVER['SERVER_NAME'];
        else
            $url .= '';

        if (isset($_SERVER['REQUEST_URI']))
            $url .= $_SERVER['REQUEST_URI'];
        elseif (isset($_SERVER['PHP_SELF']))
            $url .= $_SERVER['PHP_SELF'];
        elseif (isset($_SERVER['REDIRECT_URL']))
            $url .= $_SERVER['REDIRECT_URL'];
        else
            $url .= '';

        $url.= 'system/authMerchant.php';

        return $url;
    }

    /**
     * sets the public_key from system/merchant.csv
     * @var $public_key string
     * @return string Public Key
     */
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

    /**
     * sets the private_key from system/merchant.csv
     * @var $private_key string
     * @return string Private Key
     */
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

    /**
     * return if merchant can do live transactions or not
     * @var $is_live bool
     * @return bool If merchant has livemode
     */
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

    /**
     * returns string checked for checkbox-fields if the permission is in hte permission-scope
     *
     * @return string checked for checkboxes
     */
    function isPermissionSet($scopes, $permission)
    {
        if(strpos($scopes, $permission) !== false) {
            return "checked";
        }
        return;
    }