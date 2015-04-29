<?php
/**
 * Template
 *
 * Класс для работы с шаблонами
 *
 * @version 0.1 27.04.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */

namespace App\Utility;

class Template {
	/**
	 * Отображаем необходимые шаблоны
	 *
	 * @param array $templates Массив со списком необходимых шаблонов
	 *
	 * @return null
	 *
	 * @version 0.1 27.04.2015
	 * @author Дмитрий Щербаков <atomcms@ya.ru>
	 */
	static function show($templates) {
		if (count($templates) > 0) {
			foreach ($templates as $tpl) {
				if (is_readable('App/Templates/' . $tpl . '.php')) {
					require_once('App/Templates/' . $tpl . '.php');
				}
			}
		}
	}
}
?>