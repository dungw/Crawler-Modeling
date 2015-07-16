<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/common/constant/msc/Msc_Constant.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/route/Msc_Tbmtqt_Route.php';

use common\constant\msc\Msc_Constant as Constant;
use common\Message;
use route\Msc_Tbmtqt_Route as route;

//show loop
print loop(ENVIRONMENT);

//message
$msg = new Message();

//route
$category = isset($_GET['c']) ? Constant::find($_GET['c']) : '';
$config = [
    'category' => ($category),
    'page' => 'THONG_BAO_MOI_THAU_QUOC_TE',
];
$route = new Route($config);

$url = $route->getUrl();
if (!$url) {
    $msg->insert($route->getError());
    echo $msg->toHtml();
} else {
    $msg->insertUrl($url);
}

$html = getHTML($url, 0, 8082);

