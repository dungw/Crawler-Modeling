<?php
/**
 * Created by PhpStorm.
 * User: dung.vuong
 * Date: 7/14/2015
 * Time: 1:19 PM
 */
namespace common;

abstract class Model_Dao
{
    protected $_iniPath = '../config/';
    protected $_db;

    public function __construct()
    {
        $ini = parse_ini_file($this->_iniPath . 'db.ini');
        $this->_db = $ini['database'];
    }

}