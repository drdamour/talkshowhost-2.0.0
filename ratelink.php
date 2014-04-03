<?php
	require_once("db-connect.php");

	if(isset($_POST['id']) && isset($_POST['rating'])){
		$db = new db();

		$id = $_POST['id'];
		$result = mysql_query("SELECT link_id FROM links_clicks WHERE link_id=$id AND ip='$_SERVER[REMOTE_ADDR]'", $db->getLink()) or die(mysql_error());

		if(mysql_num_rows($result) > 0){
			mysql_query("INSERT INTO links_rating (link_id, rating, ip) VALUES ($id, $_POST[rating], '$_SERVER[REMOTE_ADDR]')", $db->getLink()) or die(mysql_error());

			mysql_query("DELETE FROM links_avg_rating") or die(mysql_error());

			mysql_query("INSERT INTO links_avg_rating (link_id, rating, votes) SELECT link_id, AVG(rating), COUNT(*) FROM links_rating GROUP BY link_id", $db->getLink()) or die(mysql_error());

			print("Your rating of $_POST[rating] has been recorded");
		}
		else{
			die("You must visit the link to rate it, no? Our records show you haven't visited it.");
		}
	}
	else{
		die("No ID and/or Rating specified");

	}


