<?php
/**
 * User
 *
 * Класс для работы с пользователями
 *
 * @version 0.1 27.04.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */

namespace App\Controllers;

use App\Configs\Config;
use App\Utility\Func;

class User {
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
		switch ($params['action']) {
			// Авторизация пользователя
			case 'signin':
				$user_id = \App\Models\User::login($params['params']['email'], $params['params']['password']);

				switch ($user_id) {
					case '0':
						$message_code = 'warning';
						$message_text = 'Неверно указана почта или пароль';
					break;

					case '-1':
						$message_code = 'danger';
						$message_text = 'Пользователь заблокирован';
					break;

					case '-2':
						$message_code = 'info';
						$message_text = 'Пользователь не найден';
					break;

					default:
						$message_code = 'NaN';
						$message_text = '';
					break;
				}

				if ($message_code == 'NaN') {
					// Авторизация прошла успешно, перезагружаем страницу
					\App\Utility\Session::create($user_id);
					header('Location: ' . Config::$global['path_short_root'] . '/');
				} else {
					// Авторизация не прошла. показываем снова главную с ошибкой
					return \App\Controllers\Main::error($message_code, $message_text);
				}
			break;

			// Выход пользователя из системы
			case 'signout':
				\App\Utility\Session::del();
				header('Location: ' . Config::$global['path_short_root'] . '/');
			break;

			case 'add':
				return \App\Models\User::add($params['params']['name'], $params['params']['email'], $params['params']['password']);
			break;

			case 'get':
				return \App\Models\User::get($params['params']['user_id']);
			break;

			case 'save':
				if (Func::is_login()) {
					return \App\Models\User::save($params['params']['name'], $params['params']['password'], $params['params']['newpassword']);
				} else {
					return \App\Controllers\Main::error('info', 'Необходимо войти в систему');
				}
			break;

			case 'lost_password':
				return \App\Models\User::lost_password($params['params']['email']);
			break;

			case 'reset_password':
				$result = \App\Models\User::reset_password($params['params']['key']);

				// Прошло все успешно или нет, все равно показываем главную с сообщением
				return \App\Controllers\Main::error($result['code'], $result['text']);
			break;

			case 'profile':
				if (Func::is_login()) {
					return \App\Views\User::profile(\App\Models\User::get(Config::$userinfo['id']));
				} else {
					return \App\Controllers\Main::error('info', 'Необходимо войти в систему');
				}
			break;

			default:
				return \App\Controllers\Error404::start();
			break;
		}
	}
}
?>