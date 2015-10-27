<?php
/**
 * Migration
 *
 * Модель для применения изменений в БД
 *
 * @version 0.6 27.10.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */

namespace AtomFramework\Models;

use AtomFramework\Configs\Config;

class Migration {
	//
	// 	  ,,                                        ,,    ,,
	// 	  db                        mm            `7MM  `7MM
	// 	                            MM              MM    MM
	// 	`7MM  `7MMpMMMb.  ,pP"Ybd mmMMmm  ,6"Yb.    MM    MM
	// 	  MM    MM    MM  8I   `"   MM   8)   MM    MM    MM
	// 	  MM    MM    MM  `YMMMa.   MM    ,pm9MM    MM    MM
	// 	  MM    MM    MM  L.   I8   MM   8M   MM    MM    MM
	// 	.JMML..JMML  JMML.M9mmmP'   `Mbmo`Moo9^Yo..JMML..JMML.
	//
	//

	/**
	 * Установка с нуля
	 *
	 * @return array
	 *
	 * @version 0.3 08.05.2015
	 * @author Дмитрий Щербаков <atomcms@ya.ru>
	 */
	static function install() {
		$file = Config::$global['path_home_root'] . '/install.sql';

		if (is_readable($file)) {
			// Читаем содержимое файла
			$content = file_get_contents($file);

			if ($content != '') {
				// Собираем результат работы
				$sql_success = 0;
				$result = [];

				// Предварительно обрабатываем содержимое файла
				$sql_list = sql_split($content);

				// Считаем количество запросов
				$sql_count = count($sql_list);

				if ($sql_count > 0) {
					// Прогоняем каждую команду
					foreach ($sql_list as $command) {
						$db_result = query($command, [], __FILE__, __LINE__);

						if ($db_result != -1) {
							++$sql_success;

							$result[] = [
								'code' => 'success',
								'sql' => $command,
							];
						} else {
							$result[] = [
								'code' => 'error',
								'sql' => $command,
							];
						}
					}
				}

				return [
					'code' => 'success',
					'text' => 'Обработка выполнена.',
					'data_count' => $sql_count,
					'data_success' => $sql_success,
					'data' => $result,
				];
			} else {
				return [
					'code' => 'info',
					'text' => 'Файл пустой.',
				];
			}
		} else {
			return [
				'code' => 'danger',
				'text' => 'Файл отсутствует или недоступен для чтения.',
			];
		}
	}

	//
	// 	         ,,
	// 	       `7MM
	// 	         MM
	// 	 ,p6"bo  MMpMMMb.   ,6"Yb.  `7MMpMMMb.  .P"Ybmmm .gP"Ya
	// 	6M'  OO  MM    MM  8)   MM    MM    MM :MI  I8  ,M'   Yb
	// 	8M       MM    MM   ,pm9MM    MM    MM  WmmmP"  8M""""""
	// 	YM.    , MM    MM  8M   MM    MM    MM 8M       YM.    ,
	// 	 YMbmd'.JMML  JMML.`Moo9^Yo..JMML  JMML.YMMMMMb  `Mbmmd'
	// 	                                       6'     dP
	// 	                                       Ybmmmd'

	/**
	 * Применение изменений
	 *
	 * @return array
	 *
	 * @version 0.3 08.05.2015
	 * @author Дмитрий Щербаков <atomcms@ya.ru>
	 */
	static function change() {
		$file = Config::$global['path_home_root'] . '/change.sql';

		if (is_readable($file)) {
			// Читаем содержимое файла
			$content = file_get_contents($file);

			if ($content != '') {
				// Собираем результат работы
				$result = [];

				// Предварительно обрабатываем содержимое файла
				$sql_list = sql_split($content);

				// Считаем количество запросов
				$sql_count = count($sql_list);

				if ($sql_count > 0) {
					// Прогоняем каждую команду
					foreach ($sql_list as $command) {
						$db_result = query($command, [], __FILE__, __LINE__);

						if ($db_result != -1) {
							$result[] = [
								'code' => 'success',
								'sql' => $command,
							];
						} else {
							$result[] = [
								'code' => 'error',
								'sql' => $command,
							];
						}
					}
				}

				return [
					'code' => 'success',
					'text' => 'Обработка выполнена.',
					'data_count' => $sql_count,
					'data' => $result,
				];
			} else {
				return [
					'code' => 'info',
					'text' => 'Файл пустой.',
				];
			}
		} else {
			return [
				'code' => 'danger',
				'text' => 'Файл отсутствует или недоступен для чтения.',
			];
		}
	}
}

