<?php

class button{


	var $subbuttons;

	function button(){
		//get all subbuttons
		$this->subbuttons = array_diff(get_class_methods($this), get_class_methods("button"), Array(get_class($this)));

	}

	function sub_menu(){
		$classname = get_class($this);
		foreach($this->subbuttons as $m){
			print("<a href='/main/" . $classname . "/sub/" . $m . "/'><div class='sub-button'>$m</div></a>");
		}
	}


	function output(){
		if(in_array($_GET['sub'], $this->subbuttons)){
			$this->{$_GET['sub']}();
		}
		else{
			$this->{$this->subbuttons[1]}();
		}
	}

}


function cardinal($number){
	$lastdigit = $number % 100 ;
	if($lastdigit == 11){
		return $number."th";
	}
	else if($lastdigit == 12){
		return $number."th";
	}
	else if($lastdigit == 13){
		return $number."th";
	}


	
	$lastdigit = $number % 10;

	if($lastdigit == 1){
		return $number."st";
	}
	else if($lastdigit == 2){
		return $number."nd";
	}
	else if($lastdigit == 3){
		return $number."rd";
	}
	else{
		return $number."th";
	}
}
