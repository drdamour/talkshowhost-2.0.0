<?php
//timer code first
require_once("timer.php");
$timer = new timer();

session_start();


if (!isset($_SESSION['whatname'])) {
	$_SESSION['whatname'] = "atomaka"; 
}

if (isset($_POST['what'])) {
	$_SESSION['whatname'] = $_POST['what'];
}


isset($_GET['button']) ? $button = $_GET['button'] : $button = 'talk';
require_once("./" . $button . ".php");
$data = new $button(); //create the appropriate button

if(ereg("Mozilla/5.0", $_SERVER['HTTP_USER_AGENT'])){

}
else{

}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<link rel="shortcut icon" href="/favicon.ico" />

<link rel="stylesheet" href="/main.css" type="text/css"/>
<link rel="stylesheet" href="/disqus.css" type="text/css"/>

<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="http://www.talkshowhost.net/rss.php" />
<link rel="EditURI" type="application/rsd+xml" title="RSD" href="http://www.talkshowhost.net/rsd.xml" />

<?php print("<title>\"And in the background, everything DaMour saw was gray\"  -  [$button]</title>"); ?>

<script type="text/javascript">

blackback = new Image();
blackback.src = '/images/backgrounds/asukacollagebw.jpg';
colorback = new Image();
colorback.src = '/images/backgrounds/asukacollage.jpg';


function webc(){

	//Main document
	document.body.style.background="url('/images/backgrounds/asukacollage.jpg')";
	<?php print("document.title='\"And in the background, everything DaMour saw was colored\"  -  [$button]';"); ?>

}


function webb() {

	//main document
	document.body.style.background="url('/images/backgrounds/asukacollagebw.jpg')";
	<?php print("document.title='\"And in the background, everything DaMour saw was gray\"  -  [$button]';"); ?>

}


</script>

</head>


<body>

<?
//aim clicks handeler

if (isset($_GET['aim'])){
	require_once("aimstats.php");
	$aim = new aimstats($_GET['aim']);

	$aim->addvisit();

	print("<div style='position: absolute; left: 5px; top: 500px; width: 225px;'>");
	$aim->relative_visits(4);
	print("</div>");

}

	require_once("whatpulsedisplay.php");
	$what = new whatpulsedisplay("drdamour", $_SESSION['whatname']);
	//what pulse
	print("<div>");
	$what->getHTML();
	print("</div>");
?>


<?php
require_once("db-connect.php");
$db = new db();

$query = "SELECT id, title FROM links ORDER BY RAND() LIMIT 1";

$linkdata = mysql_fetch_assoc( mysql_query($query, $db->getLink() ) );


?>
<div class='randomlink'>
<?php print("<a href='/go/$linkdata[id]' target='_blank'><img border='0' src='/images/dice.gif' alt='and your random link is...$linkdata[title]' /></a>"); ?>
</div>

<div class='rssfeed'>
	<a href="/rss.php"><img border="0" src="/images/feed.png"/></a>
</div>

<div id="bw" style="Z-INDEX:10; background-color:black; RIGHT:80px; POSITION:absolute; TOP:0px" onmouseover="webb()">
	<span class='greya'>G</span><span class='greyb'>R</span><span class='greyc'>A</span><span class='greyd'>Y</span>
</div>


<div id="color" style="Z-INDEX:10; background-color:black; RIGHT:0px; POSITION:absolute; TOP:0px" onmouseover="webc()">
	<span class='red'>C</span><span class='yellow'>O</span><span class='blue'>L</span><span class='green'>O</span><span class='purple'>R</span>
</div>

<!-- Start of Flickr Badge -->
<div id="flickrbadge">
	<iframe width="113" height="151" frameborder="0" scrolling="no" src="http://www.flickr.com/apps/badge/badge_iframe.gne?zg_bg_color=999999&zg_person_id=63505810%40N00" title="Flickr Badge"></iframe>
</div>
<!-- End of Flickr Badge -->

<!--Main-->
<div style="position:absolute; left:240px;top:50px;">
	<div class="main">
		<?php $data->output(); ?>
	</div>
</div>

<!--Menu-->
<div class="menu">
	
	<!--TALK button-->
	<a href="/main/talk/">
		<div class="<?php ($button == 'talk') ? print("button-active") : print("button-inactive")?>">
			talk
		</div>
	</a>
	<?php if($button == 'talk'){ $data->sub_menu();} ?>
	
	<!--SHOW button-->
	<a href="/main/show/">
		<div class="<?php ($button == 'show') ? print("button-active") : print("button-inactive")?>">
			show
		</div>
	</a>
	<?php if($button == 'show'){ $data->sub_menu();} ?>
	
	<!--HOST button-->
	<a href="/main/host/">
		<div class="<?php ($button == 'host') ? print("button-active") : print("button-inactive")?>">
			host
		</div>
	</a>
	<?php if($button == 'host'){ $data->sub_menu();} ?>
</div>


<div class='timer'>
generated in <?php $timer->getTime(4); ?> seconds
</div>

</body>
</html>