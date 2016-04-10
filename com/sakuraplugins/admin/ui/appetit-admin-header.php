<?php

/**
* 
*/
class AppetitHeader
{
	private $_data;
	function __construct($data = null) {
		$this->data = $data;			
	}

	public function render() {
		?>
		<div class="appetit-admin-header">
			<img src="<?php echo APPETIT_ADMIN_URI . '/img/admin-logo.png'; ?>" alt="logo" />
		</div>
		<?php
	}
}

?>