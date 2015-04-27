<?php
/**
* Changelog
*
* Модель для работы с файлом изменений
*
* @version 0.1 27.04.2015
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
	* @version 0.1 27.04.2015
	* @author Дмитрий Щербаков <atomcms@ya.ru>
	*/
	static function check() {
		Config::$global['db']->query("UPDATE `" . Config::$global['db_prefix'] . "user` SET
			version = :version
			WHERE id = :id
			LIMIT 1
		", [
			'id' => Config::$userinfo['id'],
			'version' => Config::$global['version'],
		]);

		return [
			'code' => 'success',
			'text' => 'ok',
		];
	}
}
?>