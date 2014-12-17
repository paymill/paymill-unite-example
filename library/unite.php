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
        $paymill_api_root = 'https://api.paymill.com/v2.1/';
    }

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
