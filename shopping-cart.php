<?php
    session_start();
    include 'library/unite.php';

    //var_dump($_SESSION['payment']);exit;
    //var_dump($_SESSION['accessMerchant']);exit;

    $disabled = "";
    $last4 = "";
    $cardholder = "";
    $expiredate =  "";
    $payment_id =  "";
    $paymillToken = "";


    if(!isset($_SESSION['payment']))
    {
        $disabled = "disabled";
    } else {
        $last4 =  $_SESSION['payment']['last4'];
        $cardholder = $_SESSION['payment']['card_holder'] ;
        $expiredate = $_SESSION['payment']['expire_month']. ' / ' . $_SESSION['payment']['expire_year'] ;
        $payment_id = $_SESSION['payment']['id'];
        $paymillToken =  $_SESSION['payment']['paymillToken'];
    }

    $public_key =  $_SESSION['accessMerchant']['publicKey'];
    //var_dump($paymillToken, $payment_id);exit;
?>
<!DOCTYPE html>
<html lang="en-gb">
    <head>
        <script type="text/javascript" src="assets/js/jquery/jquery-1.10.1.min.js"></script>
        <script type="text/javascript" src="assets/js/jquery/jquery-1.10.2.min.js"></script>
        <script type="text/javascript" src="assets/js/bootstrap/bootstrap.min.js"></script>

        <script type="text/javascript" src="<?php echo $bridge_url; ?>"></script>
        <link rel="stylesheet" href="assets/css/screen.css">

        <script type="text/javascript">
            var PAYMILL_PUBLIC_KEY = '<?php echo $public_key; ?>';

            $(document).ready(function() {
                //Without fee
                $("#payment-form").submit(function(event) {
                    $(".payment-errors").text('');
                    $('.api-response').addClass('hidden');

                    // Deactivate submit button to avoid further clicks
                    $('.submit-button').attr("disabled", "disabled");

                    if (false == paymill.validateCardNumber($(".card-number").val())) {
                        $(".payment-errors").html("<span style='color: #ff0000'>Invalid Card Number</span>");
                        return false;
                    }
                    if (false == paymill.validateExpiry($(".card-expiry-month").val(), $(".card-expiry-year").val())) {
                        $(".payment-errors").html("<span style='color: #ff0000'>Invalid Valid To Date</span>");
                        return false;
                    }
                    if (false == paymill.validateCvc($(".card-cvc").val())) {
                        $(".payment-errors").html("<span style='color: #ff0000'>Invalid CVC</span>");
                        return false;
                    }

                    paymill.createToken({
                        number: $('.card-number').val(),  // required, ohne Leerzeichen und Bindestriche
                        exp_month: $('.card-expiry-month').val(),   // required
                        exp_year: $('.card-expiry-year').val(),     // required, vierstellig z.B. "2016"
                        cvc: $('.card-cvc').val(),                  // required
                        amount_int: $('.card-amount').val(),      // required, integer, z.B. "15" für 0.15 Euro
                        currency: $('.card-currency').val(),  // required, ISO 4217 z.B. "EUR" od. "GBP"
                        cardholdername: $('.card-holdername').val() // optional
                    }, PaymillResponseHandler);                   // Info dazu weiter unten

                    return false;
                });

                // fee
                $("#payment-form-fee").submit(function(event) {
                    console.log('click');
                    $(".payment-errors").text('');
                    $('.api-response').addClass('hidden');

                    // Deactivate submit button to avoid further clicks
                    $('.submit-button').attr("disabled", "disabled");

                    PaymillResponseHandlerFee();
                    return false;

                });
            });


            function PaymillResponseHandler(error, result) {
                if (error) {
                    // Shows the error above the form
                    $(".payment-errors").text(error.apierror);
                    $(".submit-button").removeAttr("disabled");
                } else {
                    var form = $("#payment-form");
                    // Output token
                    var token = result.token;

                    // Insert token into form in order to submit to server
                    form.append("<input type='hidden' name='paymillToken' value='" + token + "'/>");
                    //with fee
                    form.append("<input type='hidden' name='withFee' value='0'/>");

                    $.post(
                        "api-trx-request.php",
                        $("#payment-form").serialize(),
                        function(result) {
                            //very simple frontend test if API response sets transaction to closed
                            if(result.indexOf('closed') != -1) {
                                $('.api-response .panel-body').html('<span style="color: #009900">Transaction successfully done!</span><br><br><pre class="pre-scrollable">'+ result +'</pre>');
                            }
                            else {
                                $('.api-response .panel-body').html('<span style="color: #ff0000;font-weight: normal">There was an error.</span>');
                            }
                            $('.api-response').removeClass('hidden');
                        },
                        'text'
                    );
                }
            }

            function PaymillResponseHandlerFee(error, result) {
                if (error) {
                    // Shows the error above the form
                    $(".payment-errors").text(error.apierror);
                    $(".submit-button").removeAttr("disabled");
                } else {

                    var formfee = $("#payment-form-fee");
                    // Insert token und payment_id into form in order to submit to server
                    formfee.append("<input type='hidden' name='paymillToken' value='<?php echo $paymillToken; ?>'/>");
                    formfee.append("<input type='hidden' name='payment_id' value='<?php echo $payment_id; ?>'/>");
                    formfee.append("<input type='hidden' name='withFee' value='1'/>");

                    $.post(
                        "api-trx-request.php",
                        $("#payment-form-fee").serialize(),
                        function(result) {
                            //very simple frontend test if API response sets transaction to closed
                            if(result.indexOf('closed') != -1) {
                                $('.api-response .panel-body').html('<span style="color: #009900">Transaction successfully done!</span><br><br><pre class="pre-scrollable">'+ result +'</pre>');
                            }
                            else {
                                $('.api-response .panel-body').html('<span style="color: #ff0000;font-weight: normal">There was an error.</span>');
                            }
                            $('.api-response').removeClass('hidden');
                        },
                        'text'
                    );
                }
            }
        </script>

    </head>

    <body>
        <section id="Sidebar">
            <div id="TopBar">
              <a href="#" id="PushSidebar" class="burgermenu"><i class="fa fa-bars"></i></a>
              <h1 id="Branding">PAYMILL Unite Demo</h1>
            </div>
            <nav>
                <ul>
                    <li>
                        <a href="index.php" ><i class="fa fa-code fa-fw"></i>1. Configuration</a>
                    </li>
                     <li>
                        <a href="#"><i class="fa fa-code fa-fw "></i>2. Connect</a>
                        <ol class="">
                            <li><a href="connect.php">Connect to a Merchant</a></li>
                            <li><a href="payment.php">Payment</a></li>
                        </ol>
                    </li>
                     <li class="active">
                        <a href="shopping-cart.php"><i class="fa fa-code fa-fw"></i>3. Shopping Cart</a>
                    </li>
                     <li>
                        <a href="refresh-token.php"><i class="fa fa-code fa-fw"></i>4. Refresh Token</a>
                    </li>
                </ul>
            </nav>
        </section>

        <div id="Content">
            <div class="container">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h3 class="panel-title">Demo shopping cart</h3>
                  </div>
                  <div class="panel-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="text-left">Description</th>
                                <th class="text-right">Amount</th>
                                <th class="text-right">Price (net)</th>
                                <th class="text-right">Price (gross)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-left">Item 1</td>
                                <td class="text-right">1</td>
                                <td class="text-right">1.26 &euro;</td>
                                <td class="text-right">1.50 &euro;</td>
                            </tr>
                            <tr>
                                <td class="text-left">Item 2</td>
                                <td class="text-right">1</td>
                                <td class="text-right">0,84 &euro;</td>
                                <td class="text-right">1.00 &euro;</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-right" style="border-top: 4px double #d3d3d3"><b>2.50 &euro;</b></td>
                            </tr>
                        </tbody>
                    </table>
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

                <div class="row">
                    <div class="col-xs-6">
                        <div class="panel panel-default ">
                          <div class="panel-heading">
                            <h3 class="panel-title">Demo payment form - without fee</h3>

                          </div>
                          <div class="panel-body">
                                <form role="form" id="payment-form" >
                                    <div class="payment-errors"> </div>

                                    <div class="form-group">
                                        <label>Card number</label>
                                        <input class="card-number form-control" type="text" size="20" value="4111111111111111" />
                                    </div>
                                    <div class="form-group">
                                        <label>CVC</label>
                                        <input class="card-cvc form-control" type="text" size="4" value="111" />
                                    </div>
                                    <div class="form-group">
                                        <label>Holder name</label>
                                        <input class="card-holdername form-control" type="text" size="20" value="lala" />
                                    </div>

                                    <div class="form-group">
                                        <label>Expire date (MM/YYYY)</label>
                                        <div class="row">
                                            <div class="col-md-3"><input class="card-expiry-month form-control" type="text" size="2" value="12" /></div>
                                            <div class="col-md-4"><input class="card-expiry-year form-control" type="text" size="4" value="2016" /></div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Price (in Cent)</label>
                                        <input readonly class="card-amount form-control" name="card-amount" type="text" value="250" size="20" />
                                    </div>
                                    <?php if($public_key): ?>
                                    <button class="btn btn-sm btn-primary" type="submit" >Buy now</button>
                                    <?php endif; ?>
                                </form>
                           </div>
                           <div class="panel-footer">
                               <?php if($public_key): ?>
                                   Used public key: <code><?php echo $public_key; ?></code><br>
                                   Live key: <code><strong><?php echo $is_live ? 'yes!!!' : 'no'; ?></strong></code>
                               <?php else: ?>
                                   <h3>Do don't have access keys yet. Please first connect a merchant:</h3>
                                   <a href="connect.php" class="btn btn-primary btn-sm">
                                      Connect page
                                   </a>
                               <?php endif; ?>
                           </div>
                        </div>
                    </div>


                    <div class="col-xs-6">
                        <div class="panel panel-default ">
                          <div class="panel-heading">
                            <h3 class="panel-title">Demo payment form - with fee</h3>

                          </div>
                          <div class="panel-body">
                                <form role="form" id="payment-form-fee" >
                                    <div class="payment-errors"> </div>

                                    <h4>Uses the Payment generated in Step 2 - Payment:</h4>
                                    <p>
                                        <label>Card number (last 4)</label>: <?php echo $last4; ?>
                                    </p>
                                    <p>
                                        <label>Holder name</label>: <?php echo $cardholder; ?>
                                    </p>
                                    <p>
                                        <label>Expire date (MM/YYYY)</label>: <?php echo $expiredate; ?>
                                    </p>

                                    <div class="form-group">
                                        <label>Price (in Cent)</label>
                                        <input  class="card-amount form-control" name="card-amount" type="text" placeholder="250" size="20" value="333" />
                                    </div>
                                    <div class="form-group">
                                        <label>Fee (in Cent)</label>
                                        <input  class="card-fee form-control" name="card-fee" type="text" placeholder="50" size="20" value="33"/>
                                    </div>
                                    <div class="form-group">
                                        <label>Currency</label>
                                        <input  class="card-currency form-control" name="card-currency" type="text" placeholder="EUR" size="20" value="EUR" />
                                    </div>
                                    <?php if($public_key): ?>
                                    <button class="btn btn-sm btn-primary" type="submit" <?php echo $disabled; ?>>Buy now</button>
                                    <?php endif; ?>
                                </form>
                           </div>
                           <div class="panel-footer">
                               <?php if($public_key): ?>
                                   Used public key: <code><?php echo $public_key; ?></code><br>
                                   Live key: <code><strong><?php echo $is_live ? 'yes!!!' : 'no'; ?></strong></code>
                               <?php else: ?>
                                   <h3>Do don't have access keys yet. Please first connect a merchant:</h3>
                                   <a href="connect.php" class="btn btn-primary btn-sm">
                                      Connect page
                                   </a>
                               <?php endif; ?>
                               <br>Used payment_id: <code><?php echo $payment_id; ?></code>
                           </div>
                        </div>
                    </div>
                </div>

                <p>
                  <a href="." class="btn btn-success btn-sm pull-left">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                    Back to intro
                  </a>
                  <a href="https://app.paymill.com" class="btn btn-success btn-sm pull-right">
                    PAYMILL Cockpit
                    <span class="glyphicon glyphicon-chevron-right "></span>
                  </a>
                </p>

                 <div class="Footer">
                    <p>&copy; PAYMILL GmbH</p>
                </div>
            </div>
        </div>
    </body>
</html>