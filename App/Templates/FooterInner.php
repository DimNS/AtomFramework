<?php
/**
 * Подвал страницы
 *
 * @version 0.1 27.04.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */

use App\Configs\Config;
?>
</div><!-- /.content-wrapper -->

<!-- Main Footer -->
<footer class="main-footer">
	<!-- To the right -->
	<div class="pull-right hidden-xs">
		<a href="http://bestion.ru">Лучшее решение</a>
	</div>

	<!-- Default to the left -->
	<?php echo Config::$global['start_year']; ?> &copy; <?php echo Config::$global['proj_name']; ?> <a href="javascript:;" class="wnd_open" data-id="changelog">v<?php echo Config::$global['version']; ?></a>
</footer>