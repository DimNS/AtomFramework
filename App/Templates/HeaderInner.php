<?php
/**
 * Шапка страницы
 *
 * @version 0.7.0 14.11.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */

use AtomFramework\Configs\Config;
use AtomFramework\Utility\Template;
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
                <li id="userinfo_drop_menu" class="dropdown">
                    <a href="<?php echo Config::$global['path_short_root']; ?>/user/signout">
                        <i class="fa fa-sign-out"></i>
                        <span>&nbsp;Выход</span>
                    </a>
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
            <li<?php print(Config::$global['route_controller'] === 'main' ? ' class="active"' : ''); ?>><a href="<?php echo Config::$global['path_short_root']; ?>/"><span>Главная</span></a></li>
            <li<?php print(Config::$global['route_controller'] === 'user' ? ' class="active"' : ''); ?>><a href="<?php echo Config::$global['path_short_root']; ?>/user/profile"><span>Мой профиль</span></a></li>
        </ul><!-- /.sidebar-menu -->
    </section><!-- /.sidebar -->
</aside>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <?php
    Template::show(['Alerts']);
    ?>