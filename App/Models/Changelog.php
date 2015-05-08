<?php
/**
 * Changelog
 *
 * Модель для работы с файлом изменений
 *
 * @version 0.3 08.05.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */

namespace App\Models;

use App\Configs\Config;

class Changelog {
	/**
	 * Отметка пользователя о новой версии
	 *
	 * @return array
	 *
	 * @version 0.3 08.05.2015
	 * @author Дмитрий Щербаков <atomcms@ya.ru>
	 */
	static function check() {
		query("UPDATE `" . Config::$global['db_prefix'] . "user` SET
			version = :version
			WHERE id = :id
			LIMIT 1
		", [
			'id' => Config::$userinfo['id'],
			'version' => Config::$global['version'],
		], __FILE__, __LINE__);

		return [
			'code' => 'success',
			'text' => 'ok',
		];
	}
}
?>