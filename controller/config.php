<?php
/**
 * Libraries and helpers
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/common/Message.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/common/constant/System_Constant.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/simple_html_dom.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/phpWebHacks.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/functions.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/dao/Query.php';

use common\System_Constant;

/**
 * Environment settings
 */
define('ENVIRONMENT', System_Constant::ENV_DEVELOP);

/**
 * Common assets
 */
print '<meta content="text/html" charset="utf-8">';
print '<link rel="stylesheet" href="../css/FStyle.css">';

