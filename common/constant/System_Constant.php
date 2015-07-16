<?php
/**
 * Created by PhpStorm.
 * User: dung.vuong
 * Date: 7/14/2015
 * Time: 2:02 PM
 */
namespace common;

require_once $_SERVER['DOCUMENT_ROOT'] . '/common/constant/Constant.php';

use common\constant\Constant;

class System_Constant extends Constant
{
    /**
     * Environments
     */
    const ENV_PRODUCTION = 'production';
    const ENV_DEVELOP = 'develop';
}