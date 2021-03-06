<?php
/**
 * Функции системы
 *
 * @version 0.8.0 28.01.2016
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */

use AtomFramework\Configs\Config;

// Запускаем сессию
session_start();

// Устанавливаем часовой пояс по Гринвичу
date_default_timezone_set('UTC');

//
//
//  `7MM"""Mq.  `7MM"""YMM    .g8""8q. `7MMF'   `7MF'`7MMF'`7MM"""Mq.  `7MM"""YMM
//    MM   `MM.   MM    `7  .dP'    `YM. MM       M    MM    MM   `MM.   MM    `7
//    MM   ,M9    MM   d    dM'      `MM MM       M    MM    MM   ,M9    MM   d
//    MMmmdM9     MMmmMM    MM        MM MM       M    MM    MMmmdM9     MMmmMM
//    MM  YM.     MM   Y  , MM.      ,MP MM       M    MM    MM  YM.     MM   Y  ,
//    MM   `Mb.   MM     ,M `Mb.    ,dP' YM.     ,M    MM    MM   `Mb.   MM     ,M
//  .JMML. .JMM..JMMmmmmMMM   `"bmmd"'    `bmmmmd"'  .JMML..JMML. .JMM..JMMmmmmMMM
//                                MMb
//                                 `bood'

// Подключаем автозагрузку классов
require_once('vendor/autoload.php');

// Подключаем файлы с настройками
require_once('Configs/Database.php');

// Проверка браузера на мобильность
$mdetect = new \Detection\MobileDetect();

// Работа с файлом .env
$dotenv_version = new Dotenv\Dotenv(__DIR__, '.version');
$dotenv_version->load();

//
//
//        db   `7MMF'   `7MF'MMP""MM""YMM   .g8""8q. `7MMF'        .g8""8q.      db      `7MM"""Yb.
//       ;MM:    MM       M  P'   MM   `7 .dP'    `YM. MM        .dP'    `YM.   ;MM:       MM    `Yb.
//      ,V^MM.   MM       M       MM      dM'      `MM MM        dM'      `MM  ,V^MM.      MM     `Mb
//     ,M  `MM   MM       M       MM      MM        MM MM        MM        MM ,M  `MM      MM      MM
//     AbmmmqMA  MM       M       MM      MM.      ,MP MM      , MM.      ,MP AbmmmqMA     MM     ,MP
//    A'     VML YM.     ,M       MM      `Mb.    ,dP' MM     ,M `Mb.    ,dP'A'     VML    MM    ,dP'
//  .AMA.   .AMMA.`bmmmmd"'     .JMML.      `"bmmd"' .JMMmmmmMMM   `"bmmd"'.AMA.   .AMMA..JMMmmmdP'
//
//

// Регистрируем функцию, которая всегда выполняется перед завершением работы скрипт, даже если он пытается завершиться до своего логического конца
if (!isset($_REQUEST['ajax'])) {
    register_shutdown_function(function() {
        // Выводим время, прошедшее с начала запуска
        echo "\n<!-- Execution time: " . (microtime(true) - Config::$global['start_script']) . " sec. -->";

        // Выводим количество затраченной оперативной памяти
        echo "\n<!-- Peak memory: " . number_format(memory_get_peak_usage(), 0, '.', ' ') . " bytes -->";
    });
}

//
//
//  `7MMF'   `7MF' db      `7MM"""Mq.  `7MMF'      db      `7MM"""Yp, `7MMF'      `7MM"""YMM   .M"""bgd
//    `MA     ,V  ;MM:       MM   `MM.   MM       ;MM:       MM    Yb   MM          MM    `7  ,MI    "Y
//     VM:   ,V  ,V^MM.      MM   ,M9    MM      ,V^MM.      MM    dP   MM          MM   d    `MMb.
//      MM.  M' ,M  `MM      MMmmdM9     MM     ,M  `MM      MM"""bg.   MM          MMmmMM      `YMMNq.
//      `MM A'  AbmmmqMA     MM  YM.     MM     AbmmmqMA     MM    `Y   MM      ,   MM   Y  , .     `MM
//       :MM;  A'     VML    MM   `Mb.   MM    A'     VML    MM    ,9   MM     ,M   MM     ,M Mb     dM
//        VF .AMA.   .AMMA..JMML. .JMM..JMML..AMA.   .AMMA..JMMmmmd9  .JMMmmmmMMM .JMMmmmmMMM P"Ybmmd"
//
//

