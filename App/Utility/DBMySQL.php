<?php
/**
* DBMySQL
*
* Класс для работы с БД MySQL
*
* @version 0.1 27.04.2015
* @author Дмитрий Щербаков <atomcms@ya.ru>
*/

namespace App\Utility;

use PDO;
use App\Utility\Error;
use App\Utility\Func;

class DBMySQL {
	/**
	* @var string $host Сервер БД
	*/
	private $host;

	/**
	* @var string $database Имя БД
	*/
	private $database;

	/**
	* @var string $user Логин пользователя
	*/
	private $user;

	/**
	* @var string $password Пароль пользователя
	*/
	private $password;

	/**
	* @var integer $connect_id Идентификатор соединения с БД
	*/
	private $connect_id = 0;

	/**
	* Конструктор класса
	*
	* @return null
	*
	* @version 0.1 27.04.2015
	* @author Дмитрий Щербаков <atomcms@ya.ru>
	*/
	function __construct($h, $d, $u, $p) {
		$this->host = $h;
		$this->database = $d;
		$this->user = $u;
		$this->password = $p;
	}

	/**
	* Соединение с БД
	*
	* @return integer Идентификатор соединения
	*
	* @version 0.1 27.04.2015
	* @author Дмитрий Щербаков <atomcms@ya.ru>
	*/
	private function connect() {
		if (gettype($this->connect_id) == 'integer') {
			// Пробуем подключиться к БД
			try {
				$this->connect_id = new PDO("mysql:host=$this->host;dbname=$this->database", $this->user, $this->password, [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8']);
				$this->connect_id->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}
			catch(PDOException $e) {
				$this->error('', $e->getMessage());
			}
		}

		return $this->connect_id;
	}

	/**
	* Выполнение запроса
	*
	* @param string $query_str Строка запроса
	* @param array $query_data Массив с данными
	*
	* @return array|integer Возвращается результат выполнения запроса в зависимости от типа запроса
	*
	* @version 0.1 27.04.2015
	* @author Дмитрий Щербаков <atomcms@ya.ru>
	*/
	function query($query_str, $query_data) {
		$this->connect();

		$query_type = mb_strtolower(mb_substr($query_str, 0, mb_strpos($query_str, ' ', 0, 'utf-8'), 'utf-8'), 'utf-8');

		//Func::debug($query_str);
		//Func::debug($query_data);

		switch ($query_type) {
			case 'select':
				try {
					$STH = $this->connect_id->prepare($query_str);
					$STH->execute($query_data);
					$STH->setFetchMode(PDO::FETCH_ASSOC);
					return $STH->fetchAll();
				}
				catch(PDOException $e) {
					return $this->error($query_str, $e->getMessage());
				}
			break;

			case 'insert':
				try {
					$STH = $this->connect_id->prepare($query_str);
					$STH->execute($query_data);
					return $this->connect_id->lastInsertId();
				}
				catch(PDOException $e) {
					return $this->error($query_str, $e->getMessage());
				}
			break;

			default:
				try {
					$STH = $this->connect_id->prepare($query_str);
					$STH->execute($query_data);
					return $STH->rowCount();
				}
				catch(PDOException $e) {
					return $this->error($query_str, $e->getMessage());
				}
			break;
		}
	}

	/**
	* Выполнение нескольких запросов в одном
	*
	* @param string $table_name Имя таблицы
	* @param array $data_fields Массив со списком полей
	* @param array $query_data Массив с данными
	*
	* @return integer Возвращаем статус выполнения (1 или -1)
	*
	* @version 0.1 27.04.2015
	* @author Дмитрий Щербаков <atomcms@ya.ru>
	*/
	function many_inserts($table_name, $data_fields, $query_data) {
		$this->connect();

		function placeholders($text, $count = 0, $separator = ",") {
			$result = [];

			if($count > 0) {
				for($x=0; $x<$count; $x++) {
					$result[] = $text;
				}
			}

			return implode($separator, $result);
		}

		$insert_values = [];

		foreach($query_data as $d) {
			$question_marks[] = '(' . placeholders('?', sizeof($d)) . ')';
			$insert_values = array_merge($insert_values, array_values($d));
		}

		$query_str = "INSERT INTO `" . $table_name . "` (" . implode(",", array_keys($data_fields)) . ") VALUES " . implode(',', $question_marks);

		$this->connect_id->beginTransaction(); // also helps speed up your inserts.

		$STH = $this->connect_id->prepare($query_str);
		try {
			$STH->execute($insert_values);
		} catch (PDOException $e){
			return $this->error($query_str, $e->getMessage());
		}

		$this->connect_id->commit();

		return 1;
	}

	/**
	* Сообщение об ошибке при выполнении запроса
	*
	* @param string $query_str Строка запроса
	* @param string $msg Сообщение с ошибкой
	*
	* @return integer Возвращаем статус ошибки
	*
	* @version 0.1 27.04.2015
	* @author Дмитрий Щербаков <atomcms@ya.ru>
	*/
	private function error($query_str, $msg) {
		Error::ins(0, 'Извините произошла ошибка в БД', $msg . ' (' . $query_str . ')', __FILE__, __FUNCTION__, __LINE__);
		return -1;
	}
}
?>