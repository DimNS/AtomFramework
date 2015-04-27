<?php
/**
* Config
*
* Класс для работы с настройками системы
*
* @version 0.1 27.04.2015
* @author Дмитрий Щербаков <atomcms@ya.ru>
*/

namespace App\Configs;

class Config {
	/**
	* @var array $global Массив для глобальных переменных
	*/
	public static $global = [
		// Это боевой сервер (true), необходим для подставления .min в подключении скриптов и блокировки отправки почты (boolean)
		'production' => false,

		// Текущий номер версии системы (string)
		'version' => '0.1',

		// Название проекта (string)
		'proj_name' => 'Atom Framework',

		// Описание проекта (string)
		'proj_descr' => 'Bla-bla-bla',

		// Первый год запуска проекта, если текущий год отличается тогда произойдет замена на ГОД_ЗАПУСКА "дефис" ТЕКУЩИЙ_ГОД (integer)
		'start_year' => 2015,

		// Префикс создаваемых таблиц (string)
		'db_prefix' => 'atom_',

		// Секретный ключ для запуска процесса миграций БД (string)
		'db_migration_token' => 'YOUR_TOKEN_HERE',

		// Адрес для добавления в поле "От кого" при отправке в письмах (string)
		'mail_from_email' => 'info@bestion.ru',

		// Имя для добавления в поле "От кого" при отправке в письмах (string)
		'mail_from_name' => 'Atom Framework',

		// Путь до корня, с начальной "/", но без конечной "/", пример: "/proj_sub_folder" (string)
		// Оставить пусто если проект находится в корне
		'path_short_root' => '/atom_framework',

		// Имя сессии (string)
		'session_name' => 'ATOM_SESSION',

		// Время удаления подвисших сессий, в секундах (integer)
		'session_die_time' => 43200,
	];

	/**
	* @var array $session Переменная сессии
	*/
	public static $session = [
		'id' => 0,
		'session' => '0',
	];

	/**
	* @var array $userinfo Информация о пользователе
	*/
	public static $userinfo = [];
}
?>