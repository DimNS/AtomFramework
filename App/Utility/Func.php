<?php
/**
 * Func
 *
 * Класс для вызова различных функций, используемых в разных частях приложения
 *
 * @version 0.6 27.10.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */

namespace AtomFramework\Utility;

use AtomFramework\Configs\Config;

class Func {
    /**
     * Поместить в массиве ИД в ключи
     *
     * @param array $arr Массив в котором нужно одно поле сделать ключами
     * @param array $key Название ключа массива
     *
     * @return array Новый массив
     *
     * @version 0.1 27.04.2015
     * @author Дмитрий Щербаков <atomcms@ya.ru>
     */
    static function array_id2key($arr, $key) {
        $result = [];

        if (count($arr) > 0) {
            foreach ($arr as $item) {
                $result[$item[$key]] = $item;
            }

            return $result;
        } else {
            return $arr;
        }
    }

    /**
     * Показать сообщение, если нет данных
     *
     * @param string $title   Заголовок
     * @param string $message Сообщение
     *
     * @return string Блок с сообщением
     *
     * @version 0.1 27.04.2015
     * @author Дмитрий Щербаков <atomcms@ya.ru>
     */
    static function get_empty_message($title, $message) {
        return '<div class="empty_message"><div><i class="fa fa-frown-o"></i> Ой, ' . $title . '</div><span>Пожалуйста, ' . $message . '</span></div>';
    }

    /**
     * Склонение существительных после числительных
     *
     * Массив слов составляется по правилу 1, 4, 5 = одно яблоко, четыре яблока, пять яблок
     *
     * @param type $number Число
     * @param type $titles Массив со словами для выбора верного варианта
     *
     * @example get_num_ending(12, ['яблоко', 'яблока', 'яблок']); // Вернет "яблок"
     *
     * @return string Правильно выбранное слово
     *
     * @version 0.1 27.04.2015
     * @author Дмитрий Щербаков <atomcms@ya.ru>
     */
    static function get_num_ending($number, $titles) {
        $cases = array (2, 0, 1, 1, 1, 2);
        return $titles[ ($number%100 > 4 && $number %100 < 20) ? 2 : $cases[min($number%10, 5)] ];
    }

    /**
     * Отформатировать число красиво
     *
     * @param integer $num           Число для форматирования
     * @param integer $decimal_count Количество знаков после запятой
     *
     * @return string Отформатированная строка
     *
     * @version 0.1 27.04.2015
     * @author Дмитрий Щербаков <atomcms@ya.ru>
     */
    static function get_number_format($num, $decimal_count) {
        return number_format($num, $decimal_count, '.', ' ');
    }

    /**
     * Вывод в красивом виде строк и массивов для отладки
     *
     * @param array|string $mixed
     *
     * @return null
     *
     * @version 0.1 27.04.2015
     * @author Дмитрий Щербаков <atomcms@ya.ru>
     */
    static function debug($mixed) {
        echo '<pre>';
        print_r($mixed);
        echo '</pre>';
    }

    /**
     * Получаем Московское время
     *
     * @param integer $timestamp Время по Гринвичу
     *
     * @return integer Московское время
     *
     * @version 0.1 27.04.2015
     * @author Дмитрий Щербаков <atomcms@ya.ru>
     */
    static function moscow_time($timestamp) {
        // Смещение в секундах для определения Московского времени
        $timestamp += 3 * 3600;
        return $timestamp;
    }

    /**
     * Генерация нового пароля
     *
     * @return string Новый пароль
     *
     * @version 0.1 27.04.2015
     * @author Дмитрий Щербаков <atomcms@ya.ru>
     */
    static function generate_password() {
        $chars = 'qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP';
        $max = 8;
        $size = strlen($chars) - 1;
        $password = null;

        while($max--)
            $password .= $chars[mt_rand(0, $size)];

        return $password;
    }

    /**
     * Определение хеша для пароля
     *
     * @param string $pwd Пароль
     *
     * @return string Хеш
     *
     * @version 0.6 27.10.2015
     * @author Дмитрий Щербаков <atomcms@ya.ru>
     */
    static function collect_password($pwd) {
        $salt = '$2y$11$' . substr(md5(uniqid(rand(), true)), 0, 22);
        return crypt($pwd, $salt);
    }

    /**
     * Проверка авторизации пользователя в системе
     *
     * @return boolean
     *
     * @version 0.1 27.04.2015
     * @author Дмитрий Щербаков <atomcms@ya.ru>
     */
    static function is_login() {
        if (Config::$session['id'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Поиск реального IP-адрес клиента
     *
     * @return string IP-адрес
     *
     * @version 0.1 27.04.2015
     * @author Дмитрий Щербаков <atomcms@ya.ru>
     */
    static function get_ip() {
        $serverVars = [
            "HTTP_X_FORWARDED_FOR",
            "HTTP_X_FORWARDED",
            "HTTP_FORWARDED_FOR",
            "HTTP_FORWARDED",
            "HTTP_VIA",
            "HTTP_X_COMING_FROM",
            "HTTP_COMING_FROM",
            "HTTP_CLIENT_IP",
            "HTTP_XROXY_CONNECTION",
            "HTTP_PROXY_CONNECTION",
            "HTTP_USERAGENT_VIA",
        ];

        foreach ($serverVars as $serverVar) {
            if (!empty($_SERVER) && !empty($_SERVER[$serverVar])) {
                $proxyIP = $_SERVER[$serverVar];
            } else {
                if (!empty($_ENV) && isset($_ENV[$serverVar])) {
                    $proxyIP = $_ENV[$serverVar];
                } else {
                    if (@getenv($serverVar)) {
                        $proxyIP = getenv($serverVar);
                    }
                }
            }

            if (!empty($proxyIP) && preg_match('|^([0-9]{1,3}\.){3,3}[0-9]{1,3}|', $proxyIP)) {
                return $proxyIP;
            }
        }

        if (!empty($proxyIP)) {
            if (preg_match('|^([0-9]{1,3}\.){3,3}[0-9]{1,3}|', $proxyIP)) {
                return $proxyIP;
            }
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }
}
?>