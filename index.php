<?php
/**
 * Главный файл обрабатывающий все запросы
 *
 * @version 0.6 27.10.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */

header('Content-type: text/html; charset=UTF-8');

// Подключаем файл с ядром
require_once('App/application.php');

// Разбираем маршрут в массив
$route = \AtomFramework\Utility\Route::start();

// Смотрим пришел ли запрос через AJAX
if (isset($route['params']['ajax'])) {
	$ajax = $route['params']['ajax'];
} else {
	$ajax = 'false';
}

try {
	// Если запущена миграция, тогда не искать сессию, таблиц еще может не быть
	if ($route['controller'] != '\AtomFramework\Controllers\Migration') {
		// Обновляем сессию
		\AtomFramework\Utility\Session::find();
	}

	// Получаем массив с результатом выполнения
	$result = $route['controller']::start($route);

	// В зависимости откуда пришел запрос, делаем правильный вывод
	if ($ajax === 'true') {
		// Возвращаем результат в виде массива
		echo json_encode($result);
	} else {
		// Отображаем содержимое страницы
		echo $result;
	}
} catch(\AtomFramework\Utility\AtomException $e) {
	// Отображаем результат
	echo \AtomFramework\Utility\AtomException::report($ajax, $e);
}
?>