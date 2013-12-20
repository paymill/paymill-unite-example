<?php
    include '../library/unite.php';

    $success = false;
    $code    = false;

    if($_GET['code']) {
        $success = true;
        $code = $_GET['code'];
        $error = false;
        $msg = 'Authorization was successful!';
    }
    else {
        $error = $_GET['error'];
        $msg = str_replace('+', ' ', $_GET['error_description']);
    }

    if ($code) {
        $url = $paymill_root . '/token';
        $fields_string = '';
        $fields = array(
            'grant_type'    => $grant_type,
            'scope'         => 'transactions_w clients_w payments_w refunds_w webhooks_w',
            'code'          => $code,
            'client_id'     => $client_id,
            'client_secret' => $client_secret
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

        if(!$result || isset($result['error'])) {
            $success = false;
            $error   = $result['error'];
            $msg     = $result['error_description'];
        } else {

            //var_dump($info);
            curl_close($ch);

            // Deprecated since 2013-12-17:

            //$list = array(
            //    array($result['access_token'], $result['refresh_token'], $result['public_key'], $result['merchant_id'])
            //);

            // Get connect information from authorization code result

            $scope      = explode(' ', $result['scope']);
            $accessKeys = $result['access_keys'];

            if(isset($accessKeys['live'])) {
                $key = $accessKeys['live'];
                $canDoLiveTransactions = true;
            } else {
                $key = $accessKeys['test'];
                $canDoLiveTransactions = false;
            }
            $list = array(
                array(
                    $key['private_key'],
                    $result['refresh_token'],
                    $key['public_key'],
                    $result['merchant_id'],
                    $canDoLiveTransactions ? '1' : '0'
                )
            );

            $csv = 'merchant.csv';
            $fp = fopen($csv, 'w');
            foreach ($list as $row) {
                fputcsv($fp, $row);
            }
            fclose($fp);
        }
    }
?>
<!DOCTYPE html>

<html lang="en-gb">

<head>
    <script type="text/javascript" src="../js/jquery-ui/js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="../js/jquery-ui/js/jquery-ui-1.10.1.custom.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">
    <!-- link rel="stylesheet" href="../css/screen.css" type="text/css" / -->
</head>

<body>

    <div class="container">
    <div class="header">
        <h1><img src="../img/icon.png" style="height: 27px; margin-bottom: 6px;" /> PAYMILL Unite Demo</h1>
    </div>

        <?php if($success): ?>

        <h2>Authorization was successful!</h2>

        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Merchant</h3>
          </div>
          <ul class="list-group">
            <li class="list-group-item">ID: <code><?php echo $result['merchant_id']; ?></code></li>
            <li class="list-group-item">
                Status: <code><?php echo isset($accessKeys['live']) ?
                "Can do live" : "Can only do test"; ?> transactions</code></li>
          </ul>
        </div>

        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Merchant allows test requests</h3>
          </div>
          <div class="panel-body">
            Test public key:  <code><?php echo $accessKeys['test']['public_key']; ?></code><br>
            Test private key: <code><?php echo $accessKeys['test']['private_key']; ?></code>
          </div>
          <ul class="list-group">
            <li class="list-group-item">Token type: <code><?php echo $result['token_type']; ?></code></li>
            <li class="list-group-item">Expires in: <code><?php echo $result['expires_in'] ? $result['expires_in'] : 'never'; ?></code></li>
          </ul>
        </div>

        <?php if(isset($accessKeys['live'])): ?>
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Merchant allows live requests</h3>
              </div>
              <div class="panel-body">
                Live public key:  <code><?php echo $accessKeys['live']['public_key']; ?></code><br>
                Live private key: <code><?php echo $accessKeys['live']['private_key']; ?></code>
              </div>
              <ul class="list-group">
                <li class="list-group-item">Token type: <code><?php echo $result['token_type']; ?></code></li>
                <li class="list-group-item">Expires in: <code><?php echo $result['expires_in'] ? $result['expires_in'] : 'never'; ?></code></li>
              </ul>
            </div>
        <?php else: ?>
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Merchant does not allow live requests</h3>
              </div>
              <div class="panel-body">
                - No keys available -
              </div>
            </div>
        <?php endif; ?>


        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Available payment methods</h3>
          </div>

            <?php if($result['payment_methods'] == 'ALL'): ?>
            <div class="panel-body">
            ALL payment methods are allowed in test mode.
            </div>
            <?php else: ?>
            <ul class="list-group">
                <?php foreach($result['payment_methods'] as $method): ?>
                <li class="list-group-item">
                    Type:     <code><?php echo $method['type']; ?></code><br>
                    Currency: <code><?php echo $method['currency']; ?></code><br>
                    Acquirer: <code><?php echo $method['acquirer']; ?></code>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </div>

        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Scope</h3>
          </div>
          <div class="panel-body">
            <?php foreach($scope as $perm): ?>
            <span class="label label-success"><?php echo $perm; ?></span>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Refresh token</h3>
          </div>
          <div class="panel-body">
            <code><?php echo $result['refresh_token']; ?></code>
          </div>
        </div>

        <?php else: ?>

        <h2>Authorization failed!</h2>

        <div class="panel panel-danger">
          <div class="panel-heading">
            <h3 class="panel-title">Response</h3>
          </div>
          <div class="panel-body">
            <?php echo $msg; ?>
          </div>
        </div>


        <?php endif; ?>

        <div class="panel-group" id="accordion">
            <div class="panel-info">
              <div class="panel-heading">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                    <h3 class="panel-title">
                        <span class="glyphicon glyphicon-chevron-down"></span>
                        Plain authorization reponse
                    </h3>
                </a>
              </div>
              <div id="collapseOne" class="panel-collapse collapse">
                  <ul class="list-group">
                    <li class="list-group-item">URL: <code><?php echo $url; ?></code></li>
                    <li class="list-group-item">POST parameters: <pre><?php print_r($fields) ?></pre></li>
                    <li class="list-group-item">Repsonse: <pre class="pre-scrollable"><?php print_r($result); ?></pre></li>
                  </ul>
              </div>
            </div>
        </div>


        <p><br>
          <a href="../connect.php" class="btn btn-primary btn-sm">
            <span class="glyphicon glyphicon-chevron-left"></span>
            Back to connect page
          </a>
          <a href="../shopping-cart.php" class="btn btn-primary btn-sm">
            Do a test transaction
            <span class="glyphicon glyphicon-chevron-right"></span>
          </a>
        </p>

        <p>&copy; PAYMILL GmbH</p>
    </div>
</body>

</html>