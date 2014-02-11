<?php
session_start();
/**
 * Test-PHP for Paymill API, minimum example
 *
 */

header('Content-Type: application/json');

include 'library/unite.php';

//define vars
set_include_path(implode(PATH_SEPARATOR, array( realpath(realpath(dirname(__FILE__)) . '/library'), get_include_path(), )));


// Don't get this values from the form to avoid manipulation:
if(!isset($_SESSION['payment'])) {

	// This must be the only value you send via the form:
	$token  = $_POST['paymillToken'];
	$amount   = 250;
	$currency = 'EUR';
	$fee      = 0;
	// Payment object which is needed for the fee collection:
	$fee_payment = null; //'<YOUR-MERCHANTS-PAYMENT-ID>';
} else {
	// This must be the only value you send via the form:
	$token  = $_SESSION['payment']['paymillToken'];
	$amount   = $_POST['card-amount'];
	$currency = $_POST['card-currency'];
	$fee      = $_POST['card-fee'];
	// Payment object which is needed for the fee collection:
	$fee_payment = $_SESSION['payment']['id'];
}

// The connected merchant need to be a client of your app with a valid
// payment object. Normally you would ask your merchant to register a payment
// for the fee collection. The payment or client ID would then be stored in your
// DB to overgive it here at this place.


if ($token) {
	require "Services/Paymill/Transactions.php";

	$transactionsObject = new Services_Paymill_Transactions($private_key, $paymill_api_root);
	$params = array(
		'amount'      => $amount,
		'currency'    => $currency,
		'token'       => $token,
		'description' => 'Test Transaction' // Here you can save for shopping cart ID.
	);

    if($fee_payment && $fee) {
        $params['fee_payment'] = $fee_payment;
        $params['fee'] = $fee;
    }

    var_dump($params);

	$transaction = $transactionsObject->create($params);
    // The return of the "create" method is an array with transaction
    // attributes like "description", "status" etc.

	// return result from transaction to frontend
	echo json_encode($transaction);
} else {
    echo 'No token found :(';
}