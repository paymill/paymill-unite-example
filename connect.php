<?php
    session_start();

    require 'library/unite.php';



    if(!isset($_SESSION['userConfig'])) {

        $queryString = 'client_id=' . $client_id . '&scope=' . $scope
                . '&response_type=code&redirect_uri=' . $redirect_uri;
        $queryStringVal =  'client_id=&scope=&response_type=code&redirect_uri=';

        if(isset($_POST['scope']) && isset( $_POST['redirectUri']) && isset($_POST['clientId'])) {
            $_SESSION['userConfig'] = array(
                                                'bridgeUrl' => $_POST['bridgeUrl'],
                                                'apiRoot' => $_POST['apiRoot'],
                                                'paymillRoot' => $_POST['paymillRoot'],
                                                'clientId' => $_POST['clientId'],
                                                'clientSecret' => $_POST['clientSecret'],
                                                'grantType' => $_POST['grantType'],
                                                'scope' => $_POST['scope'],
                                                'redirectUri' => $_POST['redirectUri']
                                            );

            $_SESSION['queryString'] = $queryString;

            $scope =  $_SESSION['userConfig']['scope'];
            $scopes = null;
            foreach ($scope as $scopeVal) {
                if( $scopes === null) {
                    $scopes = $scopeVal;
                } else {
                    $scopes = $scopes.'+'.$scopeVal;
                }
            }
            $scope = $scopes;

            $redirect_uri = $_SESSION['userConfig']['redirectUri'];
            $redirect_uri = urlencode($redirect_uri);

            $client_id =  $_SESSION['userConfig']['clientId'];

            $queryStringVal = 'client_id=' . $client_id . '&scope=' . $scope
            . '&response_type=code&redirect_uri=' . $redirect_uri;

             $_SESSION['queryStringVal'] = $queryStringVal;
        }
    }

    $queryStringVal = $_SESSION['queryStringVal'];
    $queryString = $_SESSION['queryString'];
    $client_id = $_SESSION['userConfig']['clientId'];

    $checksum = null;
        if(isset($_POST['hash_token']))
        {
            if($hash = $_POST['hash_token']) {
                $checksum = hash_hmac('sha256', $queryStringVal, $hash);
            }

            if($checksum) {
                $queryStringVal .= '&checksum=' . $checksum;
                $_SESSION['queryStringVal'] = $queryStringVal;
                $_SESSION['checksum'] = $checksum;
            }
        }

?>
<!DOCTYPE html>

<html lang="en-gb">

<head>
    <script type="text/javascript" src="assets/js/jquery-ui/js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="assets/js/jquery-ui/js/jquery-ui-1.10.1.custom.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="assets/css/screen.css">
</head>

<body>
    <div class="container">

        <div class="row">
            <div class="col-lg-7">
                <div class="header">
                    <h1><img src="assets/img/icon.png" style="height: 27px; margin-bottom: 6px;" /> PAYMILL Unite Demo</h1>
                </div>
            </div>
            <div class="col-lg-5">
                <ol class="breadcrumb">
                  <li ><a href="index.php">1. Config</a></li>
                  <li class="active">2. Connect</li>
                  <li><a href="shopping-cart.php">3. Shopping Cart</a></li>
                  <li><a href="refresh-token.php">4. Refresh Token</a></li>
                </ol>
            </div>
        </div>

        <div class="panel panel-danger">
          <div class="panel-heading">
            <h3 class="panel-title">Short description</h3>
          </div>
          <div class="panel-body">
                <p>The link behind the button is based on the settings in <code>library/unite.php</code> and looks like this:</p>
                <pre><?php echo $paymill_root . '/?' . $queryString ; ?></pre>
                <p>It sends the merchant to the PAYMILL Connect page
                    where the merchant can login or register to authorize your connect request.</p>
                <p><a href="https://paymill.com/en-gb/unite-documentation/">Read more about how to build the connect URL.</a></p>
          </div>
        </div>

        <div class="panel panel-danger">
          <div class="panel-heading">
            <h3 class="panel-title">PAYMILL Unite connect button</h3>
          </div>
          <div class="panel-body">
                <p>Include a button like this into your connect page.</p>
                <p>
                <a href="<?php echo $paymill_root . '/?' . $queryStringVal ; ?>" class="btn btn-primary">
                    Connect your PAYMILL account with this app.
                </a>
                </p>
                <p>The generated link based on your settings in <a href="connect.php">Step1 - Config</a>:</p>
                <pre><?php echo $queryStringVal; ?></pre>
                <p><br>*Click the button to test the behavior with your settings*</p>
          </div>
          <ul class="list-group">
            <li class="list-group-item">
                Connecting App ID in this example:
                <code><?php echo $client_id; ?></code>
                (Can be changed in unite.php)
            </li>
          </ul>
        </div>

        <div class="panel panel-danger">
          <div class="panel-heading">
            <h3 class="panel-title">Generate checksum</h3>
          </div>
          <div class="panel-body">
                <p>
                    To secure the parameters in the connect URL you have the possibility to optionally add a checksum parameter.
                </p>

                <p>
                <strong>Note:</strong> To generate a checksum you first need to activate the checksum validtaion for your app.<br>
                To do so, go to the <em><a href="https://app.paymill.com">PAYMILL Cockpit</a> -> Settings -> App -> App details -> Checksum validation -> check activate -> save</em>.<br>
                Now you should see the <em>hash token</em> for your app to generate the checksum out of the URL query string.
                </p>

                <?php
                    if(!isset($_SESSION['checksum'])) {
                ?>
                    <p>
                        Use this form to generate the checksum for the current connect link (it will be automatically added to the example link above).
                    </p>
                    <p>
                    <form class="form-horizontal" action="" method="post" role="form">
                        Please enter your App hash token:<br>
                        <div class="form-group">
                            <div class="col-sm-4">
                                <input class="form-control" type="text" name="hash_token" placeholder="<enter-your-hash-token>" />

                            </div>
                            <div class="col-sm-3">
                                <input class="btn btn-default" type="submit" value="Generate" />
                            </div>
                        </div>
                    </form>
                    </p>
                <?php } else { ?>
                    <p>
                    <strong>Checksum:</strong>
                    <code><?php echo $_SESSION['checksum']; ?></code>
                    </p>
                <?php }; ?>

                <p><a href="https://paymill.com/en-gb/unite-documentation/">Read more about checksum validation.</a></p>
          </div>
        </div>

        <div>
          <a href="." class="btn btn-success btn-sm pull-left">
            <span class="glyphicon glyphicon-chevron-left "></span>
            Back to intro
         </a>
         <a href="shopping-cart.php" class="btn btn-success btn-sm pull-right">
            Do a test transaction
            <span class="glyphicon glyphicon-chevron-right "></span>
         </a>
        </div>

        <div class="Footer">
            <p>&copy; PAYMILL GmbH</p>
        </div>

        </div>
</body>

</html>