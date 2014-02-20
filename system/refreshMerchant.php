<?php
    session_start();

    include '../library/unite.php';

    $success = true;
    $code = $_SESSION['accessMerchant']['code'];
    $msg = "";

      if ($code) {
          $url = $paymill_root . '/token';
          $fields_string = '';


          //there is a bug for updating scope
          //scope in Session
         /* $scope = $_POST['scope'];
          $scopes = null;
            foreach ($scope as $scopeVal) {
                if( $scopes === null) {
                    $scopes = $scopeVal;
                } else {
                    $scopes = $scopes.' '.$scopeVal;
                }
            }
          $scope = $scopes;
          $_SESSION['userConfig']['scope'] = $scope;*/


          $fields = array(
              'grant_type'    =>  "refresh_token",
              //über Post holen
              'scope'         =>  $_SESSION['userConfig']['scope'],
              'refresh_token' =>  $_SESSION['accessMerchant']['refreshToken'],
              'client_id'     =>  $_SESSION['userConfig']['clientId'] ,
              'client_secret' =>  $_SESSION['userConfig']['clientSecret']
          );


          foreach($fields as $key=>$value) {
              $fields_string .= $key.'='.$value.'&';
          }
          rtrim($fields_string, '&');

          $ch = curl_init();

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

              $scope      = explode(' ', $result['scope']);
              $accessKeys = $result['access_keys'];


              //SAVE in merchant.csv
              if(isset($accessKeys['live'])) {
                  $key = $accessKeys['live'];
                  $canDoLiveTransactions = true;
              } else {
                  $accessKeys['live'] = array(
                                              'private_key' => null,
                                              'public_key' => null
                                               );
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

              $liveTRX = $canDoLiveTransactions ? '1' : '0';

              //SAVE in SESSION
              $_SESSION['accessMerchant']['canDoLiveTransactions']  = $liveTRX;
              $_SESSION['accessMerchant']['privateTestKey'] = $accessKeys['test']['private_key'];
              $_SESSION['accessMerchant']['publicTestKey'] = $accessKeys['test']['public_key'];
              $_SESSION['accessMerchant']['privateLiveKey'] = $accessKeys['live']['private_key'];
              $_SESSION['accessMerchant']['publicLiveKey'] = $accessKeys['live']['public_key'];
              $_SESSION['accessMerchant']['refreshToken'] = $result['refresh_token'];
              $_SESSION['accessMerchant']['merchantId'] =  $result['merchant_id'];
              $_SESSION['accessMerchant']['tokenType'] = $result['token_type'];
              $_SESSION['accessMerchant']['expires_in'] = $result['expires_in'];
              $_SESSION['accessMerchant']['payment_methods'] = $result['payment_methods'];

          }
      }

?>
<!DOCTYPE html>

<html lang="en-gb">

<head>
        <script type="text/javascript" src="../assets/js/jquery/jquery-1.10.2.min.js"></script>
        <script type="text/javascript" src="../assets/js/bootstrap/bootstrap.min.js"></script>
        <script type="text/javascript" src="../assets/js/main.js"></script>

    <link rel="stylesheet" href="../assets/css/screen.css">
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
                    <a href="../index.php" ><i class="fa fa-code fa-fw"></i>1. Configuration</a>
                </li>
                 <li  >
                    <a href="#">
                        <i class="fa fa-code fa-fw "></i>2. Connect
                    </a>
                    <ol >
                        <li ><a href="../connect.php">Connect a Merchant</a></li>
                        <li><a href="../payment.php">Fee payment</a></li>
                    </ol>
                </li>
                 <li>
                    <a href="../shopping-cart.php"><i class="fa fa-code fa-fw"></i>3. Shopping Cart</a>
                </li>
                 <li class="active">
                    <a href="../refresh-merchant.php"><i class="fa fa-code fa-fw"></i>4. Refresh token</a>
                </li>
            </ul>
        </nav>
    </section>
    <div id="Content">
      <div class="container">

          <?php if($success): ?>

          <h2>Refresh was successful!</h2>

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
              <p>Test public key:  <code><?php echo $accessKeys['test']['public_key']; ?></code></p>
              <p>Test private key: <code><?php echo $accessKeys['test']['private_key']; ?></code></p>
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
                  <p>Live public key:  <code><?php echo $accessKeys['live']['public_key']; ?></code></p>
                  <p>Live private key: <code><?php echo $accessKeys['live']['private_key']; ?></code></p>
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
            <a href="../refresh-merchant.php" class="btn btn-success btn-sm pull-left">
              <span class="glyphicon glyphicon-chevron-left"></span>
              Back to refresh page
            </a>
            <a href="../shopping-cart.php" class="btn btn-success btn-sm pull-right">
              Do a test transaction
              <span class="glyphicon glyphicon-chevron-right"></span>
            </a>
          </p>

          <div class="Footer">
              <p>&copy; PAYMILL GmbH</p>
          </div>
      </div>
    </div>
</body>

</html>