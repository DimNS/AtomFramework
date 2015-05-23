<?php
/**
 * Начальные определения страницы
 *
 * @version 0.4 23.05.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */

use App\Configs\Config;
?>
<!DOCTYPE html>

<html>

<head>
	<meta http-equiv="content-language" content="ru">
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<meta name="robots" content="all">

	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

	<meta name="description" content="<?php echo Config::$global['proj_descr']; ?>">

	<meta property="og:title" content="<?php echo Config::$global['proj_name']; ?>">
	<meta property="og:description" content="<?php echo Config::$global['proj_descr']; ?>">
	<meta property="og:image" content="<?php echo Config::$global['path_http_root']; ?>/images/logo-vk.jpg?v=<?php echo md5_file(Config::$global['path_home_root'] . '/images/logo-vk.jpg'); ?>">

	<link rel="icon" href="<?php echo Config::$global['path_short_root']; ?>/favicon.ico?v=<?php echo md5_file(Config::$global['path_home_root'] . '/favicon.ico'); ?>" type="image/x-icon">
	<link rel="shortcut icon" href="<?php echo Config::$global['path_short_root']; ?>/favicon.ico?v=<?php echo md5_file(Config::$global['path_home_root'] . '/favicon.ico'); ?>" type="image/x-icon">

	<!-- Bootstrap 3.3.2 -->
	<link href="<?php echo Config::$global['path_short_root']; ?>/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<!-- Theme style -->
	<link href="<?php echo Config::$global['path_short_root']; ?>/assets/adminlte/css/AdminLTE.min.css" rel="stylesheet" type="text/css">
	<!-- AdminLTE Skins. We have chosen the skin-blue for this starter
		  page. However, you can choose any other skin. Make sure you
		  apply the skin class to the body tag so the changes take effect.
	-->
	<link href="<?php echo Config::$global['path_short_root']; ?>/assets/adminlte/css/skins/skin-blue.min.css" rel="stylesheet" type="text/css">
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	<![endif]-->

	<!-- Bootstrap Tour -->
	<link href="<?php echo Config::$global['path_short_root']; ?>/assets/plugins/bootstrap-tour/bootstrap-tour.min.css" rel="stylesheet" type="text/css">
	<!-- iCheck -->
	<link href="<?php echo Config::$global['path_short_root']; ?>/assets/plugins/iCheck/square/blue.css" rel="stylesheet" type="text/css">
	<!-- Select2 -->
	<link href="<?php echo Config::$global['path_short_root']; ?>/assets/plugins/Select2/css/select2.css" rel="stylesheet" type="text/css">
	<!-- validationEngine -->
	<link href="<?php echo Config::$global['path_short_root']; ?>/assets/plugins/validationEngine/validationEngine.jquery.css" rel="stylesheet" type="text/css">

	<link href="<?php echo Config::$global['path_short_root']; ?>/css/font-awesome/css/font-awesome.css?v=4.2.0" rel="stylesheet" type="text/css">
	<link href="<?php echo Config::$global['path_short_root']; ?>/css/style.css?v=<?php echo Config::$global['version']; ?>" rel="stylesheet" type="text/css">
	<link href="<?php echo Config::$global['path_short_root']; ?>/css/redefinition.css?v=<?php echo Config::$global['version']; ?>" rel="stylesheet" type="text/css">

	<title><?php echo Config::$global['proj_name']; ?></title>
</head>

<body class="skin-blue layout-boxed">
	<div id="container_all">
		<div class="wrapper">