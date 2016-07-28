<?php
require_once(dirname(__FILE__) . '/generic-post-type.php');
require_once(dirname(__FILE__) . '/../../page-type-manager/page-type-manager.php');

/**
* create appetit custom post type
*/
class AppetitCPT extends AppetitGenericPostType
{
	public function meta_box_appetit() {
		global $post;
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}
		$customPostOptions = get_post_meta($post->ID, $this->getPostCustomMeta(), false);
		$pageType = (isset($customPostOptions[0]['pageType'])) ? $customPostOptions[0]['pageType'] : false;	
		$customPostMeta = $this->getPostCustomMeta();
		$pageManager = new PageTypeManager($customPostMeta, $customPostOptions);
		?>

		<!--choose page type-->
		<?php if ($pageType != false): ?>
			<?php $pageManager->execute($pageType); ?>
		<?php else: ?>
			<p>Choose page type:</p>

			<select id="wedx_typeSelect"> 
				<?php $types = $pageManager->getTypes();?>
				<option value="none">Choose page type</option>				     		
				<?php for ($i=0; $i < sizeof($types); $i++):?>
					<option value="<?php echo $types[$i]['type'];?>"><?php echo $types[$i]['displayName'];?></option>
				<?php endfor;?>		
			</select>
			<?php $pageType = $types[0]['type'];?>				
			<input id="pageTypeUI" type="hidden" name="<?php echo $this->getPostCustomMeta();?>[pageType]" value="none" />	
		<?php endif; ?>
		<!--/choose page type-->
		<?php

	}
}

?>