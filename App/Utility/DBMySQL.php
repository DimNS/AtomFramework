<?php
/**
 * DBMySQL
 *
 * Класс для работы с БД MySQL
 *
 * @version 0.6 27.10.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */

namespace AtomFramework\Utility;

use PDO;
use PDOException;

use AtomFramework\Utility\AtomException;

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
     * @version 0.6 27.10.2015
     * @author Дмитрий Щербаков <atomcms@ya.ru>
     */
    private function connect() {
        if (gettype($this->connect_id) === 'integer') {
            // Пробуем подключиться к БД
            try {
                $this->connect_id = new PDO("mysql:host=$this->host;dbname=$this->database", $this->user, $this->password, [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8']);
                $this->connect_id->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $e) {
                throw new AtomException($e->getMessage(), 10);
            }
        }

        return $this->connect_id;
    }

    /**
     * Выполнение запроса
     *
     * @param string  $query_str  Строка запроса
     * @param array   $query_data Массив с данными
     * @param string  $file       Путь к файлу в котором случился запрос
     * @param integer $line       Номер строки где случился запрос
     *
     * @return array|integer Возвращается результат выполнения запроса в зависимости от типа запроса
     *
     * @version 0.1 27.04.2015
     * @author Дмитрий Щербаков <atomcms@ya.ru>
     */
    function query($query_str, $query_data, $file, $line) {
        $this->connect();

        $query_type = mb_strtolower(mb_substr($query_str, 0, mb_strpos($query_str, ' ', 0, 'utf-8'), 'utf-8'), 'utf-8');

        //AtomFramework\Utility\Func::debug($query_str);
        //AtomFramework\Utility\Func::debug($query_data);

        switch ($query_type) {
            case 'select':
                try {
                    $STH = $this->connect_id->prepare($query_str);
                    $STH->execute($query_data);
                    $STH->setFetchMode(PDO::FETCH_ASSOC);
                    return $STH->fetchAll();
                } catch(PDOException $e) {
                    throw new AtomException($e->getMessage() . ' (' . $query_str . ')', 10, $file, $line);
                }
            break;

            case 'insert':
                try {
                    $STH = $this->connect_id->prepare($query_str);
                    $STH->execute($query_data);
                    return $this->connect_id->lastInsertId();
                } catch(PDOException $e) {
                    throw new AtomException($e->getMessage() . ' (' . $query_str . ')', 10, $file, $line);
                }
            break;

            default:
                try {
                    $STH = $this->connect_id->prepare($query_str);
                    $STH->execute($query_data);
                    return $STH->rowCount();
                } catch(PDOException $e) {
                    throw new AtomException($e->getMessage() . ' (' . $query_str . ')', 10, $file, $line);
                }
            break;
        }
    }
}
?>