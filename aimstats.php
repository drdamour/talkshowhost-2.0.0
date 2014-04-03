<?php
	require_once("db-connect.php");

	class aimstats{
		var $aimdb;
		var $db;
		function aimstats($aim){

			$this->aimdb = strtolower(ereg_replace(" ", "", $aim));

			$this->db = new db();

		}

		function addvisit(){

			//inssert record into database
			mysql_query("INSERT INTO `aim_visitors` (name, time) VALUES ('$this->aimdb', NOW())", $this->db->getLink());

		}

		function topvisits($max = 25){

			$result = mysql_query("SELECT COUNT(*) as total, name AS name FROM `aim_visitors` GROUP BY `name` ORDER BY total DESC, id LIMIT 0,$max", $this->db->getLink()) or die(mysql_error());


			print("<div class='aimstats'>");

			$i = 1;
			print("<table cellspacing=0 cellpadding=2 align=center><tr><td colspan=4 align=center>AIM top $max visitors</td></tr>");
			while($data = mysql_fetch_assoc($result)){
				print("<tr class='");
		
				//if this is the visitor
				if($this->aimdb == $data['name']){
					print("aimvisitor");
				}
				else{
					print("aimnormal");
				}

				print("'><td align=right>");

				print("$i:</td><td><a href='aim:goim?screenname=$data[name]'>$data[name]</a></td><td>$data[total] visits</td><td>[:<a href='?button=show&sub=aimstats&aimuser=$data[name]'>history</a>:]</td>");
		
				print("</tr>");
				$i++;
			}

			print("</table><br></div>\n\n");
	

		}

		//visits of a specific user in list
		function relative_visits($padding = 4){
			$result = mysql_query("CREATE TEMPORARY TABLE aimclicks (id int NOT NULL PRIMARY KEY AUTO_INCREMENT, clicks int NOT NULL, name VARCHAR(255) NOT NULL)") or die (mysql_error());

			$result = mysql_query("INSERT INTO aimclicks (clicks, name) SELECT COUNT(*) as total, name AS name FROM `aim_visitors` GROUP BY `name` ORDER BY total DESC,id") or die (mysql_error());

			$result = mysql_query("SELECT id, clicks, name FROM `aimclicks` WHERE name='$this->aimdb'");

			//check to make sure there is no 
			if(mysql_num_rows($result) != 1){
				return;
			}

			$data = mysql_fetch_assoc($result);

			$top = $data['id'] - $padding;
			$bot = $data['id'] + $padding;

			$query = "(SELECT id, clicks, name FROM `aimclicks` WHERE id=1)";

			$query .= " UNION ";

			$query .= "(SELECT id, clicks, name FROM `aimclicks` WHERE (id > $top) AND (id < $bot) ORDER BY id)";

			$query .= " UNION ";

			$query .= "(SELECT id, clicks, name FROM `aimclicks` ORDER BY id DESC LIMIT 1)";

			$result = mysql_query($query);
			
			$total = mysql_num_rows($result);

			print("<div class='aimstats' style='width: 100%; align: center;' >");

			print("<table align=center cellspacing=0><tr><td colspan=4 align=center>relative stats</td></tr>");


			for($i = ($total); $i > 0; $i--){

				//store the last id for records sake
				$lastid = $data['id'];

				//fetch the new id
				$data = mysql_fetch_assoc($result);

				//if this is the second loop, and the id after this is not 1 ahead, than there should be a gap
				if( ($i == ($total-1)) && ( ($lastid+1) != $data['id']) ){
					print("<tr><td align=center colspan=4>::::::::::::::::::::::::::::::::::::::::</td></tr>");
				}

				//if this is the last loop (the lowest amount of clicks) and the id before this was NOT 1 behind, then there should be a gap
				if(($i == 1) && (($lastid+1) != $data['id'])){
					print("<tr><td align=center colspan=4>::::::::::::::::::::::::::::::::::::::::</td></tr>");	
				}

				print("<tr class='");
		
				//if this is the visitor
				if($this->aimdb == $data['name']){
					print("aimvisitor");
				}
				else{
					print("aimnormal");
				}

				print("'><td align=right>");

				print("$data[id]:</td><td><a href='aim:goim?screenname=$data[name]'>$data[name]</a></td><td>$data[clicks] visits</td><td>[:<a href='?button=show&sub=aimstats&aimuser=$data[name]'>i</a>:]</td>");
		
				print("</tr>");

			}


			print("</table><br></div>\n\n");

		}

		function history($max = 25){
			$result = mysql_query("SELECT (UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(time)) AS seconds, DATE_FORMAT(time, '%b %d %Y (%r)') AS 'date', (TO_DAYS(NOW()) - TO_DAYS(time)) AS days FROM `aim_visitors` WHERE name='$this->aimdb' ORDER BY time DESC LIMIT 0,$max", $this->db->getLink()) or die(mysql_error());


			print("<div class='aimstats' style='width: 100%; align: center;' >");

			
			if(mysql_num_rows($result) > 0){
				$i = 1;
				print("<table align=center><tr><td colspan=4 align=center>site history of user <a 	href='aim:goim?screenname=$this->aimdb'>$this->aimdb</a></td></tr>");
				while($data = mysql_fetch_assoc($result)){
					print("<tr class='");
		
					print("'><td>");

					print("visited </td><td align=right><span class='aimseconds' ><acronym title='$data[days] days ago'>$data[seconds]</acronym></span></td><td> seconds ago <span class='aimdate' >[$data[date]]</span></td>");
		
					print("</tr>");
					$i++;
				}
			print("</table><br>");
			}
			else{//not found
				print("<br><div align=center>user <a href='aim:goim?screenname=$this->aimdb'>$this->aimdb</a> not found</div><br>");
			}

			print("</div>\n\n");

		}

		function recent($max = 25){
			

			$result = mysql_query("SELECT  (UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP( max(time) ))  AS seconds,  (TO_DAYS(NOW()) - TO_DAYS( max(time) ))  AS days, DATE_FORMAT( max(time), '%b %d %Y (%r)') AS 'date', name FROM `aim_visitors` GROUP BY name ORDER BY seconds ASC LIMIT 0,$max", $this->db->getLink()) or die(mysql_error());


			print("<div class='aimstats' style='width: 100%; align: center;' >");

			
			$i = 1;
			print("<table align=center><tr><td colspan=5 align=center>AIM last $max visitors</td></tr>");
			while($data = mysql_fetch_assoc($result)){
				print("<tr class='");
		
				print("'><td align='right'>");

				print("$i:</td><td><a href='aim:goim?screenname=$data[name]'>$data[name]</a></td><td align=right><span class='aimseconds' ><acronym title='$data[days] days ago: $data[date]'>$data[seconds]</acronym></span></td><td>seconds ago</td><td>[:<a href='?button=show&sub=aimstats&aimuser=$data[name]'>history</a>:]</td>");
		
				print("</tr>");
				$i++;
			}
			print("</table><br>");
			
			
			print("</div>\n\n");

		}


	}
