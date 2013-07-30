<?php

if($_GET['code']) {
    $success = true;
    $code = $_GET['code'];
    $error = false;
    $msg = 'Authorization was successful!';
}
else {
    $success = false;
    $code = false;
    $error = $_GET['error'];
    $msg = str_replace('+', ' ', $_GET['error_description']);
}
?>
<!DOCTYPE html>

<html lang="en-gb">

<head>
    <title>PAYMILL UNITE - Merchant</title>
    <script type="text/javascript" src="../js/jquery-ui/js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="../js/jquery-ui/js/jquery-ui-1.10.1.custom.min.js"></script>
    <script type="text/javascript" src="../js/main.js"></script>

    <link rel="stylesheet" href="../css/bootstrap/css/bootstrap.min.css" type="text/css" />
    <link rel="stylesheet" href="../css/screen.css" type="text/css" />
</head>

<body>
        <div class="header">
            <h1>Paymill Unite Demo-Shop: Welcome, Merchant!</h1>
        </div>
        <div class="main">
            <div class="center">
                Hello, Merchant!

            </div>
            <div class="help">
                <h3>Short description</h3>
                <ul>
                    <li>Merchant was redirected after granting rights.</li>
                    <li>See response params below (also if there was an error).</li>
                </ul>
                Response:
                <br />
                <?php echo $msg ?>
                <br /><br />
                Authorization Code returned: <?php echo $code ?>
                <br /><br />
                <table>
                    <tr>
                        <td>Grant type</td>
                        <td><input type="text" readonly name="grant_type" value="<?php echo $grant_type ?>" /></td>
                    </tr>
                    <tr>
                        <td>Authorization Code</td>
                        <td><input type="text" readonly name="code" value="<?php echo $code ?>" /></td>
                    </tr>
                    <tr>
                        <td>Client ID</td>
                        <td><input type="text" readonly name="client_id" value="<?php echo $client_id ?>" /></td>
                    </tr>
                    <tr>
                        <td>Private Key</td>
                        <td><input type="text" readonly name="client_secret" value="<?php echo $private_access_key ?>" /></td>
                    </tr>
                </table>
                <button class="btn" type="submit">Send</button>
            </div>
        </div>
        <div class="footer">
            <div class="footerContent">
                &copy; PAYMILL GmbH
            </div>
        </div>
</body>

</html>