<?php
    $msg = $_POST["email"] . "\r\n" . $_POST["message"];
    mail("dror.m.maor@gmail.com", "BBP contact", $msg);
?>
