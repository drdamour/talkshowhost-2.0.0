<?php
	
	class whatpulsexml{

		var $status;

		var $GeneratedTime;
		var $UserID;
		var $AccountName;
		var $Country;
		var $DateJoined;
		var $Homepage;
		var $LastPulse;
		var $Pulses;
		var $TotalKeyCount;
		var $TotalMouseClicks;
		var $AvKeysPerPulse;
		var $AvClicksPerPulse;
		var $AvKPS;
		var $AvCPS;
		var $Rank;
		var $TeamID;
		var $TeamName;
		var $TeamMembers;
		var $TeamKeys;
		var $TeamClicks;
		var $TeamDescription;
		var $TeamDateFormed;
		var $RankInTeam;

		function whatpulsexml($user){			
			
			$data = implode("", file("http://whatpulse.org/api/users/" . $user . ".xml"));

			if($data == ''){
				$this->status = false;
				return;
			}

			$parser = xml_parser_create();
			xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
			xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
			xml_parse_into_struct($parser, $data, $values, $tags);
			xml_parser_free($parser);

			for($i = $tags['UserStats'][0] + 1; $i < $tags['UserStats'][1]; $i++){
				$this->$values[$i]['tag'] = $values[$i]['value'];
				//print( $values[$i]['tag'] . " => " . $values[$i]['value'] . "\n");
			}

			$this->status = true;
		}

	}
