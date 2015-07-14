<?php
class db_init {
	var $server;
	var $username;
	var $password;
	var $database;
	function db_init()
	{
		// Khai bao Server o day
        $this->server	  = "localhost";
		$this->username   = "root";
		$this->password   = "";
		$this->database   = "crawler_muasamcong";

	}
	function __destruct()
	{
		unset($this->server);
		unset($this->username);
		unset($this->password);
		unset($this->database);
	}
}

class db_query 
{
	var $result;
	var $links;
	var $time_slow_log = 0.5;
	
	function db_query($query,$file_include_name = "")
	{
	
		$dbinit = new db_init();
		//Khai bao connect
		$this->links = @mysql_connect($dbinit->server, $dbinit->username, $dbinit->password);
		
		if(!$this->links){
			echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
			echo '<meta name="revisit-after" content="1 days">';
			echo "<center>";
			echo "Chào bạn, trang web bạn yêu cầu hiện chưa thể thực hiện được. <br>";
			echo "Xin bạn vui lòng đợi vài giây rồi ấn <b>F5 để Refresh</b> lại trang web <br>";
			echo "</center>";
			exit();
		}
		
		$db_select = mysql_select_db($dbinit->database,$this->links);
		
		//echo $query;
		$time_start = $this->microtime_float();
		
		mysql_query("SET NAMES 'utf8'");
		$this->result = mysql_query($query,$this->links);
		
		$time_end = $this->microtime_float();
		$time = $time_end - $time_start;
		
		if ($time >= $this->time_slow_log){
		
			 //Ghi log o file
			 $path = $_SERVER['DOCUMENT_ROOT'] . "/log/slow/";
			 $filename = date("Y_m_d_H") . "h.txt";
		
			 //Ghi log o file
			 $url = $file_include_name;
			 if(file_exists($path . $filename)){
			 
			 	$str = file_get_contents($path . $filename);
				$str = number_format($time,10,".",",") . " :  " . $query . chr(13) . chr(13) . $str;
				file_put_contents($path . $filename,"Thoi gian : " . date("H:i") . " : " . $url . "--------------------------------------------->" . chr(13) . number_format($time,10,".",",") . " :  " . $str);
			 
			 }else{
			 
			 	file_put_contents($path . $filename,"Thoi gian : " . date("H:i") . " : " . $url . "--------------------------------------------->" . chr(13) . number_format($time,10,".",",") . " :  " . $query);
				@chmod($path . $filename,0644);
			 
			 }
		
		
		}		
		if (!$this->result)
		{
			//ghi ra log loi query
			$path 			= $_SERVER['DOCUMENT_ROOT'] . "/log/error/";
			$filename 		= date("Y_m_d_H") . "h.txt";
			$str_error 		= '';
			$str 				= '';
			$error 			= "(" . mysql_errno($this->links) . ") " . mysql_error($this->links);
			
			mysql_close($this->links);	
			
			 
			 if(file_exists($path . $filename)){
				$str = file_get_contents($path . $filename);
			 
			}
			//khai bao ip vao
			$str_error .= "IP:" .  $_SERVER['REMOTE_ADDR'] . "Thoi gian : " . date("H:i") . " " . $_SERVER['REQUEST_URI'] .chr(13);
			//khai bao loi file nao
			$str_error .= "Loi o file : " . $file_include_name . chr(13);
			//khai bao loi gi
			$str_error .= "Loi query : " . $error . chr(13);
			//query loi 
			$str_error .= "Database : " . $dbinit->database . chr(13);

			//query loi 
			$str_error .= "Query : " . $query . chr(13);
			
			$str_error .= 	"//------------------------------------------------------------------------------------------------->";
			
			$str_error =  $str_error . chr(13) . $str;
			
			@file_put_contents($path . $filename,$str_error);
			@chmod($path . $filename,0644);
			if($_SERVER["SERVER_ADDR"] == "127.0.0.1") echo $query;
			die("Error in query string ");
		}
		unset($dbinit);
		
	}
	
	//trả về array
	function resultArray(){
		
		$arrayReturn = array();
		while($row = mysql_fetch_assoc($this->result)){
			$arrayReturn[] = $row;
		}
		
		return $arrayReturn;
	}
	function close()
	{
		mysql_free_result($this->result); 
		if ($this->links) 
		{  
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

class db_execute 
{
	var $links;
	var $total = 0;
	function db_execute($query, $utf8=1)
	{
		$dbinit = new db_init();
		
		$this->links = mysql_connect($dbinit->server, $dbinit->username, $dbinit->password);
		mysql_select_db($dbinit->database);
		
		unset($dbinit);
		mysql_query("SET NAMES 'utf8'");
		mysql_query($query);
		
		//kiểm tra thành công hay chưa
		$str = strtolower(substr(trim($query), 0, 6));
		$this->total = mysql_affected_rows();
		mysql_close($this->links);	
	}
}

class db_count{
	var $total;
	function db_count($sql){
		$db_ex = new db_query($sql);
		if($row = mysql_fetch_assoc($db_ex->result)){
			$this->total = intval($row["count"]);
		}else{
			$this->total = 0;
		}
		$db_ex->close();
		unset($db_ex);
		return $this->total;
	}
}

class db_execute_return 
{
	var $links;
	var $result;
	var $total = 0;

	function db_execute($query)
	{
		$dbinit = new db_init();
		$this->links = mysql_connect($dbinit->server, $dbinit->username, $dbinit->password);
		mysql_select_db($dbinit->database);
		
		unset($dbinit);
		mysql_query("SET NAMES 'utf8'");
		mysql_query($query);
		$this->total = mysql_affected_rows();
		$last_id = 0;
		$this->result = mysql_query("select LAST_INSERT_ID() as last_id",$this->links);
		
		if($row=mysql_fetch_array($this->result)){
			$last_id = $row["last_id"];
		}
		
		mysql_close($this->links); 
		return $last_id;
	}
}
