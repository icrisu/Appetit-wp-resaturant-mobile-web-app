<!doctype html>
<html class="no-js" lang="">
    <head>

	    <title><?php echo get_the_title(get_the_ID());?></title>
	    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0, minimal-ui">
	    <meta name="apple-mobile-web-app-capable" content="yes" />
	    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
	    <link rel="stylesheet" type="text/css" href="<?php echo APPETIT_FRONT_URI . '/afui/'; ?>build/icons.css" />

	    <link rel="stylesheet" type="text/css" href="<?php echo APPETIT_FRONT_URI . '/afui/'; ?>build/af.ui.css" />

	    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,400italic,300,300italic' rel='stylesheet' type='text/css' />

	    <link rel="stylesheet" type="text/css" href="<?php echo APPETIT_FRONT_URI . '/fonts/'; ?>style.css" />

	    <link rel="stylesheet" type="text/css" href="<?php echo APPETIT_FRONT_URI . '/afui/'; ?>style.css" />

	    <script type="text/javascript" charset="utf-8" src="<?php echo APPETIT_FRONT_URI . '/afui/'; ?>jquery/jqueryv2.1.1.js"></script>

	    <script type="text/javascript" charset="utf-8" src="<?php echo APPETIT_FRONT_URI . '/afui/'; ?>app.js"></script>	    



	    <script type="text/javascript" charset="utf-8" src="<?php echo APPETIT_FRONT_URI . '/afui/'; ?>fastclick.js"></script>
	    <script type="text/javascript" charset="utf-8" src="<?php echo APPETIT_FRONT_URI . '/afui/'; ?>src/af.shim.js"></script>
	    <script type="text/javascript" charset="utf-8" src="<?php echo APPETIT_FRONT_URI . '/afui/'; ?>src/af.ui.js"></script>


	    <script src="<?php echo APPETIT_FRONT_URI . '/afui/'; ?>src/af.desktopBrowsers.js"></script>
	    <script type="text/javascript" charset="utf-8" src="<?php echo APPETIT_FRONT_URI . '/afui/'; ?>src/af.actionsheet.js"></script>
	    <script type="text/javascript" charset="utf-8" src="<?php echo APPETIT_FRONT_URI . '/afui/'; ?>src/af.animation.js"></script>
	    <script type="text/javascript" charset="utf-8" src="<?php echo APPETIT_FRONT_URI . '/afui/'; ?>src/af.touchEvents.js"></script>
	    <script type="text/javascript" charset="utf-8" src="<?php echo APPETIT_FRONT_URI . '/afui/'; ?>src/af.popup.js"></script>
	    <script type="text/javascript" charset="utf-8" src="<?php echo APPETIT_FRONT_URI . '/afui/'; ?>src/af.drawer.js"></script>
	    <script type="text/javascript" charset="utf-8" src="<?php echo APPETIT_FRONT_URI . '/afui/'; ?>src/af.toast.js"></script>
	    <script type="text/javascript" charset="utf-8" src="<?php echo APPETIT_FRONT_URI . '/afui/'; ?>src/af.animateheader.js"></script>
	    <script type="text/javascript" charset="utf-8" src="<?php echo APPETIT_FRONT_URI . '/afui/'; ?>src/af.splashscreen.js"></script>
	    <script type="text/javascript" charset="utf-8" src="<?php echo APPETIT_FRONT_URI . '/afui/'; ?>src/af.swipereveal.js"></script>
	    <script type="text/javascript" charset="utf-8" src="<?php echo APPETIT_FRONT_URI . '/afui/'; ?>src/af.lockscreen.js"></script>
	    <script>

	    $.afui.useOSThemes=false;
	    $.afui.loadDefaultHash=true;
	    $.afui.autoLaunch=false;
	    var AppetitMobile;

	    $(document).ready(function() {
	        $.afui.launch();
	        AppetitMobile = new AppetitMobileApp();
	        AppetitMobile.init();
	    });

	    </script>
    </head>
    <body>

