<?php
/**
 * Error
 *
 * Класс для работы с файлом ошибок
 *
 * @version 0.3 08.05.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */

namespace App\Utility;

use App\Configs\Config;

class Error {
	/**
	 * Основная функция для добавления записи об ошибке
	 *
	 * ___FILE___ - путь к файлу
	 * ___LINE___ - номер текущей строки
	 *
	 * @param string  $code      Номер ошибки
	 * @param string  $str_admin Строка с ошибкой для администратора
	 * @param string  $file      Файл в котором произошла ошибка
	 * @param integer $line      Строка в которой произошла ошибка
	 *
	 * @return null
	 *
	 * @version 0.3 08.05.2015
	 * @author Дмитрий Щербаков <atomcms@ya.ru>
	 */
	static function ins($code, $str_admin, $file, $line) {
		$ip = Func::get_ip();

		$str_admin = str_replace("\n", "", $str_admin);
		$str_admin = str_replace("\t", "", $str_admin);
		$str_admin = str_replace("\r", " ", $str_admin);

		$content = Config::$global['currtime'] . '|' . $ip . '|' . $code . '|' . $str_admin . '|' . $file . ' (line: ' . $line . ")\n";

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
		} else {
			die($filename.'Файл недоступен для записи. Обратитесь к администратору.');
		}
	}
}
?>