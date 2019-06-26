<?php

if (!file_exists(__DIR__.'/private.key') ||
    !file_exists(__DIR__.'/public.key')
) {
    $config = array(
        "digest_alg" => "sha512",//openssl_get_md_methods
        "private_key_bits" => 4096,
        "private_key_type" => OPENSSL_KEYTYPE_RSA,
    );

    // Create the private and public key
    $res = openssl_pkey_new($config);

    // Extract the private key from $res to $privKey
    openssl_pkey_export($res, $privKey);

    // Extract the public key from $res to $pubKey
    $pubKey = openssl_pkey_get_details($res);
    $pubKey = $pubKey["key"];

    file_put_contents(__DIR__.'/private.key', $privKey);
    file_put_contents(__DIR__.'/public.key', $pubKey);
} else {
    $privKey = openssl_get_privatekey(file_get_contents(__DIR__.'/private.key'));
    $pubKey = openssl_get_publickey(file_get_contents(__DIR__.'/public.key'));
}

$data = 'secret text';

// Encrypt the data to $encrypted using the public key
openssl_public_encrypt($data, $encrypted, $pubKey);

$encrypted = base64_encode($encrypted);
echo '<pre>',chunk_split($encrypted),'</pre>';
echo '<br>',PHP_EOL;

// Decrypt the data using the private key and store the results in $decrypted
openssl_private_decrypt(base64_decode($encrypted), $decrypted, $privKey);

echo $decrypted;
