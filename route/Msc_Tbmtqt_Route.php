<?php
namespace route;

require_once $_SERVER['DOCUMENT_ROOT'] . '/route/Msc_Route.php';
use route\Msc_Route;

class Msc_Tbmtqt_Route extends Msc_Route
{
    protected function _getParams()
    {
        return array(
            'gubun'         => $this->category,
            'fromDate'      => $this->getFromDate(),
            'toDate'        => $this->getToDate(),
            'pageSize'      => $this->pageSize,
            'pageNo'        => $this->getPageNo(),
            'pqCls'         => 'Y',
            'bidMethod'     => '',
            'viewType'      => 0,
            'instituName'   => '',
            'instituCode'   => '',
            'isInstitu'     => '',
            'bidNM'         => '',
            'typeFind'      => 1,
            'fromOpenDate'  => '01/01/2010',
            'toOpenDate'    => '31/12/2050',
            'refNumber'     => '',
            'firstCall'     => 'N',
        );
    }
}