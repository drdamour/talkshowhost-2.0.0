<?php
require_once("button.php");
require_once("db-connect.php");
require_once("program.php");

	class host extends button{

		function host(){
			$this->button();

		}
	
		
		//needs to be overloads (special case)
		function output(){
			if( isset($_GET['sub'])){
				$this->program($_GET['sub']);
			}
			else{
				$this->base();
			}

		}

		function sub_menu(){
			$db = new db();

			$result = mysql_query("SELECT `title` AS title, `id` AS id FROM `programs` ORDER BY `title`", $db->getLink());

			while($data = mysql_fetch_assoc($result)){
				print("<a href='/main/host/sub/$data[id]/'><div class='sub-button'>$data[title]</div></a>");
			}

		}


		function base(){
			print("Here You will find all the different programs that I have written over my time.  As I write more I'll add them.  Each comes with it's own description and such, along with a download counter<br><br>I'm Always looking for something Neat to Write, so give me some ideas by <a href='/main/talk/sub/mail/'>E-mailing</a> me.");

			for($i = 0; $i < 25; $i++){
				print("<br>");
			}

		}


		function program($id){
			$p = new program($id);

			
			print("<form action='/rateprogram.php' target='_blank' method=post><table>");
			print("<tr><td nowrap align=right valign=top>title:</td><td>$p->title</td></tr>");
			print("<tr><td nowrap align=right valign=top>short-description:</td><td>$p->short</td></tr>");
			print("<tr><td nowrap align=right valign=top>downloads:</td><td><a href='/download.php?id=$id'>here</a></td></tr>");
			print("<tr><td nowrap align=right valign=top>count:</td><td>$p->downloads</td></tr>");
			print("<tr><td nowrap align=right valign=top>rating:</td><td>$p->rating ($p->votes votes) &nbsp;<input type=hidden name='id' value='$id'><select name='rating'><option></option><option value=10>10</option><option value=9>9</option><option value=8>8</option><option value=7>7</option><option value=6>6</option><option value=5>5</option><option value=4>4</option><option value=3>3</option><option value=2>2</option><option value=1>1</option><option value=0>0</option></select> &nbsp;<input type=submit value='rate it'></form></td></tr>");


			print("<tr><td nowrap align=right valign=top>screenshot");
			if(count($p->images) > 1){
				print("s");
			}
			
			print(":</td><td align=center>");
			foreach($p->images as $i){
				print("<br><img src='images/$i->url' alt='$i->caption'><br>$i->caption");
				
			}
			
			print("</td></tr>");

			print("<tr><td nowrap align=right valign=top>compatibility:</td><td>$p->compatibility</td></tr>");

			print("<tr><td nowrap align=right valign=top>install instructoins:</td><td>");
			
			$j = 1;
			foreach($p->install as $i){
				print("$j. $i<br>");
				$j++;
			}
			
			print("</td></tr>");


			print("<tr><td nowrap align=right valign=top>use instructions:</td><td>");
			
			$j = 1;
			foreach($p->use as $i){
				print("$j. $i<br>");
				$j++;
			}
			
			print("</td></tr>");

			print("<tr><td nowrap align=right valign=top>long description:</td><td>$p->long</td></tr>");

			print("</table>");


		}


	}
