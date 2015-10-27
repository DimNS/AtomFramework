<?php
/**
 * User
 *
 * Класс для работы с пользователями
 *
 * @version 0.6 27.10.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */

namespace AtomFramework\Controllers;

use AtomFramework\Configs\Config;
use AtomFramework\Controllers\Main as MainController;
use AtomFramework\Models\User as UserModel;
use AtomFramework\Utility\Func;

class User {
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
		switch ($params['action']) {
			// Авторизация пользователя
			case 'signin':
				$user_id = UserModel::login($params['params']['email'], $params['params']['password']);

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

				if ($message_code === 'NaN') {
					// Авторизация прошла успешно, перезагружаем страницу
					\AtomFramework\Utility\Session::create($user_id);
					header('Location: ' . Config::$global['path_short_root'] . '/');
				} else {
					// Авторизация не прошла. показываем снова главную с ошибкой
					return MainController::error($message_code, $message_text);
				}
			break;

			// Выход пользователя из системы
			case 'signout':
				\AtomFramework\Utility\Session::del();
				header('Location: ' . Config::$global['path_short_root'] . '/');
			break;

			case 'add':
				return UserModel::add($params['params']['name'], $params['params']['email'], $params['params']['password']);
			break;

			case 'get':
				return UserModel::get($params['params']['user_id']);
			break;

			case 'save':
				if (Func::is_login()) {
					return UserModel::save($params['params']['name'], $params['params']['password'], $params['params']['newpassword']);
				} else {
					return MainController::error('info', 'Необходимо войти в систему');
				}
			break;

			case 'lost_password':
				return UserModel::lost_password($params['params']['email']);
			break;

			case 'reset_password':
				$result = UserModel::reset_password($params['params']['key']);

				// Прошло все успешно или нет, все равно показываем главную с сообщением
				return MainController::error($result['code'], $result['text']);
			break;

			case 'profile':
				if (Func::is_login()) {
					return \AtomFramework\Views\User::profile(UserModel::get(Config::$userinfo['id']));
				} else {
					return MainController::error('info', 'Необходимо войти в систему');
				}
			break;

			default:
				return \AtomFramework\Controllers\Error404::start();
			break;
		}
	}
}
?>