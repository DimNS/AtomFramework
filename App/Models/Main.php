<?php
/**
 * Main
 *
 * Модель для работы главной страницы
 *
 * @version 0.1 27.04.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */

namespace App\Models;

class Main {
	/**
	 * Получение данных для главной страницы
	 *
	 * @return array
	 *
	 * @version 0.1 27.04.2015
	 * @author Дмитрий Щербаков <atomcms@ya.ru>
	 */
	static function index() {
		return [
			'code' => 'success',
			'text' => 'Данные успешно получены',
		];
	}
}
?>