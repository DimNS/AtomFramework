<?php
/**
 * Session
 *
 * Класс для работы с сессией
 *
 * @version 0.6 27.10.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */

namespace AtomFramework\Utility;

use AtomFramework\Configs\Config;

class Session {
	/**
	 * Создать сессию для пользователя
	 *
	 * @param integer $user_id
	 *
	 * @return integer Статус создания сессии (0|1)
	 *
	 * @version 0.6 27.10.2015
	 * @author Дмитрий Щербаков <atomcms@ya.ru>
	 */
	static function create($user_id) {
		$params = [
			'action' => 'get',
			'params' => [
				'user_id' => $user_id,
			],
		];

		Config::$userinfo = \AtomFramework\Controllers\User::start($params);
		$session_id = sha1(md5(Config::$userinfo['email'] . Config::$global['currtime']));

		$ip = '';
		$ip = Func::get_ip();
		if ($ip === '') {
			$ip = 'no ip';
		}

		query("DELETE FROM `" . Config::$global['db_prefix'] . "session`
			WHERE `id_user` = :id_user
				OR `id_ses` = :id_ses
		", [
			'id_user' => $user_id,
			'id_ses' => $session_id,
		], __FILE__, __LINE__);

		$db_result = query("INSERT INTO `" . Config::$global['db_prefix'] . "session` SET
			`id_ses` = :id_ses,
			`ip` = :ip,
			`last_date` = :last_date,
			`id_user` = :id_user
		", [
			'id_ses' => $session_id,
			'ip' => $ip,
			'last_date' => Config::$global['currtime'],
			'id_user' => $user_id,
		], __FILE__, __LINE__);
		if ($db_result > 0) {
			$_SESSION[Config::$global['session_name']] = $session_id;
			Config::$session = [
				'id' => Config::$userinfo['id'],
				'session' => $session_id,
			];
			return 1;
		} else {
			return 0;
		}
	}

	/**
	 * Найти сессию, если сессия уже есть в базе то обновить атрибуты объекта
	 *
	 * @return boolean Статус поиска сессии
	 *
	 * @version 0.6 27.10.2015
	 * @author Дмитрий Щербаков <atomcms@ya.ru>
	 */
	static function find() {
		// Удаляем подвисшие сессии
		query("DELETE FROM `" . Config::$global['db_prefix'] . "session`
			WHERE last_date < :last_date
		", [
			'last_date' => Config::$global['session_die_time'],
		], __FILE__, __LINE__);

		if (isset($_SESSION[Config::$global['session_name']])) {
			$db_result = query("SELECT * FROM `" . Config::$global['db_prefix'] . "session`
				WHERE `id_ses` = :id_ses
					AND `ip` = :ip
				LIMIT 1
			", [
				'id_ses' => $_SESSION[Config::$global['session_name']],
				'ip' => Func::get_ip(),
			], __FILE__, __LINE__);
			if ($db_result != -1 AND count($db_result) === 1) {
				if ($db_result[0]['id_ses'] === $_SESSION[Config::$global['session_name']]) {
					$item = $db_result[0];

					$params = [
						'action' => 'get',
						'params' => [
							'user_id' => $item['id_user'],
						],
					];
					Config::$userinfo = \AtomFramework\Controllers\User::start($params);

					Config::$session = [
						'id' => $item['id_user'],
						'session' => str_replace("'", "", $_SESSION[Config::$global['session_name']]),
					];
					query("UPDATE `" . Config::$global['db_prefix'] . "session` SET
						`last_date` = :last_date
						WHERE `id_ses` = :id_ses
					", [
						'last_date' => Config::$global['currtime'],
						'id_ses' => $_SESSION[Config::$global['session_name']],
					], __FILE__, __LINE__);
					return true;
				} else {
					unset($_SESSION[Config::$global['session_name']]);
					return false;
				}
			} else {
				unset($_SESSION[Config::$global['session_name']]);
				return false;
			}
		} else {
			return false;
		}
	}

	/**
	 * Удалить сессию пользователя
	 *
	 * @return null
	 *
	 * @version 0.3 08.05.2015
	 * @author Дмитрий Щербаков <atomcms@ya.ru>
	 */
	static function del() {
		$db_result = query("DELETE FROM `" . Config::$global['db_prefix'] . "session`
			WHERE `id_ses` = :id_ses
			LIMIT 1
		", [
			'id_ses' => $_SESSION[Config::$global['session_name']],
		], __FILE__, __LINE__);
		if ($db_result != -1) {
			unset($_SESSION[Config::$global['session_name']]);
		}
	}
}
?>