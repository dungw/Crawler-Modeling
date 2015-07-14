<?php
/**
 * Libraries and helpers
 */
include '../common/Message.php';
include '../common/Constants.php';
include '../lib/simple_html_dom.php';
include '../lib/phpWebHacks.php';
include '../lib/functions.php';
include '../dao/Query.php';

use common\Constants;

/**
 * Environment settings
 */
define('ENVIRONMENT', Constants::ENV_DEVELOP);

/**
 * Common assets
 */
print '<meta content="text/html" charset="utf-8">';
print '<link rel="stylesheet" href="../css/FStyle.css">';

