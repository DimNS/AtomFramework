<?php
/**
 * Главный файл обрабатывающий все запросы
 *
 * @version 0.1 27.04.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */

header('Content-type: text/html; charset=UTF-8');

// Подключаем файл с ядром
require_once('app/application.php');

// Разбираем маршрут в массив
$route = \App\Utility\Route::start();

// Если запущена миграция, тогда не искать сессию, таблиц еще может не быть
if ($route['controller'] != '\App\Controllers\Migration') {
	// Обновляем сессию
	\App\Utility\Session::find();
}

// В зависимости откуда пришел запрос, делаем правильный вывод
if ($route['params']['ajax']) {
	echo json_encode($route['controller']::start($route));
} else {
	echo $route['controller']::start($route);
}
?>