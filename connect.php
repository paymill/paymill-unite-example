<?php
    session_start();

    require 'library/unite.php';

         if(!isset($_SESSION['queryString'])){
            $queryString = 'client_id=' . $client_id . '&scope=' . $scope
                . '&response_type=code&redirect_uri=' . $redirect_uri;
            $_SESSION['queryString'] = $queryString;
            $_SESSION['queryStringVal'] = $queryString;
            $queryStringVal = $queryString;
            } else {
                $queryString  = $_SESSION['queryString'];
                $queryStringVal = $_SESSION['queryStringVal'];

            }

        if(isset($_POST['scope']) && isset( $_POST['redirectUri']) && isset($_POST['clientId'])) {
            $scope = $_POST['scope'];
            $scopes = null;
            foreach ($scope as $scopeVal) {
                if( $scopes === null) {
                    $scopes = $scopeVal;
                } else {
                    $scopes = $scopes.' '.$scopeVal;
                }
            }
            $scope = $scopes;

            $_SESSION['userConfig'] = array(
                                                'bridgeUrl' => $_POST['bridgeUrl'],
                                                'apiRoot' => $_POST['apiRoot'],
                                                'paymillRoot' => $_POST['paymillRoot'],
                                                'clientId' => $_POST['clientId'],
                                                'clientSecret' => $_POST['clientSecret'],
                                                'grantType' => $_POST['grantType'],
                                                'scope' =>  $scope,
                                                'redirectUri' => $_POST['redirectUri']
                                            );
            $_SESSION['userConfig']['checksum'] = null;

            }

            if(isset($_SESSION['userConfig'])) {

                $redirect_uri = $_SESSION['userConfig']['redirectUri'];
                $redirect_uri = urlencode($redirect_uri);

                $client_id =  $_SESSION['userConfig']['clientId'];

                $scope = str_replace(' ', '+', $_SESSION['userConfig']['scope']);

                $queryStringVal = 'client_id=' . $client_id . '&scope=' . $scope
                . '&response_type=code&redirect_uri=' . $redirect_uri;

                $_SESSION['queryStringNoCheck'] = $queryStringVal;
                if(isset($_SESSION['userConfig']['checksum']))
                {
                    $schecksum =  $_SESSION['userConfig']['checksum'];
                    $queryStringVal = $_SESSION['queryStringVal'] . '&checksum=' . $schecksum;
                }
                $_SESSION['queryStringVal'] = $queryStringVal;
            } else {
                $schecksum = "";
                $queryStringVal = $queryString;
            }


    //create checksum
    $checksum = null;
    if(isset($_POST['hash_token']))
    {
            $checksum = hash_hmac('sha256', $_SESSION['queryStringNoCheck'], $_POST['hash_token']);
            $_SESSION['userConfig']['checksum'] = $checksum;
            $_SESSION['userConfig']['hashToken'] = $_POST['hash_token'];
            $queryStringVal = $_SESSION['queryStringNoCheck'] . '&checksum=' . $_SESSION['userConfig']['checksum'];
            $_SESSION['queryStringVal'] = $queryStringVal;
            $_SESSION['userConfig']['checksum'] = $checksum;
    }


?>
<!DOCTYPE html>

<html lang="en-gb">

<head>
    <script type="text/javascript" src="assets/js/jquery/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="assets/js/bootstrap/bootstrap.min.js"></script>
    <script type="text/javascript" src="assets/js/main.js"></script>

    <link rel="stylesheet" href="assets/css/screen.css">

    <script type="text/javascript" src="<?php echo $bridge_url; ?>"></script>

</head>

<body>

    <section id="Sidebar">
        <div id="TopBar">
          <a href="#" class="burgermenu PushSidebar"><i class="fa fa-bars"></i></a>
          <h1 id="Branding">PAYMILL Unite Demo</h1>
        </div>
        <nav>
            <ul>
                <li >
                    <a href="index.php" ><i class="fa fa-code fa-fw"></i>1. Configuration</a>
                </li>
                 <li  class="active">
                    <a href="#">
                        <i class="fa fa-code fa-fw "></i>2. Connect
                    </a>
                    <ol >
                        <li class="active"><a href="">Connect to a Merchant</a></li>
                        <li><a href="payment.php">Payment</a></li>
                    </ol>
                </li>
                 <li>
                    <a href="shopping-cart.php"><i class="fa fa-code fa-fw"></i>3. Shopping Cart</a>
                </li>
                 <li>
                    <a href="refresh-merchant.php"><i class="fa fa-code fa-fw"></i>4. Refresh Merchant</a>
                </li>
            </ul>
        </nav>
    </section>

    <div id="Content">
    <div class="container">
<!--         <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Short description</h3>
          </div>
          <div class="panel-body">
                <p>The link behind the button is based on the settings in <code>library/unite.php</code> and looks like this:</p>
                <pre></pre>
               
          </div>
        </div> -->

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
                <p>The generated link is based on your settings in <a href="connect.php">Step1 - Config</a>:</p>
                <pre><?php echo $queryStringVal; ?></pre>
                <p><br>*Click the button to test the behavior with your settings*</p>
                 <p>It sends the merchant to the PAYMILL Connect page
                    where the merchant can login or register to authorize your connect request.</p>
                <p><a href="https://paymill.com/en-gb/unite-documentation/">Read more about how to build the connect URL.</a></p>
                <p>If you want to create a transaction with a fee, you have to add a payment first. Go to step <a href="payment.php">Payment</a>.</p>
          </div>
          <ul class="list-group">
            <li class="list-group-item">
                Connecting Client ID (App ID) in this example:
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
                <p>
                    <strong>Your current checksum:</strong>
                    <code><?php echo ($checksum? $checksum : "[your-checksum-generated-below]"); ?></code>
                </p>

                <p>
                    Use this form to generate the (new) checksum for the current connect link (it will be automatically added to the example link above).
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
                <p><a href="https://paymill.com/en-gb/unite-documentation/">Read more about checksum validation.</a></p>
          </div>
        </div>


         <div class="panel panel-default hidden api-response">
              <div class="panel-heading">
                <h3 class="panel-title">API Response</h3>
              </div>
              <div class="panel-body">
              </div>
              <div class="panel-footer"><a href="#" onclick="$('.api-response').addClass('hidden'); return false;">close</a></div>
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

    </div>
</body>

</html>