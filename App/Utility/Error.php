<?php
/**
* Error
*
* Класс для работы с файлом ошибок
*
* @version 0.1 27.04.2015
* @author Дмитрий Щербаков <atomcms@ya.ru>
*/

namespace App\Utility;

use App\Configs\Config;
use App\Utility\Func;

class Error {
	/**
	* Основная функция для добавления записи об ошибке
	*
	* ___FILE___ - путь к файлу
	* ___FUNCTION___ - имя функции
	* ___LINE___ - номер текущей строки
	*
	* @param string $show Статус показывать или нет ошибку (1|0)
	* @param string $str_user Строка с ошибкой для пользователя
	* @param string $str_admin Строка с ошибкой для администратора
	* @param string $file Файл в котором произошла ошибка
	* @param string $function Функция в которой произошла ошибка
	* @param integer $line Строка в которой произошла ошибка
	*
	* @return string Отображаем сообщение если параметр $show со значением 1
	*
	* @version 0.1 27.04.2015
	* @author Дмитрий Щербаков <atomcms@ya.ru>
	*/
	static function ins($show, $str_user, $str_admin, $file, $function, $line) {
		$ip = Func::get_ip();

		$str = '<span class="err-message">Текст ошибки: ' . $str_user . '<br />Номер ошибки: <b>' . Config::$global['currtime'] . '</b><br /><br />Просьба обратиться к администрации указав номер ошибки.</span>';

		$str_admin = str_replace("\n", "", $str_admin);
		$str_admin = str_replace("\t", "", $str_admin);
		$str_admin = str_replace("\r", " ", $str_admin);

		$content = Config::$global['currtime'] . '|' . $ip . '|' . $str_user . '|' . $str_admin . '|' . $file . '|' . $function . ' (line: ' . $line . ")\n";

		$filename = Config::$global['path_home_root'] . '/errors.log';

		// Если файл слишком большой, сделать его копию и очистить старый лог
		// 5 Mb
		if (filesize($filename) > 5242880) {
			$prefix = 'errors';
			$suffix = '.log';
			$i = 1;
			$dest = $prefix.$i.$suffix;
			while (file_exists($dest)) {
				++$i;
				$dest = $prefix.$i.$suffix;
			}
			rename($filename, Config::$global['path_home_root'] . '/' . $dest);
			$handle = fopen($filename, 'w');
			fclose($handle);
		}

		// Убедимся, что файл существует и доступен для записи.
		if (is_writable($filename)) {
			// Открываем $filename в режиме "дописать в конец".
			if (!$handle = fopen($filename, 'a')) {
				die('Невозможно открыть файл. Обратитесь к администратору.');
			}

			flock($handle, LOCK_EX);

			// Записываем $content в наш открытый файл.
			if (fwrite($handle, $content) === FALSE) {
				die('Невозможно записать в файл. Обратитесь к администратору.');
			}

			flock($handle, LOCK_UN);

			fclose($handle);

			if ($show == 1) {
				die($str);
			}
		} else {
			die($filename.'Файл недоступен для записи. Обратитесь к администратору.');
		}
	}
}
?>