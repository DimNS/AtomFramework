<?php
/**
 * Main
 *
 * Класс для вызова главной страницы
 *
 * @version 0.6.6 07.11.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */

namespace AtomFramework\Controllers;

use AtomFramework\Configs\Config;
use AtomFramework\Models\Main as MainModel;
use AtomFramework\Utility\Func;
use AtomFramework\Views\Main as MainView;

class Main {
    /**
     * Запуск контроллера
     *
     * @return content
     *
     * @version 0.6.3 04.11.2015
     * @author Дмитрий Щербаков <atomcms@ya.ru>
     */
    static function start() {
        if (Func::is_login()) {
            // Получаем данные для главной страницы (с авторизацией)
            return MainView::dashboard(MainModel::dashboard());
        } else {
            // Получаем данные для главной страницы (без авторизации)
            return MainView::landing(MainModel::landing());
        }
    }

    /**
     * Отображаем главную страницу с ошибкой
     *
     * @param string $message_code Код сообщения
     * @param string $message_text Текст сообщения
     *
     * @return content
     *
     * @version 0.6.6 07.11.2015
     * @author Дмитрий Щербаков <atomcms@ya.ru>
     */
    static function error($message_code, $message_text) {
        // Код сообщения и текст сообщения, если переданы
        Config::$global['message_code'] = $message_code;
        Config::$global['message_text'] = $message_text;

        // Получаем данные для главной страницы (без авторизации)
        return MainView::landing(MainModel::landing(false));
    }
}
?>