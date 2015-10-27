<?php
/**
 * Main
 *
 * Класс для представления главной страницы
 *
 * @version 0.6 27.10.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */

namespace AtomFramework\Views;

use AtomFramework\Utility\Func;
use AtomFramework\Utility\Template;

class Main {
	/**
	 * Показать Landing
	 *
	 * @param array $data Массив данных
	 *
	 * @return null
	 *
	 * @version 0.1 27.04.2015
	 * @author Дмитрий Щербаков <atomcms@ya.ru>
	 */
	static function landing($data) {
		Template::show(['Header', 'AuthLogin', 'Footer']);
	}

	/**
	 * Показать Dashboard
	 *
	 * @param array $data Массив данных
	 *
	 * @return null
	 *
	 * @version 0.1 27.04.2015
	 * @author Дмитрий Щербаков <atomcms@ya.ru>
	 */
	static function dashboard($data) {
		Template::show(['Header', 'HeaderInner']);
		?>
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1>
				Главная
				<small>сводная информация</small>
			</h1>
		</section>

		<!-- Main content -->
		<section class="content">
			<?php
			Func::debug($data);
			?>
		</section><!-- /.content -->
		<?php
		Template::show(['FooterInner', 'Footer']);
	}
}
?>