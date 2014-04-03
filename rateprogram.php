<?php
	require_once("db-connect.php");

	if(isset($_POST['id']) && isset($_POST['rating'])){
		$db = new db();

		$id = $_POST['id'];
		$result = mysql_query("SELECT program_id FROM programs_download WHERE program_id=$id AND ip_address='$_SERVER[REMOTE_ADDR]'", $db->getLink()) or die(mysql_error());

		if(mysql_num_rows($result) > 0){
			mysql_query("INSERT INTO programs_rating (program_id, rating, ip) VALUES ($id, $_POST[rating], '$_SERVER[REMOTE_ADDR]')", $db->getLink()) or die(mysql_error());
			print("Your rating of $_POST[rating] has been recorded");
		}
		else{
			die("You must downloaded the program to rate it, no? Our records show you haven't downloaded it.");
		}
	}
	else{
		die("No ID and/or Rating specified");

	}

