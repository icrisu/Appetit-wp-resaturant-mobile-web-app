<?php

/**
* Page Type Manager
*/
class PageTypeManager
{
	
	private $customPostMeta;
	private $customPostOptions;

	function __construct($customPostMeta, $customPostOptions) {
		$this->customPostMeta = $customPostMeta;
		$this->customPostOptions = $customPostOptions;
	}

	private static $availableTypes = array(
		array('type' => 'mobileapp', 'displayName' => 'Appetit Mobile Web App', 'execClass' => 'AppetitMobilePage', 'path' => '/mobile-app/appetit-mobile-page.php'),
		array('type' => 'luna-caffe', 'displayName' => 'Luna Cafe', 'execClass' => 'LunaCafePage', 'path' => '/luna-cafe/luna-cafe.php'),
		array('type' => 'mixto', 'displayName' => 'Mixto', 'execClass' => 'MixtoPage', 'path' => '/mixto/mixto.php')
	);

	//get page types
	public function getTypes() {
		return self::$availableTypes;
	}

	//execute type in admin
	public function execute($type, $isFrontend = false) {
		for ($i=0; $i < sizeof(self::$availableTypes); $i++) { 
			if ($type == self::$availableTypes[$i]['type']) {
				require_once(dirname(__FILE__) . self::$availableTypes[$i]['path']);
				$page = new self::$availableTypes[$i]['execClass']($this->customPostMeta, $this->customPostOptions);
				if ($isFrontend) {
					$page->execRenderFrontend();
				} else {
					$page->execRenderAdmin();
				}				
				break;
			}
		}
	}

}
?>