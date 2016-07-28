<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset');?>">
     

    <!-- Mobile Specific Metas
  ================================================== -->     
    <meta name="viewport" content="width=device-width, initial-scale=1.0">   

    <!-- pinback
  ================================================== -->        	
  <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />  

    <!-- Favicon
  ================================================== -->  
  <link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.ico" />

  <?php wp_head(); ?>
  <style type="text/css">
  body { 
    margin: 0;
    padding: 0;
    background-image: url('<?php echo APPETIT_FRONT_URI; ?>/img/patterns/dark_embroidery.png');
    background-repeat: repeat;
  }  
  </style>
</head>

<body <?php body_class(); ?>>