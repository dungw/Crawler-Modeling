<?php
namespace common\constant\msc;

require_once $_SERVER['DOCUMENT_ROOT'] . '/common/constant/Constant.php';

use common\constant\Constant;

class Msc_Constant extends Constant
{
    /**
     * Types
     */
    const TYPE_NT = 1;
    const TYPE_MT = 2;
    const TB_KHDT = 3;
    const TB_BEN_MT = 4;

    /**
     * Categories
     */
    const HANG_HOA = 1;
    const XAY_LAP = 3;
    const TU_VAN = 5;
    const HON_HOP = 10;
    const PHI_TU_VAN = 15;
}
