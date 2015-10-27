<?php
/**
 * Changelog
 *
 * Класс для работы с файлом изменений
 *
 * @version 0.6 27.10.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */

namespace AtomFramework\Controllers;

use AtomFramework\Utility\Func;
use AtomFramework\Models\Changelog as ChangelogModel;

class Changelog {
	/**
	 * Запуск контроллера
	 *
	 * @param array $params Массив маршрута
	 *
	 * @return content
	 *
	 * @version 0.6 27.10.2015
	 * @author Дмитрий Щербаков <atomcms@ya.ru>
	 */
	static function start($params) {
		if (Func::is_login()) {
			switch ($params['action']) {
				case 'check':
					return ChangelogModel::check();
				break;

				default:
					return ChangelogModel::get();
				break;
			}
		} else {
			return \AtomFramework\Controllers\Main::error('info', 'Необходимо войти в систему');
		}
	}
}
?>