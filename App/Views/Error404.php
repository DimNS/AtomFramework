<?php
/**
* Error404
*
* Класс для представления страницы ошибки 404
*
* @version 0.1 27.04.2015
* @author Дмитрий Щербаков <atomcms@ya.ru>
*/

namespace App\Views;

use App\Configs\Config;
use App\Utility\Template;

class Error404 {
	/**
	* Показать страницу
	*
	* @return null
	*
	* @version 0.1 27.04.2015
	* @author Дмитрий Щербаков <atomcms@ya.ru>
	*/
	static function show() {
		Template::show(['Header']);
		?>
		<!-- Main content -->
		<section class="content light_bg">
			<div class="error-page">
				<h2 class="headline text-yellow">404</h2>
				<div class="error-content">
					<h3><i class="fa fa-warning text-yellow"></i> Ой! Такой страницы нет</h3>
					<p>Вернитесь <a href=<?php echo Config::$global['path_short_root']; ?>/>на главную</a> и попробуйте другой путь</p>
				</div><!-- /.error-content -->
			</div><!-- /.error-page -->
		</section><!-- /.content -->
		<?php
		Template::show(['Footer']);
	}
}
?>