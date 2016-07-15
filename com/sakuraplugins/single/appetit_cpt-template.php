<?php
//sigle post template
require_once(dirname(__FILE__) . '/../plugin-core.php');
require_once(dirname(__FILE__) . '/../page-type-manager/page-type-manager.php');

$postID = get_the_ID();

$customPostMeta = AppetitCore::APPETIT_CPT_META;
$customPostOptions = get_post_meta($postID, $customPostMeta, false);
$pageType = (isset($customPostOptions[0]['pageType'])) ? $customPostOptions[0]['pageType'] : false;
if ($pageType) {	
	$pageManager = new PageTypeManager($customPostMeta, $customPostOptions);
	$pageManager->execute($pageType, true);
} else {
	echo 'Undefined page type!';
}
?>