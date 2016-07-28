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

	//get label fields
	public static function getLabelsFields() {
		return array(
			array(
				'field' => 'menu_cat_label',
				'admin_title' => 'Menu categories label',
				'default_val' => 'Menu categories'
			),
			array(
				'field' => 'menu_saved_orders_label',
				'admin_title' => 'Saved orders label',
				'default_val' => 'Saved orders'
			),
			array(
				'field' => 'menu_no_opened_orders_label',
				'admin_title' => 'No opened orders label',
				'default_val' => 'There are no opened orders'
			),
			array(
				'field' => 'priceLabel',
				'admin_title' => 'Price label',
				'default_val' => 'Price'
			),
			array(
				'field' => 'priceLabelTotal',
				'admin_title' => 'Total price label',
				'default_val' => 'Total'
			),
			array(
				'field' => 'saveLabel',
				'admin_title' => 'Save to order label',
				'default_val' => 'Save'
			),
			array(
				'field' => 'noSavedOrders',
				'admin_title' => 'There are no opened orders label',
				'default_val' => 'There are no opened orders'
			),
			array(
				'field' => 'openedOrder',
				'admin_title' => 'Opened order label',
				'default_val' => 'Opened order'
			),
			array(
				'field' => 'pastOrders',
				'admin_title' => 'Past orders label',
				'default_val' => 'Past orders'
			),			
			array(
				'field' => 'closeOrder',
				'admin_title' => 'Close order label',
				'default_val' => 'Close order'
			),
			array(
				'field' => 'confirmDeleteOrder',
				'admin_title' => 'Confirm delete order label',
				'default_val' => 'Are you sure you want to delete this order?'
			)						
		);
	} 
}
?>