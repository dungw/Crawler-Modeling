<?php
namespace dao;

class Query extends Model_Dao
{
    var $result;
    var $links;
    var $time_slow_log = 0.5;

    function query($query, $file_include_name = "")
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

        $db_select = mysql_select_db($this->_db['name'], $this->links);

        //echo $query;
        $time_start = $this->microtime_float();

        mysql_query("SET NAMES 'utf8'");
        $this->result = mysql_query($query, $this->links);

        $time_end = $this->microtime_float();
        $time = $time_end - $time_start;

        if ($time >= $this->time_slow_log) {

            //Ghi log o file
            $path = $_SERVER['DOCUMENT_ROOT'] . "/log/slow/";
            $filename = date("Y_m_d_H") . "h.txt";

            //Ghi log o file
            $url = $file_include_name;
            if (file_exists($path . $filename)) {

                $str = file_get_contents($path . $filename);
                $str = number_format($time, 10, ".", ",") . " :  " . $query . chr(13) . chr(13) . $str;
                file_put_contents($path . $filename, "Thoi gian : " . date("H:i") . " : " . $url . "--------------------------------------------->" . chr(13) . number_format($time, 10, ".", ",") . " :  " . $str);

            } else {

                file_put_contents($path . $filename, "Thoi gian : " . date("H:i") . " : " . $url . "--------------------------------------------->" . chr(13) . number_format($time, 10, ".", ",") . " :  " . $query);
                @chmod($path . $filename, 0644);
            }

        }
        if (!$this->result) {

            //ghi ra log loi query
            $path = $_SERVER['DOCUMENT_ROOT'] . "/log/error/";
            $filename = date("Y_m_d_H") . "h.txt";
            $str_error = '';
            $str = '';
            $error = "(" . mysql_errno($this->links) . ") " . mysql_error($this->links);

            mysql_close($this->links);

            if (file_exists($path . $filename)) {
                $str = file_get_contents($path . $filename);
            }
            //khai bao ip vao
            $str_error .= "IP:" . $_SERVER['REMOTE_ADDR'] . "Thoi gian : " . date("H:i") . " " . $_SERVER['REQUEST_URI'] . chr(13);
            //khai bao loi file nao
            $str_error .= "Loi o file : " . $file_include_name . chr(13);
            //khai bao loi gi
            $str_error .= "Loi query : " . $error . chr(13);
            //query loi
            $str_error .= "Database : " . $this->_db['name'] . chr(13);

            //query loi
            $str_error .= "Query : " . $query . chr(13);

            $str_error .= "//------------------------------------------------------------------------------------------------->";

            $str_error = $str_error . chr(13) . $str;

            @file_put_contents($path . $filename, $str_error);
            @chmod($path . $filename, 0644);
            if ($_SERVER["SERVER_ADDR"] == "127.0.0.1") echo $query;
            die("Error in query string ");
        }
    }

    //trả về array
    function resultArray()
    {
        $arrayReturn = array();
        while ($row = mysql_fetch_assoc($this->result)) {
            $arrayReturn[] = $row;
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

    //Hàm tính time
    function microtime_float()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }
}