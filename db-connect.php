<?php

require_once("config.php");

class db{
	var $link;



	function db(){
		//required configuration
		global $dburl, $dbname, $dbuser, $dbpass;

		$this->link = mysql_connect($dburl, $dbuser, $dbpass) or die("Could not connect to server: " . mysql_error());


		mysql_select_db($dbname,$this->link) or die("Could not connect to database: " . mysql_error());

	}


	function getLink(){
		return $this->link;
	}

	function close(){
		mysql_close($this->link);
	}

}
