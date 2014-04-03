<?php
	require_once("whatpulsexml.php");
	
	class whatpulsedisplay{
		function whatpulsedisplay($user, $opponent){
			$this->user = new whatpulsexml($user);
			$this->opponent = new whatpulsexml($opponent);
			
		}

		function getHTML(){
			print( '<form method="post" action="http://www.talkshowhost.net' . $_SERVER[REQUEST_URI] . '"><table border="0" cellspacing="0" class="whatpulse" style="text-align: right;">');

			print("<tr><td>K:</td><td>" . number_format($this->user->TotalKeyCount) . "</td><td>" . $this->getDifference("TotalKeyCount") . "</td></tr>" );

			//print( "<tr><td>C:</td><td>" . $this->user->TotalMouseClicks . "</td><td>" . $this->getDifference("TotalMouseClicks") . "</td></tr>" );

			print( "<tr><td>R:</td><td>" . number_format($this->user->Rank) . "</td><td>" . $this->getDifference("Rank", true) . "</td></tr>" );

			print( '<tr><td>O:</td><td colspan="2"><input style="font-size: 7pt; width: 95%; background-color: #ABABAB; border-color: white; color: white; " type="text" value="' . $this->opponent->AccountName . '" name="what"/></td></tr>');

			print("</table></form>");
		}

		var $user;
		var $opponent;
	


		function getDifference($field, $invert = false){
			if(!$this->opponent->status){
				return "N/A";	
			}
			
			$x = (eregi_replace("[^0-9]", "", $this->user->$field) - eregi_replace("[^0-9]", "", $this->opponent->$field));
		
			if($invert){ $x = $x * -1; }
	
			if($x >= 0){
				return "+" . number_format($x);
			}
			else{
				return number_format($x);
			}
		}
	}
