<?php
namespace route;

class Msc_Kqdttt_Route extends Msc_Route
{
    protected function _getParams()
    {
        return array(
            'pqCls'         => 'Y',
            'bidMethod'     => '00',
            'viewType'      => '0',
            'instituName'   => '',
            'instituCode'   => '',
            'isInstitu'     => '0',
            'bidNM'         => '',
            'fromOpenDate'  => $this->getFromDate(),
            'toOpenDate'    => $this->getToDate(),
            'fromDate'      => '01/01/2010',
            'toDate'        => '31/12/2050',
            'refNumber'     => '',
            'Bid_succ_offline_yn'   => 'N',
            'pageSize'      => $this->pageSize,
            'firstCall'     => 'N',
            'pageNo'        => $this->getPageNo(),
            'gubun'         => $this->category,
        );
    }
}