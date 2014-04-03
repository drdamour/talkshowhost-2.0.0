<?php
	function theader($head){
		return "<td align=center><b>$head</b></td>";
	}


//authentication
	if (!(($_COOKIE['PASS'] == "0512hiphop") || ($_POST['pass'] == "0512hiphop"))) {
		?>
		
		<html>
		<form method=post>
		Password: <input type=password name="pass"/>
		<input type=submit value="log on"/>

		</form>
		</html>

		<?php
		die();
	}
	else{
		
		
		if ($_POST['pass'] == "0512hiphop") setcookie("PASS", $_POST['pass']);
	}
//end authentication


require_once("db-connect.php");
$db = new db();

//actions based on posts
	switch($_POST['option']){
		case 'add category':
			print("Insert of " . $_POST['name'] . " into categories ");

			if(mysql_query("INSERT INTO `links_category` ( `id` , `name` ) VALUES ('', '" . $_POST['name'] . "')", $db->getLink())){
				print("suceeded");
			}
			else{
				print("failed because: " . mysql_error());
			}

			break;

		case 'add link':
			print("Insert of URL: " . $_POST['url'] . " into categories ");

			if(mysql_query("INSERT INTO `links` ( `url` , `title` , `description`, `date` , `category` ) VALUES ('" . $_POST['url'] . "', '" . $_POST['title'] . "', '" . $_POST['description'] . "', NOW() , '" . $_POST['category'] . "')", $db->getLink())){

				$data = mysql_fetch_assoc(mysql_query("SELECT id FROM links WHERE description='$_POST[description]'"));

				//insert rating
				mysql_query("INSERT INTO links_rating (link_id, rating, ip) VALUES ($data[id], $_POST[rating], '35.11.9.52')");

				//update averages
				mysql_query("DELETE FROM links_avg_rating") or die(mysql_error());

				mysql_query("INSERT INTO links_avg_rating (link_id, rating, votes) SELECT link_id, AVG(rating), COUNT(*) FROM links_rating GROUP BY link_id", $db->getLink()) or die(mysql_error());

				print("suceeded");
			}
			else{
				print("failed because" . mysql_error());
			}

			break;

		case 'delete':
			print("<form method=post>You must <input type=hidden name='id' value='" . $_POST['id'] . "' /><input type=submit name='option' value='confirm delete' /></form>");
			break;

		case 'confirm delete':
			if(mysql_query("DELETE FROM links WHERE id = " . $_POST['id'], $db->getLink())){
				print("DELETED IT");
			}
			else{
				print("failed because" . mysql_error());
			}
			break;

		case 'edit':
			print("<div style='border-color: red; border-style: solid; border-width: thin;'>Editing Value: " . $_POST['id']);
			if($result = mysql_query("SELECT url,category,id,date,title,description from links WHERE id = " . $_POST['id'], $db->getLink())){

			$row = mysql_fetch_assoc($result);

			print("<form method=post>URL: <input type=text name='url' value='" . $row['url'] . "' size=50 /><br />TITLE: <input type=text name='title' value='" . $row['title'] . "' size=50 /><br />DESCRIPTION:<br /><textarea name='description' cols=80 rows=4>" . $row['description'] . "</textarea><br />CATEGORIES:<select name='category'>");

			$result = mysql_query("SELECT * FROM links_category ORDER BY name", $db->getLink())
			or die("could not connect: " . mysql_error());

			while($rowb = mysql_fetch_assoc($result)){
				print("<option value='" . $rowb['id'] . "' ");
				if ($rowb['id'] == $row['category'])  (print("SELECTED"));
				print(">" . $rowb['name'] . "</option>");

			}

			print("</select><input type=hidden name='id' value='" . $_POST['id'] . "'/><br />");
			
			/*
			print("RATING:<select name='rating'>");
			
			for($i = 0; $i <= 10; $i++){
				print("<option value='$i' ");
				if ($row['rating'] == $i)  (print("SELECTED"));
				print(">$i</option>");

			}
			
			
			print("</select>");

			*/
			
			print("<input type=submit name='option' value='update' /></form>");

			}
			else{
				print("failed because" . mysql_error());
			}
			print("</div>");
			break;

		case 'update':
			print("update of URL: " . $_POST['url'] . " into categories ");

			if(mysql_query("UPDATE `links` SET `url`='" . $_POST['url'] . "', `title`='" . $_POST['title'] . "', `description`='" . $_POST['description'] . "', `category`='" . $_POST['category'] . "' WHERE id=" . $_POST['id'], $db->getLink())){
				print("suceeded");
			}
			else{
				print("failed because" . mysql_error());
			}
			break;

	}
?>



<form method=post>
<div style="border-color: red; border-style: solid; border-width: thin;">
<u>Add a Link</u><br/>
URL: <input type=text name="url" size=50 value='http://'/><br/>
TITLE: <input type=text name="title" size=50 /><br/>
DESCRIPTION:<br /><textarea name="description" cols=80 rows=4></textarea><br/>
CATEGORY:<select name="category">

<?php
	$result = mysql_query("SELECT * FROM links_category ORDER BY name", $db->getLink())
		or die("could not connect: " . mysql_error());

	while($rowb = mysql_fetch_assoc($result)){
		print("<option value='" . $rowb['id'] . "'>" . $rowb['name'] . "</option>");

	}
?>

</select><br/>

<?php
	print("RATING:<select name='rating'>");
			
	for($i = 0; $i <= 10; $i++){
		print("<option value='$i' ");
		if ($row['rating'] == $i)  (print("SELECTED"));
		print(">$i</option>");
	}
		
	print("</select><br />");

?>

<input type=submit value="add link" name='option'/>
</div>
</form>

<?php
		$result = mysql_query("SELECT a.url,a.id,a.date,a.title,a.description,b.name from links as a LEFT JOIN links_category as b on a.category = b.id ORDER BY b.name,a.title", $db->getLink())
				or die("could not connect: " . mysql_error());

		print("<table border=1>");

		
		print("<tr>");

		print(theader("link"));
		print(theader("category"));
		print(theader("description"));
		print(theader("rating"));
		print(theader("added"));
		print(theader("Edit / Delete"));
		print("</tr>");


		while($row = mysql_fetch_assoc($result)){

			print("<tr");
			//if($row['rating'] == 0) print(" bgcolor=red");
			print(">");

			print("<td><a href='" . $row['url'] . "' target='linkview'>" . $row['title'] . "</a></td>");
			print("<td>" . $row['name'] . "</td>");
			print("<td>" . $row['description'] . "</td>");
			print("<td>" . $row['rating'] . "</td>");
			print("<td>" . $row['date'] . "</td>");
			print("<td><form method=post><input type=hidden name='id' value='" . $row['id'] . "' /><input type=submit name='option' value='edit' /><input type=submit name='option' value='delete'></form></td>");
			print("</tr>");
			
		}
		print("</table>");


?>


<form method=post>
category: <input type=text name="name" />

<input type=submit value="add category" name='option'/>
</form>





</html>