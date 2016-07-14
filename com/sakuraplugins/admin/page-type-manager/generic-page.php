<?php
/**
* appetit generic page
*/
class AppetitGenericPage
{
	protected $customPostMeta;
	protected $customPostOptions;

	function __construct($customPostMeta, $customPostOptions) {
		$this->customPostMeta = $customPostMeta;
		$this->customPostOptions = $customPostOptions;
	}
}
?>