<?php
/**
 * Главный файл обрабатывающий все запросы
 *
 * @version 0.3 08.05.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */

header('Content-type: text/html; charset=UTF-8');

try {
	// Подключаем файл с ядром
	require_once('app/application.php');

	// Разбираем маршрут в массив
	$route = \App\Utility\Route::start();

	// Если запущена миграция, тогда не искать сессию, таблиц еще может не быть
	if ($route['controller'] != '\App\Controllers\Migration') {
		// Обновляем сессию
		\App\Utility\Session::find();
	}

	// Получаем массив с результатом выполнения
	$result = $route['controller']::start($route);

	// В зависимости откуда пришел запрос, делаем правильный вывод
	if ($route['params']['ajax']) {
		// Возвращаем результат в виде массива
		echo json_encode($result);
	} else {
		// Отображаем содержимое страницы
		echo $result;
	}
} catch(App\Utility\AtomException $e) {
	// Отображаем результат
	echo App\Utility\AtomException::report($route['params']['ajax'], $e);
}
?>