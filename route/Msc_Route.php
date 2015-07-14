<?php
namespace route;

require_once '../Query.php';
use dao\Query;

abstract class Msc_Route extends Route
{
    protected $category = null;
    protected $page = null;
    public $baseUrl = null;
    public $dateFrom = '10/06/2005';
    public $dateTo = null;

    public function __construct($config)
    {
        if (isset($config['category']) && $config['category'] != null) {
            $this->setCategory($config['category']);
        }
        if (isset($config['page']) && $config['page'] != null) {
            $this->setPage($config['page']);
        }
    }

    public function setCategory($id)
    {
        $this->category = $id;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setPage($page)
    {
        $this->page = $page;
    }

    public function setFromDate($date)
    {
        $this->dateFrom = $date;
    }

    public function getFromDate()
    {
        if (!$this->dateFrom) {
            return date('d/m/Y',strtotime("-2 month"));
        }
        return $this->dateFrom;
    }

    public function setToDate($date)
    {
        $this->dateTo = $date;
    }

    public function getToDate()
    {
        if (!$this->dateTo) {
            return date('d/m/Y');
        }
        return $this->dateTo;
    }

    public function getUrl()
    {
        if (!$this->url) {
            $this->_setUrl();
        }
        return $this->url;
    }

    /** Protected functions */

    protected function _setUrl()
    {
        $base = $this->_getBaseUrl();

        if (!$base) {
            $this->error = 'Check category & page or table is empty';
            return null;
        }

        //get params
        $params = $this->_getParams();

        $conditions = [];
        foreach ($params as $k => $v) {
            $conditions[] = $k . '=' . $v;
        }

        $url = $base . '?' . implode('&', $conditions);
        $this->url = $url;
    }

    protected function _getBaseUrl()
    {
        $url = null;
        if (!$this->mode) {
            $sql = "SELECT * FROM route WHERE category = ". $this->category ." AND page = '". $this->page ."' AND done = 0 AND active = ". self::ACTIVE ." LIMIT 1";
        } elseif ($this->mode == self::MODE_NEW) {
            $sql = "SELECT * FROM route WHERE category = ". $this->category ." AND page = '". $this->page ."' AND active = ". self::ACTIVE ." ORDER BY last_time LIMIT 1";
        }

        $db = new Query($sql);
        if ($row = mysql_fetch_assoc($db->result)) {
            $url = $row['url'];
        }
        unset($db);
        return $url;
    }

    protected function _getParams() {
        return array();
    }
}