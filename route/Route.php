<?php
/**
 * Created by PhpStorm.
 * User: JFog
 * Date: 7/12/2015
 * Time: 2:46 PM
 */
namespace route;

abstract class Route
{
    const ACTIVE = 1;
    const INACTIVE = 0;
    const MODE_NEW = 'new';

    protected $error = null;
    protected $mode = null;

    public $url = null;
    public $pageSize = 10;
    public $pageNo = 1;

    public function setMode($mode)
    {
        if ($mode == self::MODE_NEW) {
            $this->mode = $mode;
        }
    }

    public function setPageSize($size)
    {
        $this->pageSize = $size;
    }

    public function getPageSize()
    {
        return $this->pageSize;
    }

    public function setPageNo($no)
    {
        $this->pageNo = $no;
    }

    public function getPageNo()
    {
        return $this->pageNo;
    }

    public function setError($error)
    {
        $this->error = $error;
    }

    public function getError()
    {
        return $this->error;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        if (trim($url) != '') $this->url = $url;
    }
}