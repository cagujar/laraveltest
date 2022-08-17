<?php

$password = 'mypassword';
$key = 'mykey';
$hash = hash_hmac('sha256',$password,$key);
$base = base64_encode($hash);
echo($hash);
//echo($base)

?>