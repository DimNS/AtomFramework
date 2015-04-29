<?php
/**
 * Шапка страницы
 *
 * @version 0.1 27.04.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */

use App\Configs\Config;
use App\Utility\Template;
?>
<!-- Main Header -->
<header class="main-header">
	<!-- Logo -->
	<a href="<?php echo Config::$global['path_short_root']; ?>/" class="logo"><?php echo Config::$global['proj_name']; ?></a>

	<!-- Header Navbar -->
	<nav class="navbar navbar-static-top" role="navigation">
		<!-- Sidebar toggle button-->
		<a href="javascript:;" class="sidebar-toggle" data-toggle="offcanvas" role="button">
			<span class="sr-only">Меню</span>
		</a>

		<div class="navbar-custom-menu">
			<ul class="nav navbar-nav">
				<!-- User Account: style can be found in dropdown.less -->
				<li class="dropdown user user-menu">
					<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
						<i class="fa fa-user"></i>
						<span class="hidden-xs userinfo_name"><?php echo Config::$userinfo['name']; ?></span>
					</a>

					<ul class="dropdown-menu">
						<!-- User image -->
						<li class="user-header">
							<i class="fa fa-user"></i>
							<p class="userinfo_name"><?php echo Config::$userinfo['name']; ?></p>
						</li>

						<!-- Menu Footer-->
						<li class="user-footer">
							<div class="pull-left">
								<a href="<?php echo Config::$global['path_short_root']; ?>/user/profile" class="btn btn-default">Профиль</a>
							</div>
							<div class="pull-right">
								<a href="<?php echo Config::$global['path_short_root']; ?>/user/signout" class="btn btn-default">Выход</a>
							</div>
						</li>
					</ul>
				</li>
			</ul>
		</div>
	</nav>
</header>

<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
	<!-- sidebar: style can be found in sidebar.less -->
	<section class="sidebar">
		<!-- Sidebar Menu -->
		<ul class="sidebar-menu">
			<li class="header">НАВИГАЦИЯ</li>
			<!-- Optionally, you can add icons to the links -->
			<li<?php print(Config::$global['route_controller'] == 'main' ? ' class="active"' : ''); ?>><a href="<?php echo Config::$global['path_short_root']; ?>/"><span>Главная</span></a></li>
			<li<?php print(Config::$global['route_controller'] == 'user' ? ' class="active"' : ''); ?>><a href="<?php echo Config::$global['path_short_root']; ?>/user/profile"><span>Мой профиль</span></a></li>
		</ul><!-- /.sidebar-menu -->
	</section><!-- /.sidebar -->
</aside>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<?php
	Template::show(['Alerts']);
	?>