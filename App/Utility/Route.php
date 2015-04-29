<?php
/**
 * Route
 *
 * Класс для работы с маршрутами
 *
 * @version 0.1 27.04.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */

namespace App\Utility;

use App\Configs\Config;

class Route {
	/**
	 * Запускаем разбор маршрута
	 *
	 * @return null
	 *
	 * @version 0.1 27.04.2015
	 * @author Дмитрий Щербаков <atomcms@ya.ru>
	 */
	static function start() {
		// Отсекаем Config::$global['path_short_root'] и "/" с концов
		$routes = trim(str_replace(Config::$global['path_short_root'], '', $_SERVER['REQUEST_URI']), '/');

		if ($routes == '') {
			// Главная страница
			$result = [
				'controller' => 'Main',
				'action' => 'start',
			];
		} else {
			// Отделяем параметры от маршрута, если есть
			if (strpos($routes, '/?') !== false) {
				// Разбиваем маршрут по "/?"
				$routes_array = explode('/?', $routes);
			} elseif (strpos($routes, '?') !== false) {
				// Разбиваем маршрут по "?"
				$routes_array = explode('?', $routes);
			} else {
				$routes_array[] = $routes;
			}

			// Разбиваем маршрут по "/"
			$route_con_act = explode('/', $routes_array[0]);

			// Проверяем доступность контроллера
			if (in_array($route_con_act[0], \App\Configs\Routes::$list)) {
				if ($route_con_act[1] != '') {
					$result = [
						'controller' => ucfirst($route_con_act[0]),
						'action' => $route_con_act[1],
					];
				} else {
					$result = [
						'controller' => ucfirst($route_con_act[0]),
						'action' => 'start',
					];
				}
			} else {
				$result = [
					'controller' => 'Error404',
					'action' => 'start',
				];
			}

			// Если есть параметры, находим их
			if ($routes_array[1] != '') {
				// Разбиваем параметры по "&"
				$route_params = explode('&', $routes_array[1]);

				if (count($route_params) > 0) {
					foreach ($route_params as $param) {
						$param = explode('=', $param);
						$result['params'][$param[0]] = $param[1];
					}
				}
			}

			// Дополняем параметрами из массива POST
			if (count($_POST) > 0) {
				foreach ($_POST as $param => $value) {
					$result['params'][$param] = $value;
				}
			}
		}

		Config::$global['route_controller'] = strtolower($result['controller']);
		Config::$global['route_action'] = $result['action'];

		$result['controller'] = '\\App\\Controllers\\' . $result['controller'];

		return $result;
	}
}
?>