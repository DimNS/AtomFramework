<?php
/**
 * Protocol
 *
 * Класс для работы с протоколом действий пользователя
 *
 * @version 0.1 27.04.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */

namespace App\Utility;

use App\Configs\Config;

class Protocol {
	/**
	 * Добавление строки в протокол
	 *
	 * @param string  $action   Строка с текстом события
	 * @param string  $file     Файл в котором произошло событие
	 * @param integer $line     Номер строки в которой произошло событие
	 * @param string  $function Функция в которой произошло событие
	 *
	 * @return null
	 *
	 * @version 0.1 27.04.2015
	 * @author Дмитрий Щербаков <atomcms@ya.ru>
	 */
	static function ins($action, $file, $line, $function) {
		if (Config::$userinfo['id'] == '') {
			$user_id = 0;
		} else {
			$user_id = Config::$userinfo['id'];
		}

		$db_result = Config::$global['db']->query("INSERT INTO `" . Config::$global['db_prefix'] . "protocol` SET
			date = :date,
			user_id = :user_id,
			file = :file,
			line = :line,
			function = :function,
			action = :action
		", [
			'date' => Config::$global['currtime'],
			'user_id' => $user_id,
			'file' => str_replace($_SERVER['DOCUMENT_ROOT'], '', str_replace('\\', '/', $file)),
			'line' => $line,
			'function' => $function,
			'action' => $action,
		]);
	}
}
?>