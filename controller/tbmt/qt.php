<?php
include '../config.php';
include '../../route/Msc_Tbmtqt_Route.php';

use common\Message;
use route\Msc_Tbmtqt_Route as route;

//show loop
print loop(ENVIRONMENT);

//message
$msg = new Message();

//route
$config = [
    'category' => constant($_GET['c']),
    'page' => 'THONG_BAO_MOI_THAU_QUOC_TE',
];
$route = new Route($config);

$url = $route->getUrl();
if (!$url) {
    $msg->insert($route->getError());
    print $msg->toHtml();
}


