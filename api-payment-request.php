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

// This must be the only value you send via the form:
$token  = $_POST['paymillToken'];

$apiPrivateKey = $_POST['payment']['privatekey'];


if ($token) {
	require "Services/Paymill/Payments.php";

	$paymentObject = new Services_Paymill_Payments($apiPrivateKey, $paymill_api_root);
	$params = array(
		'token'       => $token
	);

	$payment = $paymentObject->create($params);
	$_SESSION['payment'] = $payment;
	$_SESSION['payment']['paymillToken'] = $token;

    // The return of the "create" method is an array with transaction
    // attributes like "description", "status" etc.

	// return result from transaction to frontend
	//echo json_encode($payment);
} else {
    echo 'No token found :(';
}
