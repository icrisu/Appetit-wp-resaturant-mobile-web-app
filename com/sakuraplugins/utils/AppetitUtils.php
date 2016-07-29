<?php
/**
* utils
*/
class AppetitUtils
{
	//enque required fonts
	public static function enqueAdminFontsFrom($fonts){
		$protocol = is_ssl() ? 'https' : 'http';
		for ($i=0; $i < sizeof($fonts); $i++) { 
			wp_enqueue_style($fonts[$i]['key'], $protocol.$fonts[$i]['resource']);
		}	   		
	}

	//enque required fonts
	public static function enqueFontsFrom($fonts){
		$protocol = is_ssl() ? 'https' : 'http';
		for ($i=0; $i < sizeof($fonts); $i++) { 
			wp_enqueue_style($fonts[$i]['key'], $protocol.$fonts[$i]['resource']);
		}	   		
	}


	//return formated price
	public static function getFormatedPrice($value, $currency, $showAfterPrice) {
		if ($showAfterPrice) {
			return $value . $currency;		
		} else {
			return $currency . $value;			
		}
	}	
}

?>