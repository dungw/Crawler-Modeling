<?php
namespace route;

class Msc_Tbmt_Route extends Msc_Route
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