//
// 	                   ,,                      ,,    ,,
// 	                 `7MM                    `7MM    db   mm
// 	                   MM                      MM         MM
// 	,pP"Ybd  ,dW"Yvd   MM   ,pP"Ybd `7MMpdMAo. MM  `7MM mmMMmm
// 	8I   `" ,W'   MM   MM   8I   `"   MM   `Wb MM    MM   MM
// 	`YMMMa. 8M    MM   MM   `YMMMa.   MM    M8 MM    MM   MM
// 	L.   I8 YA.   MM   MM   L.   I8   MM   ,AP MM    MM   MM
// 	M9mmmP'  `MbmdMM .JMML. M9mmmP'   MMbmmd'.JMML..JMML. `Mbmo
// 	              MM                  MM
// 	            .JMML.  mmmmmmm     .JMML.

/**
 * Функция разделяет SQL-запросы по разделителю ";" и возвращает массив запросов.
 * Пустые запросы игнорируются.
 * :NOTE:
 * Функнция способна быстро обработать достаточно большие SQL дампы!
 * Дамп размером 1.6 мегабайта (15000 запросов) обрабатывается за 3 секунды на Intel Celeron 3000 MGz!
 *
 * @param    string  $sql                 SQL запросы
 * @param    bool    $is_strip_comments   вырезать комментарии?
 * @return   array
 *
 * @author   Nasibullin Rinat <n a s i b u l l i n  at starlink ru>
 * @charset  ANSI
 * @version  3.0.3
 */
function sql_split($sql, $is_strip_comments = true)
{
	static $_is_callback       = false;
	static $_is_strip_comments = false;

	if ($_is_callback)
	{
		if ($sql[0] === ';')
		{
			return "\x01\r;\n\x02";
		}
		elseif ($_is_strip_comments)
		{
			#вырезаем комментарии
			$c = substr($sql[0], 0, 1);
			if (#однострочные комментарии
				$c === '-' || $c === '#'
				#многострочные комментарии, за исключением /*!ddddd ... */
				#(выполнение части кода SQL запроса в зависимости от версии MySQL)
				|| ($c === '/' && preg_match('/^\/\*(?!\!\d{5}[ \r\n\t])/s', $sql[0])))
			{
				return '';
			}
		}
		return $sql[0];
	}

	if (! is_string($sql))
	{
		trigger_error('String type expected in first parameter, ' . gettype($sql) . ' given ', E_USER_WARNING);
		return array();
	}

	$_is_strip_comments = $is_strip_comments;
	$_is_callback = true;
	/*
	Регулярные выражения для поиска текста в кавычках, где кавычки могут экранироваться, например, "test\"test"
	\*[^*]*\*+([^\/*][^*]*\*+)*      #многострочный комментарий, подобно \/\*.*?\*\/
	"[^"\\\\]*(?:\\\\.[^"\\\\]*)*"   #рег. выражение с "раскруткой" цикла
	"(? >[^"\\\\]+|\\\\.)*"          #"однократные" субпатэрны  (убрать пробел после знака вопроса, т.к. "подсветка" синтаксиса сбивается)
	*/
	#заменяет ";" вне кавычек и комментариев на "\x01;\x02"
	$sql = preg_replace_callback('/ "  (?>[^"\\\\]+|\\\\.)*   "
								  | \' (?>[^\'\\\\]+|\\\\.)* \'
								  | `  (?>[^`]+|``)*          `
								  | \/\*  .*?  \*\/
								  | --[^\r\n]*
								  | \#[^\r\n]*
								  | ;
								  /sx', __FUNCTION__, $sql);
	$_is_callback = false;
	$r = array();
	$sql = explode("\x01\r;\n\x02", $sql);
	foreach ($sql as $q)
	{
		if ($q = trim($q))
		{
			$r[] = $q;
		}
	}#foreach
	return $r;
}
?>