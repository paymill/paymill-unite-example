<?php

include 'library/unite.php';

//define vars
define('API_HOST', $paymill_api_root);
define('API_KEY', $private_access_key);

set_include_path(
    implode(PATH_SEPARATOR, array( realpath(realpath(dirname(__FILE__)) . '/library'), get_include_path(), ))
);

$amount = floatval($_POST["card-amount"]) * 100;
$token = $_POST['paymillToken'];

if ($token) {
    require "Services/Paymill/Transactions.php";

    $transactionsObject = new Services_Paymill_Transactions(API_KEY, API_HOST);
    $params = array(
        'amount' => $amount,
        'currency' => $_POST["card-currency"],
        'token' => $token,
        'description' => 'Test Transaction'
    );
    $transaction = $transactionsObject->create($params);

    //access single elements from transaction as follows:
    $email = $transaction['description'];
    $status = $transaction['status'];

    // return result from transaction to frontend
    return $transaction;
}
?>