/**
 * @var Config::$global['currtime'] Текущее время (integer)
 */
Config::$global['currtime'] = time();

/**
 * @var Config::$global['currtime_format'] Текущее время в формате YYYY-MM-DD HH:MM:SS (string)
 */
Config::$global['currtime_format'] = date('Y-m-d H:i:s', Config::$global['currtime']);

/**
 * @var Config::$global['start_script'] Врема запуска скрипта (integer)
 */
Config::$global['start_script'] = microtime(true);

/**
 * @var Config::$global['mobile'] Мобильный браузер или нет (boolean)
 */
Config::$global['mobile'] = $mdetect->isMobile();

/**
 * @var Config::$global['start_year'] Год запуска проекта (string)
 */
Config::$global['start_year'] = (date('Y', Config::$global['currtime']) === Config::$global['start_year'] ? Config::$global['start_year'] : Config::$global['start_year'] . '-' . date('Y', Config::$global['currtime']));

/**
 * @var Config::$global['session_die_time'] Время удаления подвисших сессий (integer)
 */
Config::$global['session_die_time'] = Config::$global['currtime'] - Config::$global['session_die_time'];

/**
 * @var Config::$global['path_http_root'] Виртуальный путь до папки проекта (string)
 */
Config::$global['path_http_root'] = 'http://' . $_SERVER['HTTP_HOST'] . Config::$global['path_short_root'];

/**
 * @var Config::$global['path_home_root'] Абсолютный путь до папки с проектом (string)
 */
Config::$global['path_home_root'] = $_SERVER['DOCUMENT_ROOT'] . Config::$global['path_short_root'];

//
//
//  `7MMM.     ,MMF'           .M"""bgd   .g8""8q. `7MMF'
//    MMMb    dPMM            ,MI    "Y .dP'    `YM. MM
//    M YM   ,M MM `7M'   `MF'`MMb.     dM'      `MM MM
//    M  Mb  M' MM   VA   ,V    `YMMNq. MM        MM MM
//    M  YM.P'  MM    VA ,V   .     `MM MM.      ,MP MM      ,
//    M  `YM'   MM     VVV    Mb     dM `Mb.    ,dP' MM     ,M
//  .JML. `'  .JMML.   ,V     P"Ybmmd"    `"bmmd"' .JMMmmmmMMM
//                    ,V                      MMb
//                 OOb"                        `bood'

// Подключаем класс для работы с БД
Config::$global['db'] = new \AtomFramework\Utility\DBMySQL(DB_HOST, DB_DATABASE, DB_USER, DB_PASSWORD);

//
//
//    .g8""8q. `7MMF'   `7MF'`7MM"""YMM  `7MM"""Mq.`YMM'   `MM'    `7MM"""YMM `7MN.   `7MF'.M"""bgd
//  .dP'    `YM. MM       M    MM    `7    MM   `MM. VMA   ,V        MM    `7   MMN.    M ,MI    "Y
//  dM'      `MM MM       M    MM   d      MM   ,M9   VMA ,V         MM   d     M YMb   M `MMb.
//  MM        MM MM       M    MMmmMM      MMmmdM9     VMMP          MM""MM     M  `MN. M   `YMMNq.
//  MM.      ,MP MM       M    MM   Y  ,   MM  YM.      MM           MM   Y     M   `MM.M .     `MM
//  `Mb.    ,dP' YM.     ,M    MM     ,M   MM   `Mb.    MM           MM         M     YMM Mb     dM
//    `"bmmd"'    `bmmmmd"'  .JMMmmmmMMM .JMML. .JMM. .JMML.       .JMML.     .JML.    YM P"Ybmmd"
//        MMb
//         `bood'

/**
 * Функция для запуска запроса
 *
 * @param string  $query Строка sql-запроса
 * @param array   $data  Массив с данными для запроса
 * @param string  $file  Путь к файлу в котором случился запрос
 * @param integer $line  Номер строки где случился запрос
 *
 * @return mixed
 *
 * @version 0.1 27.04.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */
function query($query, $data, $file, $line) {
    return Config::$global['db']->query($query, $data, $file, $line);
}
?>