<?php
    include 'library/unite.php';

    $row = 1;
    if (($handle = @fopen("system/merchant.csv", "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $num = count($data);

            for ($c=0; $c < $num; $c++) {
                //$merchant_id = $data[$merchant_id];
                $public_key = $data[2];
                $access_token = $data[0];
            }
        }
        fclose($handle);
    }
?>
<!DOCTYPE html>

<html lang="en-gb">

    <head>
        <script type="text/javascript" src="js/jquery-ui/js/jquery-1.9.1.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui/js/jquery-ui-1.10.1.custom.min.js"></script>

        <script type="text/javascript" src="https://bridge.paymill.com"></script>

        <script type="text/javascript">
            var PAYMILL_PUBLIC_KEY = '<?php echo $public_key ?>';

            $(document).ready(function() {
                $("#payment-form").submit(function(event) {
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

                    $.post(
                        "paymill_test.php",
                        $("#payment-form").serialize(),
                        function(result) {
                            //very simple frontend test if API response sets transaction to closed
                            if(result.indexOf('closed') != -1) {
                                $('.payment-errors').html('<span style="color: #009900">Payment successfully done!</span>');
                            }
                            else {
                                $('.payment-errors').html('<span style="color: #ff0000;font-weight: normal">There was an error.</span>');
                            }
                        },
                        'text'
                    );
                }
            }
        </script>

        <link rel="stylesheet" href="css/bootstrap/css/bootstrap.min.css" type="text/css" />
        <link rel="stylesheet" href="css/screen.css" type="text/css" />
    </head>

    <body>
        <div class="header">
            <h1>Paymill Unite Demo-Shop</h1>
        </div>
        <div class="main">
            <h4>basket</h4>
            <table width="100%">
                <thead>
                    <tr bgcolor="#d3d3d3">
                        <th align="left">Description</th>
                        <th>Amount</th>
                        <th align="right">Price (netto)</th>
                        <th align="right">Price (brutto)</th>
                    </tr>
                </thead>
                <tr bgcolor="#ffffff">
                    <td>AC Cobra</td>
                    <td align="center">1</td>
                    <td align="right">16,72 &euro;</td>
                    <td align="right">19,90 &euro;</td>
                </tr>
                <tr bgcolor="#eeeeee">
                    <td>Porsche 911 993 Carrera 4S</td>
                    <td align="center">1</td>
                    <td align="right">16,72 &euro;</td>
                    <td align="right">19,90 &euro;</td>
                </tr>
                <tr bgcolor="#ffffff">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td align="right" style="border-top: 4px double #d3d3d3"><b>39,80 &euro;</b></td>
                </tr>
            </table>

            <div class="payment-form">
                <form id="payment-form">
                    <div class="payment-errors"> </div>
                    <div class="form-row">
                        <div class="labeldiv"><label>card number</label></div>
                        <input class="card-number" type="text" size="20" value="4111111111111111" />
                    </div>
                    <div class="form-row">
                        <div class="labeldiv"><label>CVC</label></div>
                        <input class="card-cvc" type="text" size="4" value="111" />
                    </div>
                    <div class="form-row">
                        <div class="labeldiv"><label>Name</label></div>
                        <input class="card-holdername" type="text" size="20" value="lala" />
                    </div>

                    <div class="form-row">
                        <div class="labeldiv"><label>end date (MM/YYYY)</label></div>
                        <input class="card-expiry-month input-small" type="text" size="2" value="12" />
                        <span> </span>
                        <input class="card-expiry-year input-small" type="text" size="4" value="2013" />
                    </div>
                    <div class="form-row">
                        <div class="labeldiv"><label>Price</label></div>
                        <input readonly class="card-amount" name="card-amount" type="text" value="3980" size="20" />
                    </div>
                    <div class="form-row">
                        <div class="labeldiv"><label>Fee</label></div>
                        <input readonly class="card-amount" name="card-fee" type="text" value="398" size="20" />
                    </div>
                    <div class="form-row">
                        <div class="labeldiv"><label>Currency</label></div>
                        <input readonly class="card-currency" name="card-currency" type="text" value="EUR" size="20" />
                    </div>
                    <button class="btn btn-large" type="submit">Buy now</button>
                </form>
            </div>

        </div>

        <div class="footer">
            <div class="footerContent">
                &copy; PAYMILL GmbH
            </div>
        </div>
    </body>

</html>