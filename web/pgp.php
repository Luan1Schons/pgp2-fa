<?php
session_start();
include('./sdk/pgp-2fa.php');

$pgp = new PGP2FA();
$msg = '';
$returnpgp = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['pgp-key'])) {
        $pgp->generateSecretPassphrase();
        $returnpgp = $pgp->encryptSecretPassphrase($_POST['pgp-key']);
    } elseif (isset($_POST['user-input'])) {
        if ($pgp->compareSecretPassphrase($_POST['user-input'])) {
            $msg = '<div class="alert alert-success">Success!</div>';
        } else {
            $msg = '<div class="alert alert-danger">Fail!</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2FA-PGP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        body {
            padding-top: 50px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1 class="text-center">Two Factor Authentication PGP</h1>
    <?php echo $msg ?>
    <form action="pgp.php" method="post">
        <div class="mb-3">
            <label for="pgp-key" class="form-label">Encrypted:</label>
            <textarea rows="10" class="form-control" id="pgp-key" name="pgp-msg" readonly><?php echo $returnpgp ?></textarea>
        </div>
        <div class="mb-3">
            <label for="user-input" class="form-label">Decrypted:</label>
            <input type="text" name="user-input" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary form-control">Check!</button>
    </form>
</div>
</body>
</html>
