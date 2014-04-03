<?php
	class timer{
		var $starttime;

		function timer(){
			list($usec, $sec) = explode(" ", microtime()); 
			$this->starttime =  ((float)$usec + (float)$sec); 
		}

		
		function getTime($decimals = 6){
			list($usec, $sec) = explode(" ", microtime());
			$stoptime = ((float)$usec + (float)$sec);

			print( number_format($stoptime - $this->starttime, $decimals));
		}
	}
