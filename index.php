<?php

require 'src/start.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <?php if ($user->member) : ?>
        <p>You are a member</p>
    <?php else : ?>
        <p>You are not a member <a href="member/payment.php">Become a member</a></p>
    <?php endif; ?>
</body>

</html>