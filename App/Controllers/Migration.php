<?php
/**
 * Migration
 *
 * Класс для применения изменений в БД
 *
 * @version 1 14.04.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */

namespace App\Controllers;

use App\Configs\Config;

class Migration {
	/**
	 * Запуск контроллера
	 *
	 * @param array $params Массив маршрута
	 *
	 * @return content
	 *
	 * @version 1 14.04.2015
	 * @author Дмитрий Щербаков <atomcms@ya.ru>
	 */
	static function start($params) {
		if ($params['params']['token'] == Config::$global['db_migration_token']) {
			switch ($params['action']) {
				case 'install': // Первичное развертывание системы, или переразвертывание на начало
				case 'change': // Применение изменений
					$result = call_user_func(['\\App\\Models\\Migration', $params['action']]);

					if (Config::$global['production'] AND $result['data_success'] == $result['data_count']) {
						// Все запросы успешно выполнены, удаляем файл
						@unlink(Config::$global['path_home_root'] . '/' . $params['action'] . '.sql');
					}

					return \App\Views\Migration::show($result);
				break;

				default:
					return \App\Controllers\Error404::start();
				break;
			}
		} else {
			return \App\Controllers\Main::error('info', 'Неверный или отсутствующий ключ');
		}
	}
}
?>