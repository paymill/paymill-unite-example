<?php

/**
 * Test-PHP for Paymill API, minimum example
 *
 */

include 'library/unite.php';

//define vars
define('API_HOST', $paymill_api_root);

// Get connect data from the CSV storage:
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

define('API_KEY', $access_token);

set_include_path(implode(PATH_SEPARATOR, array( realpath(realpath(dirname(__FILE__)) . '/library'), get_include_path(), )));

$amount = $_POST["card-amount"];
$fee    = $_POST["card-fee"];
$token  = $_POST['paymillToken'];


// Payment object which is needed for the fee collection:

$fee_payment = '<YOUR-MERCHANTS-PAYMENT-ID>';

// The connected merchant need to be a client of your app with a valid
// payment object. Normally you would ask your merchant to register a payment
// for the fee collection. The payment or client ID would then be stored in your
// DB to overgive it here at this place.


if ($token) {
	require "Services/Paymill/Transactions.php";

	$transactionsObject = new Services_Paymill_Transactions(API_KEY, API_HOST);
	$params = array(
		'amount'      => $amount,
		'currency'    => $_POST["card-currency"],
		'token'       => $token,
        'fee_amount'  => $fee,
        'fee_payment' => $fee_payment,
		'description' => 'Test Transaction'
	);

	$transaction = $transactionsObject->create($params);
    // The return of the "create" method is an array with transaction
    // attributes like "description", "status" etc.

	// return result from transaction to frontend
	var_dump($transaction);
}
?>