<?php

/**
* options
*/
class AppetitOptions
{
	//get admin fonts	
	public static function getAdminFonts() {
		return array(
			array('key'=>'roboto', 'resource'=>'://fonts.googleapis.com/css?family=Roboto:400,300'),
			array('key'=>'sk-opensans', 'resource'=>'://fonts.googleapis.com/css?family=Open+Sans:400,800,300,600')
		);
	} 
}
?>