<?php
	require_once("db-connect.php");

	if(isset($_GET['id'])){
		$db = new db();
		$id = $_GET['id'];

		$result = mysql_query("SELECT url FROM programs WHERE id=$id", $db->getLink());
		

		if(mysql_num_rows($result) == 1){
			$data = mysql_fetch_assoc($result);

			mysql_query("INSERT INTO programs_download (program_id, ip_address, time) VALUES ($id, '$_SERVER[REMOTE_ADDR]', NOW())", $db->getLink()) or die(mysql_error());

			header("Location:  downloads/$data[url]");

		}
		else{
			die("The ID Specified ($id) was INVALID");
		}

	}
	else{
		die("No ID specified");
	}
