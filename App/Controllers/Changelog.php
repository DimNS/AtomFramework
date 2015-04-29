<?php
/**
 * Changelog
 *
 * Класс для работы с файлом изменений
 *
 * @version 0.1 27.04.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */

namespace App\Controllers;

use App\Utility\Func;

class Changelog {
	/**
	 * Запуск контроллера
	 *
	 * @param array $params Массив маршрута
	 *
	 * @return content
	 *
	 * @version 0.1 27.04.2015
	 * @author Дмитрий Щербаков <atomcms@ya.ru>
	 */
	static function start($params) {
		if (Func::is_login()) {
			switch ($params['action']) {
				case 'check':
					return \App\Models\Changelog::check();
				break;

				default:
					return \App\Controllers\Error404::start();
				break;
			}
		} else {
			return \App\Controllers\Main::error('info', 'Необходимо войти в систему');
		}
	}
}
?>