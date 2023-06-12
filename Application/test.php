<?php

$simple_string = "Welcome to GeeksforGeeks\n";

echo "Original String: " . $simple_string;

$ciphering = "AES-128-CTR";

$encryption_iv = '1234';

$encryption_key = "userPassword";

$encryption = openssl_encrypt($simple_string, $ciphering,
            $encryption_key, $options=0, $encryption_iv);

echo "<br> Encripted msg : " .$encryption;

$decryption_iv = '1234';

$decryption_key = "userPassword";

$decryption= openssl_decrypt ($encryption, $ciphering,
        $decryption_key, $options=0, $decryption_iv);

echo "<br> Decripted msg : " .$decryption;      

?>