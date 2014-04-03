<?php
	require_once("db-connect.php");

	if(!isset($_GET['id'])){ die("No Link Specified"); }

	$db = new db();

	$result = mysql_query("SELECT url FROM links WHERE id = ". $_GET['id'], $db->getLink()) or die("could not connect: " . mysql_error());

	//need to check if something showed up or not.
	if(mysql_num_rows($result) != 1){
		die("Sorry $_GET[id], is not a valid link id");	
	}


	$row = mysql_fetch_assoc($result);


	$row['clicks']++;
	mysql_query("INSERT INTO links_clicks (`link_id`, `time`, `ip`) VALUES ('$_GET[id]', now(), '$_SERVER[REMOTE_ADDR]')", $db->getLink())
		or die("could not connect: " . mysql_error());

	$db->close();

	header("Location: $row[url]");
