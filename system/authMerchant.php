<pre>
<?php
    include '../library/unite.php';

    //var_dump($_GET);

    if($_GET['code']) {
        $success = true;
        $code = $_GET['code'];
        $error = false;
        $msg = 'Authorization was successful!';
    }
    else {
        $success = false;
        $code = false;
        $error = $_GET['error'];
        $msg = str_replace('+', ' ', $_GET['error_description']);
    }

    if ($code) {
        $url = $paymill_root . '/token';
        $fields_string = '';
        $fields = array(
            'grant_type' => $grant_type,
            'scope' => 'transactions_w clients_w payments_w refunds_w webhooks_w',
            'code' => $code,
            'client_id' => $client_id,
            'client_secret' => $private_access_key
        );

        foreach($fields as $key=>$value) {
            $fields_string .= $key.'='.$value.'&';
        }
        rtrim($fields_string, '&');

        $ch = curl_init();
        //var_dump($fields_string);
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch,CURLOPT_POST, count($fields));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);
        #var_dump($result);
        #die();
        $result = json_decode($result, true);

        //var_dump($info);
        curl_close($ch);

        $list = array(
            //array( 'token', 'refresh_token', 'public_key', 'merchant_id' ),
            array($result['access_token'], $result['refresh_token'], $result['public_key'], $result['merchant_id'])
        );

        $csv = 'merchant.csv';
        $fp = fopen($csv, 'w');
        foreach ($list as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);

        header('Location: ' . $merchant_root . '/final.php');
    }
    else {
        echo $msg;
    }
?>
</pre>