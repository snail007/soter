<?php

class MyClass {
	public function __construct() {
		echo '<br>My Library __construct from:'.__FILE__.'<br>';
	}
	public function callTest(){
		echo '<br>called '.__METHOD__.':'.__FILE__.'<br>';
	}
}
