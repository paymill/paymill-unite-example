<?php
    require '../library/unite.php';
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
            <h1>Paymill Unite Marketplace: Merchant connection</h1>
        </div>
        <div class="main">
            <div class="center">
                <button type="button" class="btn" onclick="redirectOauth('<?php echo $paymill_root ?>', '<?php echo $client_id ?>', '<?php echo $scope ?>', '<?php echo $redirect_uri ?>')">Connect your PAYMILL account</button>
            </div>
            <div class="help well">
                <h3>Short description</h3>
                Redirect User to:
                <br /><br />
                <?php echo $paymill_root . '/authorize?client_id=' . $client_id . '&scope=' . $scope . '&response_type=code&redirect_uri=' . $redirect_uri ; ?>
            </div>
        </div>
        <div class="footer">
            <div class="footerContent">
                &copy; PAYMILL GmbH
            </div>
        </div>
</body>

</html>