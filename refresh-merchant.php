<?php
    session_start();

    require 'library/unite.php';

    if(isset($_SESSION['accessMerchant']) ) {
            $client_id = $_SESSION['userConfig']['clientId'];
            $client_secret = $_SESSION['userConfig']['clientSecret'];
            $scopes = $_SESSION['userConfig']['scope'];
            $refresh_token = $_SESSION['accessMerchant']['refreshToken'];
            $hasMerchant = true;
      } else {
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
            <p> To refesh a access token you first have to <a href="connect.php">connect to a Merchant</a>.</p>
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
                        <label >Your grant_type</label>
                            <input readonly class="form-control"  type="text" name="grantType"  placeholder="<?php echo $grant_type; ?>" value="refresh_token" />
                    </div>
                    <div class="form-group">
                        <label >Your refresh_token</label>
                            <input class="form-control"  type="text" name="refreshToken"  placeholder="<?php echo $refresh_token; ?>" value="<?php echo $refresh_token; ?>" />
                    </div>
<!--                     <div class="form-group">
                        <label >Your scope</label>
                            <div class="checkbox">
                                <div class="col-md-4">
                                    <label><input type="checkbox" name="scope[]" value="transactions_w" <?php if(strpos($scopes, 'transactions_w') !== false) { echo " checked"; } ?> >transactions_w</label><br>
                                    <label><input type="checkbox" name="scope[]" value="clients_w" <?php if(strpos($scopes, 'clients_w') !== false) { echo " checked"; } ?>>clients_w</label><br>
                                    <label><input type="checkbox" name="scope[]" value="payments_w" <?php if(strpos($scopes, 'payments_w') !== false) { echo " checked"; } ?>>payments_w</label><br>
                                    <label><input type="checkbox" name="scope[]" value="refunds_w" <?php if(strpos($scopes, 'refunds_w') !== false) { echo " checked"; } ?>>refunds_w</label><br>
                                    <label><input type="checkbox" name="scope[]" value="webhooks_w" <?php if(strpos($scopes, 'webhooks_w') !== false) { echo " checked"; } ?>>webhooks_w</label><br>
                                    <label><input type="checkbox" name="scope[]" value="frauds_w" <?php if(strpos($scopes, 'frauds_w') !== false) { echo " checked"; } ?>>frauds_w</label><br>
                                    <label><input type="checkbox" name="scope[]" value="subscriptions_w" <?php if(strpos($scopes, 'subscriptions_w') !== false) { echo " checked"; } ?>>subscriptions_w</label><br>
                                    <label><input type="checkbox" name="scope[]" value="offers_w" <?php if(strpos($scopes, 'offers_w') !== false) { echo " checked"; } ?>>offers_w</label><br>
                                    <label><input type="checkbox" name="scope[]" value="preauthorizations_w" <?php if(strpos($scopes, 'preauthorizations_w') !== false) { echo " checked"; } ?>>preauthorizations_w</label><br>

                                </div>
                                <div class="col-md-4">
                                    <label><input type="checkbox" name="scope[]" value="transactions_r" <?php if(strpos($scopes, 'transactions_r') !== false) { echo " checked"; } ?> >transactions_r</label><br>
                                    <label><input type="checkbox" name="scope[]" value="clients_r" <?php if(strpos($scopes, 'clients_r') !== false) { echo " checked"; } ?>>clients_r</label><br>
                                    <label><input type="checkbox" name="scope[]" value="payments_r" <?php if(strpos($scopes, 'payments_r') !== false) { echo " checked"; } ?>>payments_r</label><br>
                                    <label><input type="checkbox" name="scope[]" value="refunds_r" <?php if(strpos($scopes, 'refunds_r') !== false) { echo " checked"; } ?>>refunds_r</label><br>
                                    <label><input type="checkbox" name="scope[]" value="webhooks_r" <?php if(strpos($scopes, 'webhooks_r') !== false) { echo " checked"; } ?>>webhooks_r</label><br>
                                    <label><input type="checkbox" name="scope[]" value="frauds_r" <?php if(strpos($scopes, 'frauds_r') !== false) { echo " checked"; } ?>>frauds_r</label><br>
                                    <label><input type="checkbox" name="scope[]" value="subscriptions_r" <?php if(strpos($scopes, 'subscriptions_r') !== false) { echo " checked"; } ?>>subscriptions_r</label><br>
                                    <label><input type="checkbox" name="scope[]" value="offers_r" <?php if(strpos($scopes, 'offers_r') !== false) { echo " checked"; } ?>>offers_r</label><br>
                                    <label><input type="checkbox" name="scope[]" value="preauthorizations_r" <?php if(strpos($scopes, 'preauthorizations_r') !== false) { echo " checked"; } ?>>preauthorizations_r</label><br>

                                </div>
                                <div class="col-md-4">
                                    <label><input type="checkbox" name="scope[]" value="transactions_rw" <?php if(strpos($scopes, 'transactions_rw') !== false) { echo " checked"; } ?> >transactions_rw</label><br>
                                    <label><input type="checkbox" name="scope[]" value="clients_rw" <?php if(strpos($scopes, 'clients_rw') !== false) { echo " checked"; } ?>>clients_rw</label><br>
                                    <label><input type="checkbox" name="scope[]" value="payments_rw" <?php if(strpos($scopes, 'payments_rw') !== false) { echo " checked"; } ?>>payments_rw</label><br>
                                    <label><input type="checkbox" name="scope[]" value="refunds_rw" <?php if(strpos($scopes, 'refunds_rw') !== false) { echo " checked"; } ?>>refunds_rw</label><br>
                                    <label><input type="checkbox" name="scope[]" value="webhooks_rw" <?php if(strpos($scopes, 'webhooks_rw') !== false) { echo " checked"; } ?>>webhooks_rw</label><br>
                                    <label><input type="checkbox" name="scope[]" value="frauds_r" <?php if(strpos($scopes, 'frauds_rw') !== false) { echo " checked"; } ?>>frauds_rw</label><br>
                                    <label><input type="checkbox" name="scope[]" value="subscriptions_rw" <?php if(strpos($scopes, 'subscriptions_rw') !== false) { echo " checked"; } ?>>subscriptions_rw</label><br>
                                    <label><input type="checkbox" name="scope[]" value="offers_rw" <?php if(strpos($scopes, 'offers_rw') !== false) { echo " checked"; } ?>>offers_rw</label><br>
                                    <label><input type="checkbox" name="scope[]" value="preauthorizations_rw" <?php if(strpos($scopes, 'preauthorizations_rw') !== false) { echo " checked"; } ?>>preauthorizations_rw</label><br><br>


                                </div>
                            </div>
                    </div> -->
                   <div class="form-group" >
                        <button type="submit" class="btn btn-sm btn-primary submit-button" <?php echo $hasMerchant?"":"disabled"; ?> >Generate new access token</button>
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

