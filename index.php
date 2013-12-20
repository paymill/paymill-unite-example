<?php
    include 'library/unite.php';
?>
<!DOCTYPE html>
<html lang="en-gb">
    <head>
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">
    </head>

    <body>
        <div class="container">
        <div class="header">
            <h1><img src="img/icon.png" style="height: 27px; margin-bottom: 6px;" /> PAYMILL Unite Demo</h1>
        </div>


        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Welcome to the PAYMILL Unite demo</h3>
          </div>
          <div class="panel-body">
            <p>This is a very minimalistic PAYMILL Unite demo written in PHP.<br>
            With this demo we want to show you how to use PAYMILL Unite to connect your app with a merchant.</p>

            <p><a href="https://paymill.com/en-gb/unite-documentation/">Please read the PAYMILL Unite documentation</a> before you start.</p>
          </div>
        </div>


        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Current configuration</h3>
          </div>
          <div class="panel-body">
            <p>There's a basic configuration file where you can set up your
                connection data for the OAuth <a href="connect.php">connect</a>:</p>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Setting</th>
                        <th>Value</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><a href="https://paymill.com/en-gb/documentation-3/reference/paymill-bridge">Bridge</a></td>
                        <td><code><?php echo $bridge_url; ?></code></td>
                        <td>JavaScript which handles the payment form validation and payment token generation. (Don't modify!)</td>
                    </tr>
                    <tr>
                        <td><a href="https://paymill.com/en-gb/documentation-3/reference/api-reference">API</a> root</td>
                        <td><code><?php echo $paymill_api_root; ?></code></td>
                        <td>API root URL (Don't modify!)</td>
                    </tr>
                    <tr>
                        <td>PAYMILL Connect URL</td>
                        <td><code><?php echo $paymill_root; ?></code></td>
                        <td>PAYMILL Connect form where the merchant authorizes your connect request. (Don't modify!)</td>
                    </tr>
                    <tr>
                        <td><strong>Client ID</strong></td>
                        <td><code><?php echo $client_id; ?></code></td>
                        <td>Equivalent to your App ID. Can be found in <br><em><a href="https://app.paymill.com">PAYMILL Cockpit</a> -> Settings -> App -> App details</em></td>
                    </tr>
                    <tr>
                        <td><strong>Client Secret</strong></td>
                        <td><code><?php echo $client_secret; ?></code></td>
                        <td>Needed for authentication. Can be found in <br><em><a href="https://app.paymill.com">PAYMILL Cockpit</a> -> Settings -> App -> App details</em></td>
                    </tr>
                    <tr>
                        <td>Grant type</td>
                        <td><code><?php echo $grant_type; ?></code></td>
                        <td>OAuth grant type to request the authorization data (Don't modify!)</td>
                    </tr>
                    <tr>
                        <td>Scope</td>
                        <td><code><?php echo $scope; ?></code></td>
                        <td>Permissions you are asking for.</td>
                    </tr>
                    <tr>
                        <td>Redirect URI</td>
                        <td><code><?php echo $redirect_uri; ?></code></td>
                        <td>Your script which handles the returning authorization data (e.g. access keys etc.).</td>
                    </tr>
                </tbody>
            </table>

            <p>
                <strong>Note:</strong> Please configure theses settings before you go on. You need at least one merchant account which acts as app.
                If you don't have one yet just sign up for a free test account at PAYMILL (It takes just 2 minutes).
                <a class="btn btn-xs btn-primary" href="https://app.paymill.com/en-gb/auth/register">Sign up.</a>
            </p>

          </div>
          <div class="panel-footer">File: <code>library/unite.php</code></div>
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
                    The button could look like our <a href="connect.php">example</a>.
                </li>
                <li>
                    The button will send your merchant to the <a href="https://connect.paymill.com">PAYMILL Connect</a> page, were he can authorize your request.
                </li>
                <li>
                    After the authorization the merchant will be redirected to your above defined redirect URI, were the received access keys are stored.
                </li>
                <li>
                    Now if everything worked well, you can do a test transaction with our shopping cart <a href="shopping-cart.php">example</a>.
                </li>
            </ol>
          </div>
        </div>


        <p>
           <a href="connect.php" class="btn btn-primary btn-sm">
            Go on with the connect button example
            <span class="glyphicon glyphicon-chevron-right"></span>
          </a>
        </p>

        <p>&copy; PAYMILL GmbH</p>

        </div>
    </body>

</html>