<?php
    session_start();

    include 'library/unite.php';

    // set created payment id or plaeholder
    $paymentId = "[your-current-fee-payment]";
    if(isset($_SESSION['payment']['id'])) {
        $paymentId = $_SESSION['payment']['id'];
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

    <script type="text/javascript">

            var PAYMILL_PUBLIC_KEY = "";
            $(document).ready(function() {

                $("#create-payment").submit(function(event) {
                    $('.payment-errors').text('');
                    $('.api-response').addClass('hidden');

                    if (false == paymill.validateCardNumber($("#number").val())) {
                        $(".payment-errors").html("<span style='color: #ff0000'>Invalid Card Number</span>");
                        return false;
                    }
                    if (false == paymill.validateExpiry($("#expire_month").val(), $("#expire_year").val())) {
                        $(".payment-errors").html("<span style='color: #ff0000'>Invalid Valid To Date</span>");
                        return false;
                    }
                    if (false == paymill.validateCvc($("#cvc").val())) {
                        $(".payment-errors").html("<span style='color: #ff0000'>Invalid CVC</span>");
                        return false;
                    }
                    PAYMILL_PUBLIC_KEY =  $('#public-key').val();

                    // Deactivate submit button to avoid further clicks
                    $('submit-button').attr("disabled", "disabled");

                    paymill.createToken({
                        number: $('#number').val(),     // required, ohne Leerzeichen und Bindestriche
                        exp_month: $('#expire_month').val(),                // required
                        exp_year: $('#expire_year').val(),               // required, vierstellig z.B. "2016"
                        cvc: $('#cvc').val(),                     // required
                        amount_int: $('#amount').val(),      // required, integer, z.B. "15" für 0.15 Euro
                        currency: $('#currency').val(),  // required, ISO 4217 z.B. "EUR" od. "GBP"
                        cardholder: $('#holder').val() // optional
                    }, PaymillResponseHandler);                   // Info dazu weiter unten
                    return false;
                });
            });

            function PaymillResponseHandler(error, result) {
                if (error) {
                    // Shows the error above the form
                    $(".payment-errors").text(error.apierror);
                    $(".submit-button").removeAttr("disabled");
                } else {
                    var form = $("#create-payment");
                    // Output token
                    var token = result.token;
                    // Insert token into form in order to submit to server
                    form.append("<input type='hidden' name='paymillToken' value='" + token + "'/>");
                    form.append("<input type='hidden' name='cardnumber' value='" + $('#number').val() + "'/>");
                    form.append("<input type='hidden' name='cvc' value='" + $('#cvc').val() +  "'/>");

                    $.post(
                        "api-payment-request.php",
                        $('#create-payment').serialize(),
                        function(result) {
                            //very simple frontend test if API response sets transaction to closed
                            $('.api-response .panel-body').html('<span style="color: #009900">Transaction successfully done!</span><br><br><pre class="pre-scrollable">'+ result +'</pre>');
                            $('.api-response').removeClass('hidden');
                            $('#payment-id').val('<?php echo $paymentId; ?>');
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
                        <li><a href="connect.php">Connect a Merchant</a></li>
                        <li class="active"><a href="#">Fee payment</a></li>
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
        <div class="container">
            <form class="form-horizontal" role="form" id="create-payment">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h3 class="panel-title">Add a fee payment</h3>
                  </div>
                  <div class="panel-body">
                    <p>If you want to add a fee to your Unite transactions, your merchants first need to
                        provide a payment method from which we can charge the demanded fees. <br>
                        Read more about the <a href="https://paymill.com/en-gb/unite-documentation/" target="_blank">fee payment</a>.
                    </p>
                  </div>
                </div>

                <div class="panel panel-danger">
                  <div class="panel-heading">
                    <h3 class="panel-title">API test keys</h3>
                  </div>
                  <div class="panel-body">
                    <p>
                        To create a fee payment you need your own API keys, because you want to receive the fees on your bank account.
                        This is just an example, please use <span class="text-danger" >only test keys</span> here! Feel free to create a separate PAYMILL test account.
                        Of course the private key normally never should be visible like in this example!
                    </p>
                    <p>
                        <div class="payment-errors"> </div>
                        <div class="form-group">
                            <label >Your Private test key
                                <br> <i><a href="https://app.paymill.com">PAYMILL Cockpit</a> -> Settings -> API-Keys -> Test keys</i></label>
                                <input type="text" placeholder="[private-test-key]" id="private-key" name="privatekey" class="form-control"  >
                        </div>
                        <div class="form-group">
                            <label >Your Public test key
                                <br> <i><a href="https://app.paymill.com">PAYMILL Cockpit</a> -> Settings -> API-Keys -> Test keys</i></label>
                                <input type="text" placeholder="[public-test-key]" id="public-key" name="public-key" class="form-control"  >
                        </div>
                    </p>
                  </div>
                </div>

                <div class="panel panel-danger">
                  <div class="panel-heading">
                    <h3 class="panel-title">Generate a fee payment</h3>
                  </div>
                  <div class="panel-body">
                        <p>
                            <strong>Your current Payment:</strong>
                            <code id="payment-id"><?php echo $paymentId; ?></code>
                        </p>

                         <div class="form-group">
                            <label>Card number</label>
                            <input class="card-number form-control" type="text" id="number" size="20" placeholder="4012888888881881" value="4012888888881881" />
                        </div>
                        <div class="form-group">
                            <label>CVC</label>
                            <input class="card-cvc form-control" type="text" id="cvc"  size="4" placeholder="123" value="123" />
                        </div>
                        <div class="form-group">
                            <label>Holder name</label>
                            <input class="card-holdername form-control" type="text" id="holder" size="20" placeholder="3-D Secure Payment" value="3-D Secure Payment"/>
                        </div>
                        <div class="form-group">
                            <label>Expire date (MM/YYYY)</label>
                            <div class="row">
                                <div class="col-xs-3"><input class="card-expiry-month form-control" type="text" id="expire_month" size="2" placeholder="12" value="12"/></div>
                                <div class="col-xs-4"><input class="card-expiry-year form-control" type="text" id="expire_year" size="4" placeholder="2016" value="2016"/></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Amoun (in Cent)</label>
                            <input  class="card-amount form-control" name="amount" type="text" placeholder="1" size="20" value="1" />
                        </div>
                        <div class="form-group">
                            <label>Currency</label>
                            <input  class="card-currency form-control " name="currency" type="text" placeholder="EUR" size="20" value="EUR" />
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary submit-button pull-right" >Create fee payment</button>

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
            </form>
        </div>
    </div>
</body>

</html>

