<?php
/**
* appetit generic page
*/
class AppetitGenericPage
{
	protected $customPostMeta;
	protected $customPostOptions;
	protected $data;

	function __construct($customPostMeta, $customPostOptions, $data = null) {
		$this->customPostMeta = $customPostMeta;
		$this->customPostOptions = $customPostOptions;
		$this->data = $data;
	}
}
?>