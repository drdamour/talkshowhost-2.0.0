<?php
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

?>

<html>

<head>
<title>Wishlist Admin Utility</title>
</head>

<body>

<div style='border: solid thin red; padding:5px;'>
<table border='1'>
<tr><td>Title</td><td>Description</td><td>Cost</td><tr>
<tr><td><input type='text' size='40' value='' name='title' /></td><td><textarea rows='6' cols='25' name='description'></textarea></td><td>$<input type='text' size='5' value='' name='cost' /></td><tr>
</table>

<table>
<tr><td/><td>Link URL</td><tr>

<tr><td></td><td></td><td><input type='submit' value='Update' /><input type='submit' value='Delete' /></td><tr>
<tr><td></td><td></td><td><input type='submit' value='add' /></td><tr>

</table>


<div style='border: solid thin red; padding:5px;'>
<table border='1'>
<tr><td>Title</td><td>Description</td><td>Cost</td><td>Links</td><tr>



</table>
</div>

</body>




</html>