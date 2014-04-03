<?php
	class option{
		function option($text, $value){
			$this->myValue = $value;
			$this->myText = $text;
			$this->mySelected = false;
		}

		function select(){
			$this->mySelected = true;
		}

		function getValue(){
			return $this->myValue;
		}

		var $myValue;
		var $myText;
		var $mySelected;

		function getHTML(){
			$str = "<option ";
			if($this->mySelected){
				$str .= "selected ";
			}
			$str .= "value='" . $this->myValue . "'>" . $this->myText . "</option>";

			return $str;
		}
	
	}

	class select{
		function select($name, $defaultValue){

			$this->myOptions = Array();
			$this->myValue = $defaultValue;
			$this->setName($name);

		}

		function setName($name){
			$this->myName = $name;

			if( isset($_GET[$name]) ){
				$this->myValue = $_GET[$name];
			}
			else if( isset($_POST[$name]) ){
				$this->myValue = $_POST[$name];
			}
		}

		function addOption($option){
			if( is_a($option, "option") ){
				array_push($this->myOptions, $option);
			}

		}
		
		function getHTML(){
			$str = "<select name='" . $this->myName . "'>";
			for($i = 0; $i < count($this->myOptions); $i++){
				if($this->myOptions[$i]->getValue() == $this->myValue){
					$this->myOptions[$i]->select();
				}
				$str .= $this->myOptions[$i]->getHTML();
			}
			$str .= "</select>";

			return $str;
		}

		function getValue(){
			return $this->myValue;
		}

		var $myOptions;
		var $myValue;
		var $myName;

	}

