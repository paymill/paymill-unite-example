<?php
    session_start();
    include 'library/unite.php';

    // if merchant has connected -> set parameter
    if(isset($_SESSION['accessMerchant']) ) {
            $client_id = $_SESSION['userConfig']['clientId'];
            $client_secret = $_SESSION['userConfig']['clientSecret'];
            $scopes = $_SESSION['userConfig']['scope'];
            $refresh_token = $_SESSION['accessMerchant']['refreshToken'];
            $hasMerchant = true;
      } else {
    // or set placeholder
        $scopes = "";
        $refresh_token = "[your-generated-refresh-token]";
        $hasMerchant = false;
      }
?>


<!DOCTYPE html>

<html lang="en-gb">

<head>
    <script type="text/javascript" src="assets/js/jquery/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="assets/js/bootstrap/bootstrap.min.js"></script>
    <script type="text/javascript" src="assets/js/main.js"></script>
    <link rel='shortcut icon' href="favicon.ico" type="image/ico" />

    <link rel="stylesheet" href="assets/css/screen.css">

    <script type="text/javascript" src="<?php echo $bridge_url; ?>"></script>
</head>

<body>

    <section id="Sidebar">
        <div id="TopBar">
          <a href="#"  class="burgermenu PushSidebar"><i class="fa fa-bars"></i></a>
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
                        <li><a href="connect.php">Connect a Merchant</a></li>
                        <li ><a href="payment.php">Fee payment</a></li>
                    </ol>
                </li>
                 <li>
                    <a href="shopping-cart.php"><i class="fa fa-code fa-fw"></i>3. Shopping Cart</a>
                </li>
                 <li class="active">
                    <a href="refresh-merchant.php"><i class="fa fa-code fa-fw"></i>4. Refresh token</a>
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
            <p> To refesh the access token you first have to <a href="connect.php">connect a merchant</a>.</p>
            <form id="refreshToken" role="form" class="col-xs-6" method="post" action="system/refreshMerchant.php">
                    <div class="payment-errors"> </div>
                    <div class="form-group">
                        <label >Your client_id</label>
                            <input class="form-control"  type="text" name="clientId"  placeholder="<?php echo $client_id; ?>" value="<?php echo $client_id; ?>" />
                    </div>
                    <div class="form-group">
                        <label >Your client_secret</label>
                            <input class="form-control"  type="text" name="clientSecret"  placeholder="<?php echo $client_secret; ?>" value="<?php echo $client_secret; ?>" />
                    </div>
                    <div class="form-group">
                        <label >Your refresh_token</label>
                            <input class="form-control"  type="text" name="refreshToken"  placeholder="<?php echo $refresh_token; ?>" value="<?php echo $refresh_token; ?>" />
                    </div>
                   <div class="form-group" >
                        <button type="submit" class="btn btn-sm btn-primary submit-button" <?php echo $hasMerchant?"":"disabled"; ?> >Refresh access token</button>
                    </div>
            </form>
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

