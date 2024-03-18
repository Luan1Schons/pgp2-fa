<?php session_start(); ?>
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
    <form action="pgp.php" method="post">
        <div class="mb-3">
            <label for="pgp-key" class="form-label">Public Key:</label>
            <textarea rows="10" class="form-control" id="pgp-key" name="pgp-key" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
</body>
</html>
