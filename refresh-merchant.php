<?php
    session_start();

    require 'library/unite.php';

    if(isset($_SESSION['accessMerchant']) && isset($_SESSION['userConfig']['hashToken'])) {
        $hasMerchant = 1 ;

        $redirect_uri = "http://localhost:81/github_repos/paymill-unite-example/system/refreshMerchant.php";
        $redirect_uri = urlencode($redirect_uri);
        $scope = str_replace(' ', '+', $_SESSION['userConfig']['scope']);
        $newQueryStringVal = 'client_id=' . $_SESSION['userConfig']['clientId'] . '&scope=' . $scope . '&response_type=code&redirect_uri=' . $redirect_uri;
        $checksum = hash_hmac('sha256', $newQueryStringVal,  $_SESSION['userConfig']['hashToken']);
        $queryStringVal = $newQueryStringVal . '&checksum=' . $checksum;
    } else {
        $hasMerchant = 0 ;
    }

?>


<!DOCTYPE html>

<html lang="en-gb">

<head>
    <script type="text/javascript" src="assets/js/jquery/jquery-1.10.1.min.js"></script>
    <script type="text/javascript" src="assets/js/jquery/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="assets/js/bootstrap/bootstrap.min.js"></script>

    <link rel="stylesheet" href="assets/css/screen.css">

    <script type="text/javascript" src="<?php echo $bridge_url; ?>"></script>
</head>

<body>

    <section id="Sidebar">
        <div id="TopBar">
          <a href="#" id="PushSidebar" class="burgermenu"><i class="fa fa-bars"></i></a>
          <h1 id="Branding">PAYMILL Unite Demo</h1>
        </div>
        <nav>
            <ul>
                <li >
                    <a href="index.php" ><i class="fa fa-code fa-fw"></i>1. Configuration</a>
                </li>
                 <li >
                    <a href="#">
                        <i class="fa fa-code fa-fw "></i>2. Connect
                    </a>
                    <ol >
                        <li><a href="connect.php">Connect to a Merchant</a></li>
                        <li ><a href="payment.php">Payment</a></li>
                    </ol>
                </li>
                 <li>
                    <a href="shopping-cart.php"><i class="fa fa-code fa-fw"></i>3. Shopping Cart</a>
                </li>
                 <li class="active">
                    <a href="refresh-merchant.php"><i class="fa fa-code fa-fw"></i>4. Refresh Merchant</a>
                </li>
            </ul>
        </nav>
    </section>

    <div id="Content">
    <div class="container">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Refresh Merchant</h3>
          </div>
          <div class="panel-body">
            <?php
                if($hasMerchant === 1) {
            ?>
                <a href="<?php echo $paymill_root . '/?' . $queryStringVal; ?>" class="btn btn-primary">
                   Generate new access tokens
                </a>
            <?php } else { ?>
                <p> To refesh a access token you first have to <a href="connect.php">connect to a Merchant</a>.</p>
            <?php } ?>
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
          <a href="connect.php" class="btn btn-success btn-sm pull-left">
            <span class="glyphicon glyphicon-chevron-left "></span>
            Back to connect
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

