<?php
/**
 * User
 *
 * Модель для работы с пользователями
 *
 * @version 0.1 27.04.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */

namespace App\Models;

use App\Configs\Config;
use App\Utility\Email;
use App\Utility\Error;
use App\Utility\Func;
use App\Utility\Protocol;

class User {
	/**
	 * Добавление нового пользователя
	 *
	 * @param string $name     Имя пользователя
	 * @param string $email    Почтовый адрес
	 * @param string $password Пароль
	 *
	 * @return array
	 *
	 * @version 0.1 27.04.2015
	 * @author Дмитрий Щербаков <atomcms@ya.ru>
	 */
	static function add($name, $email, $password) {
		$db_result = Config::$global['db']->query("SELECT * FROM `" . Config::$global['db_prefix'] . "user`
			WHERE email = :email
			LIMIT 1
		", [
			'email' => $email,
		]);
		if ($db_result != -1) {
			if (count($db_result) == 0) {
				$password = Func::generate_password();

				$db_result = Config::$global['db']->query("INSERT INTO `" . Config::$global['db_prefix'] . "user` SET
					date = :date,
					email = :email,
					password = :password,
					name = :name,
					version = :version
				", [
					'date' => Config::$global['currtime'],
					'email' => $email,
					'password' => Func::collect_password($password),
					'name' => $name,
					'version' => Config::$global['version'],
				]);
				if ($db_result >= 0) {
					Protocol::ins('Добавлен новый администратор: ' . $email, __FILE__, __LINE__, __FUNCTION__);

					if (!Email::send('reg', $email, ['email' => $email, 'password' => $password])) {
						return [
							'code' => 'warning',
							'text' => 'При отправке пароля произошла ошибка. Свяжитесь с администрацией.',
						];
					} else {
						Email::send('newuser', Config::$global['mail_from_email'], ['email' => $email]);

						return [
							'code' => 'success',
							'text' => 'Для входа воспользуйтесь паролем отправленным на почту.',
						];
					}
				} else {
					return [
						'code' => 'warning',
						'text' => 'При добавлении произошла ошибка. Попробуйте ещё раз.',
					];
				}
			} else {
				return [
					'code' => 'info',
					'text' => 'Клиент с email "' . $email . '" уже добавлен.',
				];
			}
		} else {
			return [
				'code' => 'danger',
				'text' => 'Ошибка в запросе. Попробуйте ещё раз.',
			];
		}
	}

	/**
	 * Авторизация пользователя по логину и паролю
	 *
	 * @param string $email    Почтовый адрес для авторизации
	 * @param string $password Пароль для авторизации
	 *
	 * @return integer Статус авторизации (#: id_user; 0: wrong_email_or_password; -1: user_blocked; -2: user_not_found)
	 *
	 * @version 0.1 27.04.2015
	 * @author Дмитрий Щербаков <atomcms@ya.ru>
	 */
	static function login($email, $password) {
		// Удаляем устаревшие попытки входа
		// 7 дней
		$db_result = Config::$global['db']->query("DELETE FROM `" . Config::$global['db_prefix'] . "user_block`
			WHERE date < :date
		", [
			'date' => Config::$global['currtime'] - 604800,
		]);

		$db_result = Config::$global['db']->query("SELECT * FROM `" . Config::$global['db_prefix'] . "user`
			WHERE email = :email
				AND `active` = '1'
			LIMIT 1
		", [
			'email' => $email,
		]);
		if ($db_result != -1) {
			if (count($db_result) == 1) {
				$user_info = $db_result[0];

				$db_result = Config::$global['db']->query("SELECT * FROM `" . Config::$global['db_prefix'] . "user`
					WHERE id = :id
					LIMIT 1
				", [
					'id' => $user_info['id'],
				]);
				if ($db_result != -1) {
					if (count($db_result) < 10) {
						if (crypt($password, $user_info['password']) == $user_info['password']) {
							// Записываем браузер пользователя
							$db_result = Config::$global['db']->query("INSERT INTO `" . Config::$global['db_prefix'] . "browsers` SET
								date = :date,
								user_id = :user_id,
								user_agent = :user_agent
							", [
								'date' => Config::$global['currtime'],
								'user_id' => $user_info['id'],
								'user_agent' => $_SERVER['HTTP_USER_AGENT'],
							]);

							return $user_info['id'];
						} else {
							$db_result = Config::$global['db']->query("INSERT INTO `" . Config::$global['db_prefix'] . "user_block` SET
								user_id = :user_id,
								date = :date
							", [
								'user_id' => $user_info['id'],
								'date' => time(),
							]);
							if ($db_result >= 0) {
								Error::ins(0, 'SQL', '', 'Пользователь не прошел авторизацию (передан параметр email: ' . $email . ')', __FILE__, __FUNCTION__, __LINE__);
								return 0;
							}
						}
					} else {
						Error::ins(0, 'ERR', 'Пользователь заблокирован. Обратитесь к администратору', 'Пользователь заблокирован (передан параметр email: ' . $email . ')', __FILE__, __FUNCTION__, __LINE__);
						return -1;
					}
				} else {
					return 0;
				}
			} else {
				Error::ins(0, 'SQL', '', 'Пользователь не существует (передан параметр email: ' . $email . ')', __FILE__, __FUNCTION__, __LINE__);
				return -2;
			}
		} else {
			return 0;
		}
	}

	/**
	 * Получение данных пользователя по его ИД
	 *
	 * @param integer $id
	 *
	 * @return array Массив данных пользователя
	 *
	 * @version 0.1 27.04.2015
	 * @author Дмитрий Щербаков <atomcms@ya.ru>
	 */
	static function get($id) {
		if (preg_match("/^[0-9]+$/", $id)) {
			$db_result = Config::$global['db']->query("SELECT * FROM `" . Config::$global['db_prefix'] . "user`
				WHERE id = :id
				LIMIT 1
			", [
				'id' => $id,
			]);

			return $db_result[0];
		}
	}

	/**
	 * Сохранение личных данных
	 *
	 * @param string $name        Имя пользователя
	 * @param string $password    Новый пароль, если указан
	 * @param string $newpassword Статус смены пароля
	 *
	 * @return array
	 *
	 * @version 0.1 27.04.2015
	 * @author Дмитрий Щербаков <atomcms@ya.ru>
	 */
	static function save($name, $password, $newpassword) {
		$db_result = Config::$global['db']->query("SELECT * FROM `" . Config::$global['db_prefix'] . "user`
			WHERE id = :id
			LIMIT 1
		", [
			'id' => Config::$userinfo['id'],
		]);
		if ($db_result != -1 AND count($db_result) > 0) {
			if ($newpassword == '1') {
				if ($password != '' AND count($password) > 3) {
					$db_result = Config::$global['db']->query("UPDATE `" . Config::$global['db_prefix'] . "user` SET
						name = :name,
						password = :password
						WHERE id = :id
						LIMIT 1
					", [
						'name' => $name,
						'password' => Func::collect_password($password),
						'id' => Config::$userinfo['id'],
					]);
					Protocol::ins('Сохранение личных данных с изменением пароля', __FILE__, __LINE__, __FUNCTION__);
				} else {
					return [
						'code' => 'info',
						'text' => 'Укажите новый пароль. Длина пароля не менее 3 символов.'
					];
				}
			} else {
				$db_result = Config::$global['db']->query("UPDATE `" . Config::$global['db_prefix'] . "user` SET
					name = :name
					WHERE id = :id
					LIMIT 1
				", [
					'name' => $name,
					'id' => Config::$userinfo['id'],
				]);
				Protocol::ins('Сохранение личных данных', __FILE__, __LINE__, __FUNCTION__);
			}

			if ($db_result >= 0) {
				return [
					'code' => 'success',
					'text' => 'Данные изменены.',
				];
			} else {
				return [
					'code' => 'warning',
					'text' => 'При изменении произошла ошибка. Попробуйте ещё раз.',
				];
			}
		} else {
			return [
				'code' => 'warning',
				'text' => 'Произошла ошибка. Попробуйте ещё раз.',
			];
		}
	}

	/**
	 * Отправка запроса для напоминания пароля
	 *
	 * @param string $email Почтовый адрес
	 *
	 * @return array
	 *
	 * @version 0.1 27.04.2015
	 * @author Дмитрий Щербаков <atomcms@ya.ru>
	 */
	static function lost_password($email) {
		$db_result = Config::$global['db']->query("SELECT * FROM `" . Config::$global['db_prefix'] . "user`
			WHERE email = :email
			LIMIT 1
		", [
			'email' => $email,
		]);
		if ($db_result != -1) {
			if (count($db_result) > 0) {
				$hash = md5($email);

				$db_result = Config::$global['db']->query("UPDATE `" . Config::$global['db_prefix'] . "user` SET
					reset_password = :reset_password
					WHERE email = :email
				", [
					'reset_password' => $hash,
					'email' => $email,
				]);
				if ($db_result >= 0) {
					Protocol::ins('Запрос на сброс пароля для пользователя: ' . $email, __FILE__, __LINE__, __FUNCTION__);

					if (!Email::send('reset', $email, ['email' => $email, 'hash' => $hash])) {
						return [
							'code' => 'warning',
							'text' => 'При отправке письма произошла ошибка. Свяжитесь с администрацией.',
						];
					} else {
						return [
							'code' => 'success',
							'text' => 'Запрос на изменение пароля успешно выслан на указанную почту.',
						];
					}
				} else {
					return [
						'code' => 'warning',
						'text' => 'При внесении запроса произошла ошибка. Попробуйте ещё раз.',
					];
				}
			} else {
				return [
					'code' => 'info',
					'text' => 'Клиент с email "' . $email . '" не найден.',
				];
			}
		} else {
			return [
				'code' => 'danger',
				'text' => 'Ошибка в запросе. Попробуйте ещё раз.',
			];
		}
	}

	/**
	 * Сброс пароля для пользователя
	 *
	 * @param integer $key Ключ из письма
	 *
	 * @return string Результат работы
	 *
	 * @version 0.1 27.04.2015
	 * @author Дмитрий Щербаков <atomcms@ya.ru>
	 */
	static function reset_password($key) {
		$db_result = Config::$global['db']->query("SELECT * FROM `" . Config::$global['db_prefix'] . "user`
			WHERE reset_password = :reset_password
			LIMIT 1
		", [
			'reset_password' => $key,
		]);
		if ($db_result != -1 AND count($db_result) > 0) {
			$email = $db_result[0]['email'];
			$password = Func::generate_password();

			$db_result = Config::$global['db']->query("UPDATE `" . Config::$global['db_prefix'] . "user` SET
				password = :password,
				reset_password = ''
				WHERE reset_password = :reset_password
			", [
				'password' => Func::collect_password($password),
				'reset_password' => $key,
			]);
			if ($db_result >= 0) {
				Protocol::ins('Сброс пароля для пользователя: ' . $email, __FILE__, __LINE__, __FUNCTION__);

				if (!Email::send('lost', $email, ['email' => $email, 'password' => $password])) {
					return [
						'code' => 'warning',
						'text' => 'При отправке пароля произошла ошибка. Свяжитесь с администрацией.',
					];
				} else {
					$db_result = Config::$global['db']->query("DELETE FROM `" . Config::$global['db_prefix'] . "user_block`
						WHERE user_id = :user_id
					", [
						'user_id' => Config::$userinfo['id'],
					]);

					return [
						'code' => 'success',
						'text' => 'Новый пароль успешно отправлен на вашу почту.',
					];
				}
			} else {
				return [
					'code' => 'warning',
					'text' => 'При создании нового пароля произошла ошибка. Обновите это окно.',
				];
			}
		} else {
			return [
				'code' => 'warning',
				'text' => 'Не найден запрос на изменение пароля.',
			];
		}
	}
}
?>