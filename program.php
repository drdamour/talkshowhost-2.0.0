<?php
	require_once("db-connect.php");
	
	class image{

		var $url;
		var $caption;

		function image($u, $cap){
			$this->caption = $cap;
			$this->url = $u;
		}
	}

	class program{
		var $title;
		var $short;
		var $long;

		var $downloads;

		var $use;

		var $images;

		var $install;

		var $rating;
		var $votes;

		var $compatibility;

		function program($id){
			$db = new db();
			$result = mysql_query("SELECT COUNT(p.id) AS 'count', p.compatibility AS comp, p.title AS title, p.short AS 'short', p.long AS 'long' FROM programs AS p LEFT JOIN programs_download AS pd ON p.id = pd.program_id WHERE `id`='$id' GROUP BY pd.program_id", $db->getLink()) or die(mysql_error());

			$data = mysql_fetch_assoc($result);

			$this->title = $data['title'];
			$this->short = $data['short'];
			$this->long = $data['long'];
			$this->downloads = $data['count'];
			$this->compatibility = $data['comp'];


			$result = mysql_query("SELECT url AS url, caption AS caption FROM programs_images WHERE program_id=$id", $db->getLink()) or die(mysql_error());

			$this->images = Array();

			while($data = mysql_fetch_assoc($result)){
				array_push($this->images, new image($data['url'], $data['caption']));
			}

			$result = mysql_query("SELECT instruction AS instruction FROM programs_instructions WHERE type='install' AND program_id=$id ORDER BY step", $db->getLink()) or die(mysql_error());

			$this->install = Array();

			while($data = mysql_fetch_assoc($result)){
				array_push($this->install, $data['instruction']);
			}


			$result = mysql_query("SELECT instruction AS instruction FROM programs_instructions WHERE type='use' AND program_id=$id ORDER BY step", $db->getLink()) or die(mysql_error());

			$this->use = Array();

			while($data = mysql_fetch_assoc($result)){
				array_push($this->use, $data['instruction']);
			}

			$result = mysql_query("SELECT AVG(rating) AS 'rating', COUNT(*) as 'count' FROM programs_rating WHERE program_id=$id GROUP BY program_id", $db->getLink()) or die(mysql_error());

			$data = mysql_fetch_assoc($result);

			if($data['rating'] == ""){
				$this->rating = "none";
				$this->votes = 0;
			}
			else{
				$this->rating = $data['rating'];
				$this->votes = $data['count'];
			}
		}
	}

