<?php
/**
 * Main
 *
 * Класс для вызова главной страницы
 *
 * @version 0.1 27.04.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */

namespace App\Controllers;

use App\Configs\Config;
use App\Utility\Func;

class Main {
	/**
	 * Запуск контроллера
	 *
	 * @return content
	 *
	 * @version 0.1 27.04.2015
	 * @author Дмитрий Щербаков <atomcms@ya.ru>
	 */
	static function start() {
		if (Func::is_login()) {
			// Получаем данные для главной страницы (с авторизацией)
			$data = \App\Models\Main::index(true);

			if ($data['code'] == 'success') {
				return \App\Views\Main::dashboard($data);
			} else {
				//ERROR
			}
		} else {
			// Получаем данные для главной страницы (без авторизации)
			$data = \App\Models\Main::index(false);

			if ($data['code'] == 'success') {
				return \App\Views\Main::landing($data);
			} else {
				//ERROR
			}
		}
	}

	/**
	 * Отображаем главную страницу с ошибкой
	 *
	 * @param string $message_code Код сообщения
	 * @param string $message_text Текст сообщения
	 *
	 * @return content
	 *
	 * @version 0.1 27.04.2015
	 * @author Дмитрий Щербаков <atomcms@ya.ru>
	 */
	static function error($message_code, $message_text) {
		// Код сообщения и текст сообщения, если переданы
		Config::$global['message_code'] = $message_code;
		Config::$global['message_text'] = $message_text;

		// Получаем данные для главной страницы (без авторизации)
		$data = \App\Models\Main::index(false);

		if ($data['code'] == 'success') {
			return \App\Views\Main::landing($data);
		} else {
			//ERROR
		}
	}
}
?>