<?php
    session_start();
    include 'library/unite.php';
?>

<!DOCTYPE html>
<html lang="en-gb">
    <head>
        <link rel="stylesheet" href="assets/css/screen.css">
        <script type="text/javascript" src="assets/js/bootstrap/bootstrap.min.js"></script>
        <script type="text/javascript" src="assets/js/jquery/jquery-1.10.2.min.js"></script>
        <script type="text/javascript" src="assets/js/main.js"></script>
        <link rel='shortcut icon' href="favicon.ico" type="image/ico" />

        <script type="text/javascript">
            $(document).ready(function(){
                $('#show-example').click(function(){
                    $('form').submit();
                })
            })
        </script>
    </head>

    <body>
    <section id="Sidebar">
        <div id="TopBar">
           <a class="burgermenu PushSidebar" href="#"><i class="fa fa-bars"></i></a>
           <h1 id="Branding">PAYMILL Unite Demo</h1>
        </div>
        <nav>
            <ul>
                <li class="active">
                    <a href="#" ><i class="fa fa-code fa-fw"></i>1. Configuration</a>
                </li>
                <li>
                    <a href="#">
                        <i class="fa fa-code fa-fw "></i>2. Connect
                    </a>
                    <ol>
                        <li><a href="connect.php">Connect a Merchant</a></li>
                        <li><a href="payment.php">Fee payment</a></li>
                    </ol>
                </li>
                <li>
                    <a href="shopping-cart.php"><i class="fa fa-code fa-fw"></i>3. Shopping Cart</a>
                </li>
                 <li>
                    <a href="refresh-merchant.php"><i class="fa fa-code fa-fw"></i>4. Refresh token</a>
                </li>
            </ul>
        </nav>
    </section>

    <div id="Content">
        <form method="post" action="connect.php" class="configForm">
            <div class="container">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h3 class="panel-title">Welcome to the PAYMILL Unite demo</h3>
                  </div>
                  <div class="panel-body">
                    <p>This is a very minimalistic PAYMILL Unite demo written in PHP.<br>
                    With this demo we want to show you how to use PAYMILL Unite to connect your app with a merchant.</p>
                    <p><a href="https://paymill.com/en-gb/unite-documentation/">Please read the PAYMILL Unite documentation</a>
                        before you start.</p>
                  </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Next steps</h3>
                      </div>
                      <div class="panel-body">
                        <p>The normal PAYMILL Unite workflow would be as follwed:</p>
                        <ol>
                            <li>
                                Direct your merchant to a page were you implemented your individual PAYMILL Connect link/button.
                                The button could look like our <a id="show-example">example</a>.
                            </li>
                            <li>
                                The button will send your merchant to the <a href="https://connect.paymill.com">PAYMILL Connect</a> page,
                                were he can authorize your request.
                            </li>
                            <li>
                                After the authorization the merchant will be redirected to your above defined redirect URI,
                                were the received access keys are stored.
                            </li>
                            <li>
                                Now if everything worked well, you can do a test transaction with our shopping cart
                                <a href="shopping-cart.php">example</a>.<br>
                                <i>If you wish to execute a transaction with a fee, you have to do a
                                <a href="payment.php">Fee payment</a> before.</i>
                            </li>
                        </ol>
                    </div>
                </div>

                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <h3 class="panel-title">Current configuration</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-10">
                            <p>
                                <strong>Note:</strong> Please configure theses settings before you go on. You
                                <span class="text-danger">need at least one merchant</span> account which acts as app.
                                If you don't have one yet just sign up for a free test account at PAYMILL (It takes just 2 minutes).
                            </p>
                            </div>
                            <div class="col-xs-2">
                                <a class="btn btn-xs btn-primary" href="https://app.paymill.com/en-gb/auth/register">Sign up.</a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <p>
                                    There's a basic configuration file where you can set up your
                                    connection data for the OAuth <a href="connect.php">connect</a>:
                                </p>
                                <table class="table table-striped table-bordered" id="permissions">
                                    <thead class="row">
                                        <tr>
                                            <th class="col-xs-2">Setting</th>
                                            <th class="col-xs-4">Description</th>
                                            <th class="col-xs-4">Enter your Values</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><a href="https://paymill.com/en-gb/documentation-3/reference/paymill-bridge">Bridge</a></td>
                                            <td>
                                                <p>JavaScript which handles the payment form validation and payment token generation.</p>
                                                <p><strong>default:</strong> <code>https://bridge.paymill.com/</code></p>
                                            </td>
                                            <td><input type="text" name="bridgeUrl"  value="<?php echo $bridge_url; ?>" /></td>
                                        </tr>
                                        <tr>
                                            <td><a href="https://paymill.com/en-gb/documentation-3/reference/api-reference">API</a> root</td>
                                            <td>
                                                <p>API root URL</p>
                                                <p><strong>default:</strong> <code>https://api.paymill.com/v2/</code></p>
                                            </td>
                                            <td><input type="text" name="apiRoot"  value="<?php echo $paymill_api_root; ?>" /></td>
                                        </tr>
                                        <tr>
                                            <td>PAYMILL Connect URL</td>
                                            <td>
                                                <p>PAYMILL Connect form where the merchant authorizes your connect request.</p>
                                                <p><strong>default:</strong><code>https://connect.paymill.com/</code></p>
                                            </td>
                                            <td><input type="text" name="paymillRoot" value="<?php echo $paymill_root; ?>" /> </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Client ID</strong></td>
                                            <td>
                                                <p>Equivalent to your App ID. Can be found in <br><em><a href="https://app.paymill.com">PAYMILL Cockpit</a> -> Settings -> App -> App details</em></p>
                                            </td>
                                            <td><input type="text" name="clientId"  placeholder="<?php echo $client_id; ?>" value="<?php echo $client_id; ?>" /> </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Client Secret</strong></td>
                                            <td>
                                                <p>Needed for authentication. Can be found in <br><em><a href="https://app.paymill.com">PAYMILL Cockpit</a> -> Settings -> App -> App details</em></p>
                                            </td>
                                            <td>
                                                <input type="text" name="clientSecret"  placeholder="<?php echo $client_secret; ?>" value="<?php echo $client_secret; ?>"/>
                                                <input type="hidden" name="grantType" value="<?php echo $grant_type; ?>" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Scope</td>
                                            <td><p>Permissions you are asking for. <br>
                                                <i>(Don't join '_w', '_r' and '_rw' permissions of one object.)</i><p></td>
                                            <td class="row">
                                                <div class="checkbox">
                                                    <div class="col-sm-12 col-md-4">
                                                        <label><input type="checkbox" name="scope[]" value="transactions_w" <?php echo isPermissionSet($scopes, "transactions_w"); ?> >transactions_w</label><br>
                                                        <label><input type="checkbox" name="scope[]" value="clients_w" <?php echo isPermissionSet($scopes, "clients_w"); ?>>clients_w</label><br>
                                                        <label><input type="checkbox" name="scope[]" value="payments_w" <?php echo isPermissionSet($scopes, "payments_w"); ?>>payments_w</label><br>
                                                        <label><input type="checkbox" name="scope[]" value="refunds_w" <?php echo isPermissionSet($scopes, "refunds_w"); ?>>refunds_w</label><br>
                                                        <label><input type="checkbox" name="scope[]" value="webhooks_w" <?php echo isPermissionSet($scopes, "webhooks_w"); ?>>webhooks_w</label><br>
                                                        <label><input type="checkbox" name="scope[]" value="frauds_w" <?php echo isPermissionSet($scopes, "frauds_w"); ?>>frauds_w</label><br>
                                                        <label><input type="checkbox" name="scope[]" value="subscriptions_w" <?php echo isPermissionSet($scopes, "subscriptions_w"); ?>>subscriptions_w</label><br>
                                                        <label><input type="checkbox" name="scope[]" value="offers_w" <?php echo isPermissionSet($scopes, "offers_w"); ?>>offers_w</label><br>
                                                        <label><input type="checkbox" name="scope[]" value="preauthorizations_w" <?php echo isPermissionSet($scopes, "preauthorizations_w"); ?>>preauthorizations_w</label><br>

                                                    </div>
                                                    <div class="col-sm-12 col-md-4">
                                                        <label><input type="checkbox" name="scope[]" value="transactions_r" <?php echo isPermissionSet($scopes, "transactions_r"); ?> >transactions_r</label><br>
                                                        <label><input type="checkbox" name="scope[]" value="clients_r" <?php echo isPermissionSet($scopes, "clients_r"); ?>>clients_r</label><br>
                                                        <label><input type="checkbox" name="scope[]" value="payments_r" <?php echo isPermissionSet($scopes, "payments_r"); ?>>payments_r</label><br>
                                                        <label><input type="checkbox" name="scope[]" value="refunds_r" <?php echo isPermissionSet($scopes, "refunds_r"); ?>>refunds_r</label><br>
                                                        <label><input type="checkbox" name="scope[]" value="webhooks_r" <?php echo isPermissionSet($scopes, "webhooks_r"); ?>>webhooks_r</label><br>
                                                        <label><input type="checkbox" name="scope[]" value="frauds_r" <?php echo isPermissionSet($scopes, "frauds_r"); ?>>frauds_r</label><br>
                                                        <label><input type="checkbox" name="scope[]" value="subscriptions_r" <?php echo isPermissionSet($scopes, "subscriptions_r"); ?>>subscriptions_r</label><br>
                                                        <label><input type="checkbox" name="scope[]" value="offers_r" <?php echo isPermissionSet($scopes, "offers_r"); ?>>offers_r</label><br>
                                                        <label><input type="checkbox" name="scope[]" value="preauthorizations_r" <?php echo isPermissionSet($scopes, "preauthorizations_r"); ?>>preauthorizations_r</label><br>

                                                    </div>
                                                    <div class="col-sm-12 col-md-4">
                                                        <label><input type="checkbox" name="scope[]" value="transactions_rw" <?php echo isPermissionSet($scopes, "transactions_rw"); ?> >transactions_rw</label><br>
                                                        <label><input type="checkbox" name="scope[]" value="clients_rw" <?php echo isPermissionSet($scopes, "clients_rw"); ?>>clients_rw</label><br>
                                                        <label><input type="checkbox" name="scope[]" value="payments_rw" <?php echo isPermissionSet($scopes, "payments_rw"); ?>>payments_rw</label><br>
                                                        <label><input type="checkbox" name="scope[]" value="refunds_rw" <?php echo isPermissionSet($scopes, "refunds_rw"); ?>>refunds_rw</label><br>
                                                        <label><input type="checkbox" name="scope[]" value="webhooks_rw" <?php echo isPermissionSet($scopes, "webhooks_rw"); ?>>webhooks_rw</label><br>
                                                        <label><input type="checkbox" name="scope[]" value="frauds_rw" <?php echo isPermissionSet($scopes, "frauds_rw"); ?>>frauds_rw</label><br>
                                                        <label><input type="checkbox" name="scope[]" value="subscriptions_rw" <?php echo isPermissionSet($scopes, "subscriptions_rw"); ?>>subscriptions_rw</label><br>
                                                        <label><input type="checkbox" name="scope[]" value="offers_rw" <?php echo isPermissionSet($scopes, "offers_rw"); ?>>offers_rw</label><br>
                                                        <label><input type="checkbox" name="scope[]" value="preauthorizations_rw" <?php echo isPermissionSet($scopes, "preauthorizations_rw"); ?>>preauthorizations_rw</label><br>

                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Redirect URI</td>
                                            <td><p>Your script which handles the returning authorization data <br><i>(e.g. access keys etc.)</i>.</p></td>
                                            <td><input type="text" name="redirectUri" value="<?php echo $redirect_uri; ?>" /> </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="panel-footer">File: <code>library/unite.php</code></div>
                            </div>
                        </div>
                    </div>
                </div>
                <p>
                <button type="submit" class="btn btn-success btn-sm pull-right">
                    Go on with the connect button example
                    <span class="glyphicon glyphicon-chevron-right "></span>
                </button>
                </p>
                <div class="Footer">
                    <p>&copy; PAYMILL GmbH</p>
                </div>
            </div>
        </form>
    </div>
</body>
</html>