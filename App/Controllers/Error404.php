<?php
/**
 * Error404
 *
 * Класс для вызова страницы ошибки 404
 *
 * @version 0.6 27.10.2015
 * @author Дмитрий Щербаков <atomcms@ya.ru>
 */

namespace AtomFramework\Controllers;

class Error404 {
    /**
     * Запуск контроллера
     *
     * @return content
     *
     * @version 0.6 27.10.2015
     * @author Дмитрий Щербаков <atomcms@ya.ru>
     */
    static function start() {
        header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');
        \AtomFramework\Utility\Error::ins(0, 'ERR', '', 'Ошибка 404: Страница не найдена (запрошен адрес: ' . $_SERVER['REQUEST_URI'] . ')', __FILE__, __FUNCTION__, __LINE__);
        \AtomFramework\Views\Error404::show();
        return null;
    }
}
?>