<?php
/**
* Error404
*
* Класс для вызова страницы ошибки 404
*
* @version 0.1 27.04.2015
* @author Дмитрий Щербаков <atomcms@ya.ru>
*/

namespace App\Controllers;

use App\Utility\Error;

class Error404 {
	/**
	* Запуск контроллера
	*
	* @return content
	*
	* @version 0.1 27.04.2015
	* @author Дмитрий Щербаков <atomcms@ya.ru>
	*/
	static function start() {
		header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');
		Error::ins(0, 'ERR', '', 'Ошибка 404: Страница не найдена (запрошен адрес: ' . $_SERVER['REQUEST_URI'] . ')', __FILE__, __FUNCTION__, __LINE__);
		\App\Views\Error404::show();
		return null;
	}
}
?>