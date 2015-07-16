<?php
namespace dao;

require_once $_SERVER['DOCUMENT_ROOT'] . '/dao/Model_Dao.php';
use common\Model_Dao;

class Query extends Model_Dao
{
    var $result;
    var $links;

    function execute($query, $get_last_id = false)
    {
        //Khai bao connect
        $this->links = @mysql_connect($this->_db['server'], $this->_db['username'], $this->_db['password']);

        if (!$this->links) {
            echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
            echo '<meta name="revisit-after" content="1 days">';
            echo "<center>";
            echo "Chào bạn, trang web bạn yêu cầu hiện chưa thể thực hiện được. <br>";
            echo "Xin bạn vui lòng đợi vài giây rồi ấn <b>F5 để Refresh</b> lại trang web <br>";
            echo "</center>";
            exit();
        }

        // select database
        mysql_select_db($this->_db['name'], $this->links);

        //execute the query
        mysql_query("SET NAMES 'utf8'");
        $this->result = mysql_query($query, $this->links);

        //get last id was inserted
        $last_id = 0;
        if ($get_last_id) {
            $dbLast = mysql_query("select LAST_INSERT_ID() as last_id", $this->links);
            if ($row = mysql_fetch_array($dbLast)) {
                $last_id = $row["last_id"];
            }
            return $last_id;
        }

        return $last_id;
    }

    //trả về array
    function resultArray()
    {
        $arrayReturn = array();
        while ($row = mysql_fetch_assoc($this->result)) {
            $arrayReturn[] = $row;
        }
        if (count($arrayReturn) == 1) {
            return $arrayReturn[0];
        }
        return $arrayReturn;
    }

    function close()
    {
        mysql_free_result($this->result);
        if ($this->links) {
            mysql_close($this->links);
        }
    }
}